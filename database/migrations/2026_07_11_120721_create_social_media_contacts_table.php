<?php

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
                Schema::create('social_media_contacts', function (Blueprint $table) {

                        $table->id();
                        $table->foreignId('student_id')
                                ->nullable()
                                ->constrained('students')
                                ->nullOnDelete();
                        $table->string('platform', 30);
                        $table->string('platform_user_id');
                        $table->string('social_name')->nullable();
                        $table->string('phone_number')->nullable();
                        $table->string('phone_number_id')->nullable();
                        $table->string('page_id')->nullable();
                        $table->string('email')->nullable();
                        $table->string('profile_picture')->nullable();
                        $table->string('last_platform')->nullable();
                        $table->timestamp('last_seen_at')->nullable();
                        $table->boolean('is_online')->default(false);
                        $table->boolean('is_typing')->default(false);
                        $table->timestamp('last_typing_at')->nullable();
                        $table->boolean('is_blocked')->default(false);
                        $table->boolean('is_archived')->default(false);
                        $table->json('meta')->nullable();
                        $table->timestamps();
                        $table->unique([
                                'platform',
                                'platform_user_id'
                        ]);
                });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
                Schema::dropIfExists('social_media_contacts');
        }
};
