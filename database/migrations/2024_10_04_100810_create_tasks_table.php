<?php

use App\Models\Task;
use App\Models\User;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignIdFor(User::class, 'created_by_user_id')->constrained('users');
            $table->foreignIdFor(Task::class, 'parent_task_id')->nullable()->constrained('tasks');

            $table->bigInteger('task_key');
            $table->string('task_code')->index();
            $table->string('title');
            $table->text('description');
            $table->enum('task_type', array_keys(Task::SELECT_TAST_TYPES));
            $table->enum('task_status', array_keys(Task::SELECT_TAST_STATUSES));
            $table->enum('task_priority', array_keys(Task::SELECT_PRIORITIES));

            $table->date('due_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
