<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'created_by_user_id',
        'parent_task_id',
        'task_key',
        'task_code',
        'title',
        'description',
        'task_type',
        'task_status',
        'task_priority',
        'due_date',
    ];

    const SELECT_TAST_STATUSES = [
        'declined' => 'Declined',
        'on-hold' => 'On Hold',
        'to-do' => 'To Do',
        'in-progress' => 'In Progress',
        'in-qa-testing' => 'In QA Testing',
        'reopened' => 'Reopened',
        'done' => 'Done',
    ];

    const STATUS_TAST_STATUS_COLORS = [
        'declined' => 'danger',
        'on-hold' => 'yellow',
        'to-do' => 'gray',
        'in-progress' => 'sky',
        'in-qa-testing' => 'indigo',
        'reopened' => 'info',
        'done' => 'success',
    ];

    const SELECT_TAST_TYPES = [
        'bug' => 'Bug',
        'task' => 'Task',
        'new-feature' => 'New Feature',
        'improvement' => 'Improvement',
    ];

    const SELECT_TAST_TYPE_ICONS = [
        'bug' => '<i class="text-rose-700 text-lg fas fa-bug"></i>',
        'task' => '<i class="text-blue-700 text-lg fa-solid fa-square-check"></i>',
        'new-feature' => '<i class="text-violet-700 text-lg fa-solid fa-square-plus"></i>',
        'improvement' => '<i class="text-green-600 text-lg fa-solid fa-square-caret-up"></i>',
    ];

    const SELECT_TASK_COLORS = [
        'bug' => '#be123c',
        'new-feature' => '#6d28d9',
        'improvement' => '#16a34a',
        'task' => '#1d4ed8',
    ];


    const SELECT_PRIORITIES = [
        'minor' => "Minor",
        'lowest' => "Lowest",
        'low' => "Low",
        'medium' => "Medium",
        'high' => "High",
        'highest' => "Highest",
        'major' => "Major",
        "critical" => "Critical",
        "blocker" => "Blocker",
    ];

    const PRIORITIES_ICON_FOLDER = "icons/prioirty-icon-images";
    const PRIORITIES_ICON_EXTN = ".png";

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
        ];
    }



    public function project()
    {
        return $this->belongsTo(Project::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }


    public function parenttask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }


    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            // Generate task_code based on project_id
            $maxCode = static::where('project_id', $task->project_id)->max('task_key');
            $pCode = Project::find($task->project_id)->code;
            $task->task_key = $maxCode ? $maxCode + 1 : 1;

            $task->task_code = $pCode . "-" . $task->task_key;
        });
    }

    public function getUserAvatar($user){

        if($user instanceof User){
            return $user->getAvatar();
        }else{
            return (new User)->getAvatar();
        }

    }

}
