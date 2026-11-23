<?php

namespace Tests\Feature;

use App\Models\God;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeding_twice_keeps_existing_records_and_roles(): void
    {
        $existingAdmin = User::factory()->create([
            'username' => 'moderator',
            'is_admin' => true,
        ]);

        $existingGod = $existingAdmin->gods()->create([
            'name' => 'Зевс',
            'badge' => 'Моя категория',
            'sphere' => 'Моя сфера',
            'description' => 'Моё описание',
        ]);

        DB::table('gods')->where('id', $existingGod->id)->update([
            'deleted_at' => now(),
        ]);

        $this->seed();
        $this->seed();

        $this->assertTrue($existingAdmin->fresh()->is_admin);
        $this->assertDatabaseHas('gods', [
            'id' => $existingGod->id,
            'user_id' => $existingAdmin->id,
            'badge' => 'Моя категория',
        ]);
        $this->assertSoftDeleted('gods', ['id' => $existingGod->id]);
        $this->assertSame(1, God::withTrashed()->where('name', 'Зевс')->count());
        $this->assertSame(5, God::withTrashed()->count());
        $this->assertSame(4, User::count());
    }

    public function test_reseeding_does_not_reset_existing_demo_passwords(): void
    {
        $this->seed();

        $admin = User::where('username', 'admin')->firstOrFail();
        $admin->password = 'changed-password';
        $admin->save();

        $this->seed();

        $this->assertTrue(Hash::check('changed-password', $admin->fresh()->password));
        $this->assertTrue($admin->fresh()->is_admin);
        $this->assertSame(3, User::count());
    }
}
