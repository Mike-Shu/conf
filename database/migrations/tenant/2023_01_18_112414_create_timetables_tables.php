<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('timetables', static function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->jsonb('data')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('timetable_slots', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('timetable_id')
                ->constrained('timetables')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_datetime');
            $table->timestamp('finish_datetime');
            $table->jsonb('data')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_slots');
        Schema::dropIfExists('timetables');
    }
};
