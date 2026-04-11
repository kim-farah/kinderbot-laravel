<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->string('class');
            $table->string('teacher');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('duration')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_activity_log');
    }
};
