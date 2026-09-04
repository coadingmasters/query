<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    public function test_the_index_page_renders(): void
    {
        $this->get('/admin/videos')->assertOk();
    }

    public function test_uploading_a_video_stores_it_as_is(): void
    {
        $file = UploadedFile::fake()->create('cat-playing.mp4', 5000, 'video/mp4');

        $response = $this->post('/admin/videos', [
            'video' => $file,
            'name' => 'cat-playing',
            'description' => 'A cat chasing a laser pointer',
            'category' => 'blog',
        ]);

        $response->assertCreated();

        $video = Video::first();
        $this->assertNotNull($video);
        $this->assertSame('cat-playing', $video->name);
        $this->assertSame('blog', $video->category);
        $this->assertSame('video/mp4', $video->mime_type);
        $this->assertStringEndsWith('.mp4', $video->path);
        $this->assertGreaterThan(0, $video->size_bytes);

        Storage::disk('public')->assertExists($video->path);
    }

    /**
     * The admin grid renders <video :src="item.url"> straight from this
     * JSON. url is a PHP-only accessor unless it is appended, so this is the
     * one test standing between "works in tinker" and every card being broken.
     */
    public function test_the_upload_response_carries_a_working_url(): void
    {
        $file = UploadedFile::fake()->create('cat-playing.mp4', 5000, 'video/mp4');

        $response = $this->post('/admin/videos', [
            'video' => $file,
            'name' => 'cat-playing',
            'category' => 'blog',
        ]);

        $video = Video::first();
        $response->assertJson(['video' => ['url' => $video->url]]);
    }

    public function test_a_non_video_file_is_rejected(): void
    {
        $file = UploadedFile::fake()->create('not-a-video.pdf', 100);

        $this->postJson('/admin/videos', [
            'video' => $file,
            'category' => 'general',
        ])->assertJsonValidationErrors(['video']);
    }

    public function test_an_unsupported_video_format_is_rejected(): void
    {
        $file = UploadedFile::fake()->create('old-clip.avi', 5000, 'video/x-msvideo');

        $this->postJson('/admin/videos', [
            'video' => $file,
            'category' => 'general',
        ])->assertJsonValidationErrors(['video']);
    }

    public function test_an_oversized_video_is_rejected(): void
    {
        $file = UploadedFile::fake()->create('huge.mp4', 204801, 'video/mp4');

        $this->postJson('/admin/videos', [
            'video' => $file,
            'category' => 'general',
        ])->assertJsonValidationErrors(['video']);
    }

    public function test_an_untitled_upload_keeps_the_original_filename_as_its_name(): void
    {
        $file = UploadedFile::fake()->create('kitten first steps.mp4', 5000, 'video/mp4');

        $this->post('/admin/videos', [
            'video' => $file,
            'category' => 'general',
        ])->assertCreated();

        $this->assertTrue(Video::where('name', 'kitten first steps')->exists());
    }

    public function test_a_video_can_be_renamed_and_recategorised(): void
    {
        $video = Video::create([
            'name' => 'old-name', 'path' => 'videos/old-name-abc123.mp4', 'original_filename' => 'old.mp4',
            'mime_type' => 'video/mp4', 'size_bytes' => 1000, 'category' => 'general',
        ]);

        $response = $this->putJson("/admin/videos/{$video->id}", [
            'name' => 'new-name',
            'description' => 'A better description',
            'category' => 'tools',
        ]);

        $response->assertOk();
        $this->assertSame('new-name', $video->fresh()->name);
        $this->assertSame('tools', $video->fresh()->category);
    }

    public function test_deleting_a_video_removes_the_file_from_storage(): void
    {
        Storage::disk('public')->put('videos/to-delete-abc123.mp4', 'fake-video-content');

        $video = Video::create([
            'name' => 'to-delete', 'path' => 'videos/to-delete-abc123.mp4', 'original_filename' => 'x.mp4',
            'mime_type' => 'video/mp4', 'size_bytes' => 1000, 'category' => 'general',
        ]);

        $this->deleteJson("/admin/videos/{$video->id}")->assertOk();

        $this->assertModelMissing($video);
        Storage::disk('public')->assertMissing('videos/to-delete-abc123.mp4');
    }

    public function test_the_videos_page_is_behind_auth(): void
    {
        auth()->logout();

        $this->get('/admin/videos')->assertRedirect(route('admin.login'));
    }
}
