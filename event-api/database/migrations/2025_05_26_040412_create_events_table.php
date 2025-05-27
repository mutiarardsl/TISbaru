<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->integer('quota')->nullable();
            $table->datetime('registration_deadline')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('event_categories')->onDelete('set null');
            $table->enum('organizer', ['faculty', 'university']);
            $table->integer('organizer_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};