<?php

use App\Enums\OrganizerRoleEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = [
            OrganizerRoleEnum::Admin->value,
            OrganizerRoleEnum::Staff->value,
        ];

        Schema::create('organizers', function (Blueprint $table) use ($roles) {
            $table->id();
            $table->uuid()->index();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', $roles)->default(OrganizerRoleEnum::Staff->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizers');
    }
};
