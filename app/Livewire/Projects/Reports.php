<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class Reports extends Component
{
    public Project $project;

    public function mount(Project $project){
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.projects.reports');
    }
}
