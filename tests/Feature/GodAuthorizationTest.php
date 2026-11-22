<?php

namespace Tests\Feature;

use App\Models\God;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GodAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_edit_update_and_soft_delete_own_god(): void
    {
        $owner = User::factory()->create();
        $god = $this->createGod($owner);

        $this->actingAs($owner)
            ->get(route('gods.edit', $god))
            ->assertOk();

        $this->put(route('gods.update', $god), [
            'name' => 'Athena Updated',
            'badge' => 'Богиня',
            'sphere' => 'Мудрость',
            'description' => 'Богиня мудрости и стратегии.',
        ])->assertRedirect(route('gods.show', $god));

        $this->assertDatabaseHas('gods', [
            'id' => $god->id,
            'name' => 'Athena Updated',
        ]);

        $this->delete(route('gods.destroy', $god))
            ->assertRedirect(route('gods.index'));

        $this->assertSoftDeleted('gods', ['id' => $god->id]);
    }

    public function test_user_cannot_edit_update_or_delete_another_users_god(): void
    {
        $owner = User::factory()->create();
        $anotherUser = User::factory()->create();
        $god = $this->createGod($owner);

        $this->actingAs($anotherUser)
            ->get(route('gods.edit', $god))
            ->assertForbidden();

        $this->put(route('gods.update', $god), [
            'name' => 'Changed name',
            'badge' => 'Бог',
            'sphere' => 'Море',
            'description' => 'Описание.',
        ])->assertForbidden();

        $this->delete(route('gods.destroy', $god))
            ->assertForbidden();

        $this->assertDatabaseHas('gods', [
            'id' => $god->id,
            'name' => $god->name,
            'deleted_at' => null,
        ]);
    }

    public function test_only_admin_can_restore_and_force_delete_gods(): void
    {
        $owner = User::factory()->create();
        $god = $this->createGod($owner);

        $this->actingAs($owner)
            ->delete(route('gods.destroy', $god))
            ->assertRedirect(route('gods.index'));

        $this->assertSoftDeleted('gods', ['id' => $god->id]);

        $regularUser = User::factory()->create();

        $this->actingAs($regularUser)
            ->post(route('gods.restore', $god))
            ->assertForbidden();

        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $this->actingAs($admin)
            ->post(route('gods.restore', $god))
            ->assertRedirect(route('gods.trashed'));

        $this->assertDatabaseHas('gods', [
            'id' => $god->id,
            'deleted_at' => null,
        ]);

        $this->delete(route('gods.destroy', $god))
            ->assertRedirect(route('gods.index'));

        $this->assertSoftDeleted('gods', ['id' => $god->id]);

        $this->delete(route('gods.forceDelete', $god))
            ->assertRedirect(route('gods.trashed'));

        $this->assertDatabaseMissing('gods', ['id' => $god->id]);
    }

    public function test_regular_user_cannot_open_trashed_gods_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('gods.trashed'))
            ->assertForbidden();
    }

    private function createGod(User $owner): God
    {
        return $owner->gods()->create([
            'name' => 'Athena',
            'badge' => 'Богиня',
            'sphere' => 'Мудрость',
            'description' => 'Богиня мудрости и стратегии.',
        ]);
    }
}
