<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->create());
    }

    public function test_the_index_page_renders(): void
    {
        $this->get('/admin/media')->assertOk();
    }

    public function test_uploading_an_image_converts_it_to_webp_and_stores_it(): void
    {
        $file = UploadedFile::fake()->image('cat-photo.png', 800, 600);

        $response = $this->post('/admin/media', [
            'images' => [$file],
            'name' => 'maine-coon-hero',
            'alt_text' => 'A Maine Coon sitting on a windowsill',
            'category' => 'breeds',
        ]);

        $response->assertCreated();

        $media = Media::first();
        $this->assertNotNull($media);
        $this->assertSame('maine-coon-hero', $media->name);
        $this->assertSame('breeds', $media->category);
        $this->assertSame('image/webp', $media->mime_type);
        $this->assertStringEndsWith('.webp', $media->path);
        $this->assertSame(800, $media->width);
        $this->assertSame(600, $media->height);
        $this->assertGreaterThan(0, $media->size_bytes);

        Storage::disk('public')->assertExists($media->path);
    }

    /**
     * The admin grid renders <img :src="item.url"> straight from this JSON.
     * url is a PHP-only accessor unless it is appended, so this is the one
     * test standing between "works in tinker" and "every card is broken".
     */
    public function test_the_upload_response_carries_a_working_url(): void
    {
        $file = UploadedFile::fake()->image('cat-photo.png', 800, 600);

        $response = $this->post('/admin/media', [
            'images' => [$file],
            'name' => 'maine-coon-hero',
            'category' => 'breeds',
        ]);

        $media = Media::first();
        $response->assertJson(['media' => [['url' => $media->url]]]);
    }

    public function test_the_index_page_carries_a_working_url_for_existing_media(): void
    {
        $media = Media::create([
            'name' => 'existing', 'path' => 'media/existing-abc123.webp', 'original_filename' => 'x.png',
            'mime_type' => 'image/webp', 'width' => 100, 'height' => 100, 'size_bytes' => 1000, 'category' => 'general',
        ]);

        // path alone would also contain the filename, so this checks for the
        // url accessor specifically: its "/storage/" prefix that path lacks.
        // Js::from() escapes slashes, so check for the pieces either side.
        $html = $this->get('/admin/media')->getContent();
        $this->assertMatchesRegularExpression('#storage.*existing-abc123\.webp#', $html);
    }

    public function test_a_non_image_file_is_rejected(): void
    {
        $file = UploadedFile::fake()->create('not-an-image.pdf', 100);

        $this->postJson('/admin/media', [
            'images' => [$file],
            'category' => 'general',
        ])->assertJsonValidationErrors(['images.0']);
    }

    public function test_bulk_upload_names_each_file_from_its_own_filename(): void
    {
        $files = [
            UploadedFile::fake()->image('first-cat.jpg', 400, 400),
            UploadedFile::fake()->image('second cat!.jpg', 400, 400),
        ];

        $response = $this->post('/admin/media', [
            'images' => $files,
            'name' => 'this should be ignored for bulk uploads',
            'category' => 'general',
        ]);

        $response->assertCreated();
        $this->assertSame(2, Media::count());
        $this->assertTrue(Media::where('name', 'first-cat')->exists());
        $this->assertTrue(Media::where('name', 'second cat!')->exists());
    }

    public function test_an_oversized_dimension_is_scaled_down_not_rejected(): void
    {
        $file = UploadedFile::fake()->image('huge.jpg', 5000, 3000);

        $this->post('/admin/media', [
            'images' => [$file],
            'category' => 'general',
        ])->assertCreated();

        $media = Media::first();
        $this->assertLessThanOrEqual(2400, $media->width);
        $this->assertLessThanOrEqual(2400, $media->height);
    }

    public function test_an_image_can_be_renamed_and_recategorised(): void
    {
        $media = Media::create([
            'name' => 'old-name', 'path' => 'media/old-name-abc123.webp', 'original_filename' => 'old.png',
            'mime_type' => 'image/webp', 'width' => 100, 'height' => 100, 'size_bytes' => 1000, 'category' => 'general',
        ]);

        $response = $this->putJson("/admin/media/{$media->id}", [
            'name' => 'new-name',
            'alt_text' => 'A better description',
            'category' => 'tools',
        ]);

        $response->assertOk();
        $this->assertSame('new-name', $media->fresh()->name);
        $this->assertSame('tools', $media->fresh()->category);
    }

    public function test_deleting_an_image_removes_the_file_from_storage(): void
    {
        Storage::disk('public')->put('media/to-delete-abc123.webp', 'fake-webp-content');

        $media = Media::create([
            'name' => 'to-delete', 'path' => 'media/to-delete-abc123.webp', 'original_filename' => 'x.png',
            'mime_type' => 'image/webp', 'width' => 100, 'height' => 100, 'size_bytes' => 1000, 'category' => 'general',
        ]);

        $this->deleteJson("/admin/media/{$media->id}")->assertOk();

        $this->assertModelMissing($media);
        Storage::disk('public')->assertMissing('media/to-delete-abc123.webp');
    }

    public function test_the_media_page_is_behind_auth(): void
    {
        auth()->logout();

        $this->get('/admin/media')->assertRedirect(route('admin.login'));
    }
}
