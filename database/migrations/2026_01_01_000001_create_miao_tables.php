<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', fn(Blueprint $t) => $t->string('username', 50)->nullable()->unique());
        Schema::create('tb_tasks', function (Blueprint $t) {
            $t->id('task_id');
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->string('task_title', 150);
            $t->text('task_description')->nullable();
            $t->dateTime('task_deadline');
            $t->enum('task_priority', ['Low', 'Medium', 'High'])->default('Medium');
            $t->enum('task_status', ['Not Started', 'In Progress', 'Completed'])->default('Not Started');
            $t->string('task_category', 50)->default('Academic');
            $t->timestamp('task_created_at')->nullable();
            $t->timestamp('task_updated_at')->nullable();
        });
        Schema::create('tb_subtasks', function (Blueprint $t) {
            $t->id('subtask_id');
            $t->foreignId('task_id')->constrained('tb_tasks', 'task_id')->cascadeOnDelete();
            $t->string('subtask_title', 150);
            $t->boolean('subtask_is_completed')->default(false);
            $t->unsignedSmallInteger('subtask_position')->default(0);
            $t->timestamp('subtask_created_at')->nullable();
            $t->timestamp('subtask_updated_at')->nullable();
        });
        Schema::create('tb_daily_focus', function (Blueprint $t) {
            $t->id('focus_id');
            $t->foreignId('task_id')->constrained('tb_tasks', 'task_id')->cascadeOnDelete();
            $t->date('focus_date');
            $t->timestamp('focus_created_at')->nullable();
            $t->timestamp('focus_updated_at')->nullable();
            $t->unique(['task_id', 'focus_date']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('tb_daily_focus');
        Schema::dropIfExists('tb_subtasks');
        Schema::dropIfExists('tb_tasks');
        Schema::table('users', fn(Blueprint $t) => $t->dropColumn('username'));
    }
};
