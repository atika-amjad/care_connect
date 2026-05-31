<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mood_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->string('emoji', 8);
            $table->string('label');
            $table->unsignedTinyInteger('energy');
            $table->unsignedTinyInteger('anxiety');
            $table->json('tags')->nullable();
            $table->text('note')->nullable();
            $table->date('logged_date');
            $table->string('logged_time', 16);
            $table->timestamps();

            $table->index(['user_id', 'logged_date']);
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->unsignedInteger('word_count')->default(0);
            $table->timestamps();
        });

        Schema::create('therapists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialization');
            $table->unsignedTinyInteger('years_experience');
            $table->decimal('rating', 2, 1);
            $table->string('availability');
            $table->json('tags');
            $table->string('icon', 8)->default('👩‍⚕️');
            $table->string('bg_color', 32)->default('green');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('support_groups', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_en');
            $table->string('name_ur');
            $table->text('description_en');
            $table->text('description_ur');
            $table->unsignedInteger('member_count')->default(0);
            $table->string('icon', 8);
            $table->string('bg_color', 32);
            $table->timestamps();
        });

        Schema::create('group_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('support_group_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'support_group_id']);
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('therapist_id')->constrained()->cascadeOnDelete();
            $table->string('contact_name');
            $table->string('email');
            $table->date('appointment_date');
            $table->string('appointment_time', 16);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('group_memberships');
        Schema::dropIfExists('support_groups');
        Schema::dropIfExists('therapists');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('mood_entries');
    }
};
