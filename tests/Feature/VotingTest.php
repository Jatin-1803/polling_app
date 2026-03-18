<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VotingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_vote_and_cannot_vote_twice()
    {
        $poll = Poll::factory()->create();
        $opt = $poll->options()->create(['option_text' => 'A']);
        $poll->options()->create(['option_text' => 'B']);

        $user = User::factory()->create();
        $this->actingAs($user);

        $resp = $this->post(route('polls.public.vote', $poll->uuid), ['option_id' => $opt->id]);
        $resp->assertStatus(200);

        // second vote attempt
        $resp2 = $this->post(route('polls.public.vote', $poll->uuid), ['option_id' => $opt->id]);
        $resp2->assertStatus(409);
    }

    public function test_guest_ip_cannot_vote_twice()
    {
        $poll = Poll::factory()->create();
        $opt = $poll->options()->create(['option_text' => 'A']);
        $poll->options()->create(['option_text' => 'B']);

        $this->post(route('polls.public.vote', $poll->uuid), ['option_id' => $opt->id], ['REMOTE_ADDR' => '1.2.3.4'])
             ->assertStatus(200);

        $this->post(route('polls.public.vote', $poll->uuid), ['option_id' => $opt->id], ['REMOTE_ADDR' => '1.2.3.4'])
             ->assertStatus(409);
    }
}