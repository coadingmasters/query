<?php

namespace Tests\Feature\Admin;

use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MessagesTest extends TestCase
{
    public function test_the_messages_page_is_behind_auth(): void
    {
        $this->get('/admin/messages')->assertRedirect(route('admin.login'));
    }

    public function test_mark_handled_is_behind_auth(): void
    {
        $message = ContactMessage::create([
            'name' => 'Alex', 'email' => 'alex@example.com',
            'subject' => 'A question', 'message' => 'Hello',
        ]);

        $this->post(route('admin.messages.handled', $message))
            ->assertRedirect(route('admin.login'));
    }

    public function test_reply_is_behind_auth(): void
    {
        $message = ContactMessage::create([
            'name' => 'Alex', 'email' => 'alex@example.com',
            'subject' => 'A question', 'message' => 'Hello',
        ]);

        $this->postJson(route('admin.messages.reply', $message), ['body' => 'Thanks for writing in.'])
            ->assertUnauthorized();
    }

    public function test_a_reply_emails_the_original_sender_and_marks_the_message_handled(): void
    {
        Mail::fake();

        $message = ContactMessage::create([
            'name' => 'Alex', 'email' => 'alex@example.com',
            'subject' => 'A question about cat care', 'message' => 'Is tuna okay daily?',
        ]);

        $this->actingAs(User::factory()->create())
            ->postJson(route('admin.messages.reply', $message), ['body' => 'Not daily — an occasional treat is best.'])
            ->assertOk()
            ->assertJson(['handled' => true]);

        Mail::assertSent(ContactMessageReply::class, function (ContactMessageReply $mail) use ($message) {
            return $mail->hasTo($message->email)
                && $mail->original->is($message)
                && $mail->body === 'Not daily — an occasional treat is best.';
        });

        $this->assertNotNull($message->fresh()->handled_at);
    }

    public function test_a_reply_requires_a_non_empty_body(): void
    {
        Mail::fake();

        $message = ContactMessage::create([
            'name' => 'Alex', 'email' => 'alex@example.com',
            'subject' => 'A question', 'message' => 'Hello',
        ]);

        $this->actingAs(User::factory()->create())
            ->postJson(route('admin.messages.reply', $message), ['body' => ''])
            ->assertUnprocessable();

        Mail::assertNothingSent();
    }

    public function test_replying_to_an_already_handled_message_does_not_change_its_handled_time(): void
    {
        Mail::fake();

        $handledAt = now()->subDay();
        $message = ContactMessage::create([
            'name' => 'Alex', 'email' => 'alex@example.com',
            'subject' => 'A question', 'message' => 'Hello', 'handled_at' => $handledAt,
        ]);

        $this->actingAs(User::factory()->create())
            ->postJson(route('admin.messages.reply', $message), ['body' => 'Following up.'])
            ->assertOk();

        $this->assertSame($handledAt->timestamp, $message->fresh()->handled_at->timestamp);
    }

    public function test_delete_is_behind_auth(): void
    {
        $message = ContactMessage::create([
            'name' => 'Alex', 'email' => 'alex@example.com',
            'subject' => 'A question', 'message' => 'Hello',
        ]);

        $this->deleteJson(route('admin.messages.destroy', $message))->assertUnauthorized();
        $this->assertModelExists($message);
    }

    public function test_a_logged_in_admin_can_delete_a_message(): void
    {
        $message = ContactMessage::create([
            'name' => 'Alex', 'email' => 'alex@example.com',
            'subject' => 'A question', 'message' => 'Hello',
        ]);

        $this->actingAs(User::factory()->create())
            ->deleteJson(route('admin.messages.destroy', $message))
            ->assertOk()
            ->assertJson(['deleted' => true]);

        $this->assertModelMissing($message);
    }

    public function test_the_search_filter_matches_name_email_subject_and_body(): void
    {
        ContactMessage::create(['name' => 'Priya Shah', 'email' => 'x@example.com', 'subject' => 'A correction', 'message' => 'Body one']);
        ContactMessage::create(['name' => 'Someone Else', 'email' => 'priya@example.com', 'subject' => 'Other', 'message' => 'Body two']);
        ContactMessage::create(['name' => 'Nobody', 'email' => 'z@example.com', 'subject' => 'Priya mentioned here', 'message' => 'Body three']);
        ContactMessage::create(['name' => 'Irrelevant', 'email' => 'z@example.com', 'subject' => 'Other', 'message' => 'No match at all']);

        $this->actingAs(User::factory()->create())
            ->get('/admin/messages?q=priya')
            ->assertOk()
            ->assertSee('Priya Shah')
            ->assertSee('Someone Else')
            ->assertSee('Nobody')
            ->assertDontSee('Irrelevant');
    }

    public function test_the_status_filter_separates_new_from_handled(): void
    {
        ContactMessage::create(['name' => 'New One', 'email' => 'a@example.com', 'subject' => 'S', 'message' => 'M']);
        ContactMessage::create(['name' => 'Handled One', 'email' => 'b@example.com', 'subject' => 'S', 'message' => 'M', 'handled_at' => now()]);

        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/messages?status=new')
            ->assertSee('New One')->assertDontSee('Handled One');

        $this->actingAs($admin)->get('/admin/messages?status=handled')
            ->assertSee('Handled One')->assertDontSee('New One');
    }

    public function test_the_date_range_filter_excludes_messages_outside_it(): void
    {
        $inRange = ContactMessage::create(['name' => 'In Range', 'email' => 'a@example.com', 'subject' => 'S', 'message' => 'M']);
        $inRange->created_at = now()->subDays(2);
        $inRange->save();

        $outOfRange = ContactMessage::create(['name' => 'Out Of Range', 'email' => 'b@example.com', 'subject' => 'S', 'message' => 'M']);
        $outOfRange->created_at = now()->subDays(20);
        $outOfRange->save();

        $this->actingAs(User::factory()->create())
            ->get('/admin/messages?from='.now()->subDays(5)->toDateString().'&to='.now()->toDateString())
            ->assertOk()
            ->assertSee('In Range')
            ->assertDontSee('Out Of Range');
    }
}
