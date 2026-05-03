<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->renameColumn('rodinComment', 'rodin_comment');
            $table->renameColumn('activityComment', 'activity_comment');
            $table->renameColumn('feedbackComment', 'feedback_comment');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->renameColumn('rodin_comment', 'rodinComment');
            $table->renameColumn('activity_comment', 'activityComment');
            $table->renameColumn('feedback_comment', 'feedbackComment');
        });
    }
};
