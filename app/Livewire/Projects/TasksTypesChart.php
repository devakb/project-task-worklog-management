<?php

namespace App\Livewire\Projects;

use App\Models\Task;
use App\Models\Project;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class TasksTypesChart extends ChartWidget
{
    protected static ?string $heading = 'Raised Task Types';

    public Project $project;

    protected static ?string $maxHeight = "340px";



    protected function getData(): array
    {
        $records = Task::where('project_id', $this->project->id)->selectRaw('count(id) as count, task_type')->orderby('task_type')->groupby('task_type')->pluck('count', 'task_type');

        $labels = $records->keys()->map(fn($item) => Task::SELECT_TAST_TYPES[$item])->toArray();

        $data = $records->values()->toArray();

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => collect(Task::SELECT_TASK_COLORS)->sortKeys()->values()->toArray(),
                    'hoverOffset' => 7,
                ],
            ],

            "labels" => $labels
        ];
    }

    protected function getOptions(): array | RawJs | null
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    y: {
                        display: false,
                    },
                    x: {
                        display: false,
                    },
                },

            }
        JS);
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
