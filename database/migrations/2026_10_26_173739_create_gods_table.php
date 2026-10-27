
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('badge');
            $table->string('sphere');
            $table->text('description');
            $table->text('symbols')->nullable();
            $table->text('parents')->nullable();
            $table->text('siblings')->nullable();
            $table->text('spouse')->nullable();
            $table->text('children')->nullable();
            $table->text('temple')->nullable();
            $table->text('myth')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gods');
    }
};
