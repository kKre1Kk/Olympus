
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique();
            $table->boolean('is_admin')->default(false);
        });

        DB::table('users')->whereNull('username')->orderBy('id')->get()->each(function ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'username' => 'user' . $user->id
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'is_admin']);
        });
    }
};
