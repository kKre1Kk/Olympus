<?php

namespace Tests\Feature;

use App\Models\FriendRequest;
use App\Models\God;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_comment_on_own_and_other_users_gods(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $alicesGod = $this->makeGod($alice, 'Афина');
        $bobsGod = $this->makeGod($bob, 'Посейдон');

        $this->actingAs($alice)
            ->post(route('comments.store', $alicesGod), ['body' => 'Мой комментарий'])
            ->assertRedirect(route('gods.show', $alicesGod));

        $this->post(route('comments.store', $bobsGod), ['body' => 'Комментарий другу'])
            ->assertRedirect(route('gods.show', $bobsGod));

        $this->assertDatabaseHas('comments', [
            'god_id' => $alicesGod->id,
            'user_id' => $alice->id,
            'body' => 'Мой комментарий',
        ]);

        $this->assertDatabaseHas('comments', [
            'god_id' => $bobsGod->id,
            'user_id' => $alice->id,
            'body' => 'Комментарий другу',
        ]);

        $this->assertEquals($alice->id, $bobsGod->comments()->first()->user->id);
    }

    public function test_guest_cannot_post_comments(): void
    {
        $god = $this->makeGod(User::factory()->create(), 'Зевс');

        $this->post(route('comments.store', $god), ['body' => 'Без входа'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_friendship_requires_acceptance_and_is_mutual(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        $this->actingAs($alice)
            ->post(route('friends.store', $bob))
            ->assertRedirect(route('users.show', $bob));

        $friendRequest = FriendRequest::firstOrFail();

        $this->assertDatabaseHas('friend_requests', [
            'sender_id' => $alice->id,
            'receiver_id' => $bob->id,
        ]);
        $this->assertDatabaseCount('friendships', 0);

        $this->actingAs($bob)
            ->get(route('friend-requests.index'))
            ->assertOk()
            ->assertSee($alice->username);

        $this->post(route('friend-requests.accept', $friendRequest))
            ->assertRedirect(route('friend-requests.index'));

        $this->assertDatabaseCount('friend_requests', 0);
        $this->assertDatabaseHas('friendships', [
            'user_id' => $alice->id,
            'friend_id' => $bob->id,
        ]);
        $this->assertDatabaseHas('friendships', [
            'user_id' => $bob->id,
            'friend_id' => $alice->id,
        ]);

        $this->delete(route('friends.destroy', $alice))
            ->assertRedirect(route('users.show', $alice));

        $this->assertDatabaseCount('friendships', 0);
    }

    public function test_sender_and_strangers_cannot_accept_another_users_request(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $stranger = User::factory()->create();

        $this->actingAs($alice)
            ->post(route('friends.store', $bob))
            ->assertRedirect();

        $friendRequest = FriendRequest::firstOrFail();

        $this->post(route('friend-requests.accept', $friendRequest))
            ->assertForbidden();

        $this->actingAs($stranger)
            ->post(route('friend-requests.accept', $friendRequest))
            ->assertForbidden();

        $this->delete(route('friend-requests.destroy', $friendRequest))
            ->assertForbidden();

        $this->assertDatabaseCount('friendships', 0);
        $this->assertDatabaseCount('friend_requests', 1);
    }

    public function test_receiver_can_decline_and_sender_can_cancel_requests(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        $this->actingAs($alice)
            ->post(route('friends.store', $bob))
            ->assertRedirect();

        $friendRequest = FriendRequest::firstOrFail();

        $this->actingAs($bob)
            ->delete(route('friend-requests.destroy', $friendRequest))
            ->assertRedirect(route('friend-requests.index'));

        $this->assertDatabaseCount('friend_requests', 0);
        $this->assertDatabaseCount('friendships', 0);

        $this->actingAs($alice)
            ->post(route('friends.store', $bob))
            ->assertRedirect();

        $friendRequest = FriendRequest::firstOrFail();

        $this->delete(route('friend-requests.destroy', $friendRequest))
            ->assertRedirect(route('friend-requests.index'));

        $this->assertDatabaseCount('friend_requests', 0);
        $this->assertDatabaseCount('friendships', 0);
    }

    public function test_repeated_and_reverse_requests_do_not_create_friendship(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        $this->actingAs($alice)
            ->post(route('friends.store', $bob))
            ->assertRedirect();

        $this->post(route('friends.store', $bob))->assertRedirect();

        $this->actingAs($bob)
            ->post(route('friends.store', $alice))
            ->assertRedirect(route('friend-requests.index'));

        $this->assertDatabaseCount('friend_requests', 1);
        $this->assertDatabaseCount('friendships', 0);
    }

    public function test_user_cannot_send_friend_request_to_themselves(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('friends.store', $user))
            ->assertStatus(422);

        $this->assertDatabaseCount('friend_requests', 0);
        $this->assertDatabaseCount('friendships', 0);
    }

    public function test_feed_contains_friends_gods_only_after_acceptance(): void
    {
        $viewer = User::factory()->create();
        $friend = User::factory()->create();
        $stranger = User::factory()->create();

        $this->actingAs($viewer)
            ->post(route('friends.store', $friend))
            ->assertRedirect();

        $this->makeGod($friend, 'Старый бог');
        $this->makeGod($friend, 'Новый бог');
        $this->makeGod($stranger, 'Чужой бог');

        $this->get(route('feed.index'))
            ->assertOk()
            ->assertDontSee('Новый бог');

        $friendRequest = FriendRequest::firstOrFail();

        $this->actingAs($friend)
            ->post(route('friend-requests.accept', $friendRequest))
            ->assertRedirect();

        $this->actingAs($viewer)
            ->get(route('feed.index'))
            ->assertOk()
            ->assertSeeInOrder(['Новый бог', 'Старый бог'])
            ->assertDontSee('Чужой бог');
    }

    public function test_comments_from_confirmed_friends_are_highlighted(): void
    {
        $viewer = User::factory()->create();
        $friend = User::factory()->create();
        $stranger = User::factory()->create();
        $god = $this->makeGod($stranger, 'Гера');

        $this->actingAs($viewer)
            ->post(route('friends.store', $friend))
            ->assertRedirect();

        $this->actingAs($friend)
            ->post(route('comments.store', $god), ['body' => 'Комментарий друга'])
            ->assertRedirect();

        $this->actingAs($stranger)
            ->post(route('comments.store', $god), ['body' => 'Обычный комментарий'])
            ->assertRedirect();

        $this->actingAs($viewer)
            ->get(route('gods.show', $god))
            ->assertOk()
            ->assertDontSee('★ Друг');

        $friendRequest = FriendRequest::firstOrFail();

        $this->actingAs($friend)
            ->post(route('friend-requests.accept', $friendRequest))
            ->assertRedirect();

        $response = $this->actingAs($viewer)
            ->get(route('gods.show', $god))
            ->assertOk()
            ->assertSee('Комментарий друга')
            ->assertSee('Обычный комментарий')
            ->assertSee('★ Друг');

        $this->assertSame(1, substr_count($response->getContent(), '★ Друг'));
    }

    private function makeGod(User $user, string $name): God
    {
        return $user->gods()->create([
            'name' => $name,
            'badge' => 'Божество',
            'sphere' => 'Олимп',
            'description' => 'Описание божества.',
        ]);
    }
}
