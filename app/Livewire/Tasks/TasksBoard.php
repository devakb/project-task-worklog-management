<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\Project;
use Livewire\Component;

class TasksBoard extends Component
{
    public Project $project;

    public function mount(){

        $project = request()->get('project', 0);

        $this->project = Project::findOrFail($project);

    }

    public function render()
    {

        $boardItems = [
            "to-do"             => Task::whereProjectId($this->project->id)->where("task_status", "to-do")->orderby('updated_at')->get(),
            "in-progress"       => Task::whereProjectId($this->project->id)->where("task_status", "in-progress")->orderby('updated_at')->get(),
            "in-qa-testing"     => Task::whereProjectId($this->project->id)->where("task_status", "in-qa-testing")->orderby('updated_at')->get(),
            "reopened"          => Task::whereProjectId($this->project->id)->where("task_status", "reopened")->orderby('updated_at')->get(),
            "done"              => Task::whereProjectId($this->project->id)->where("task_status", "done")->orderby('updated_at')->get(),
        ];

        return view('livewire.tasks.tasks-board', compact('boardItems'));
    }

    public function changeTaskStatus(String $zone, int $id){
        Task::where('id', $id)->update(['task_status' => $zone]);
    }
}
