<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Filament\Support\RawJs;
use App\Models\ProjectMember;
use Filament\Widgets\ChartWidget;

class MembersRoleBasedChart extends ChartWidget
{
    protected static ?string $heading = 'Role Wise Project Members';

    public Project $project;

    protected static ?string $maxHeight = "340px";

    public function colors(int $lenght = 2){
        return collect([
            'red' => [255, 99, 132],      // Highlighted: Red
            'blue' => [54, 162, 235],     // Highlighted: Blue
            'green' => [75, 192, 192],    // Highlighted: Green
            'orange' => [255, 159, 64],   // Highlighted: Orange
            'purple' => [153, 102, 255],  // Highlighted: Purple
            'yellow' => [255, 206, 86],   // Highlighted: Yellow
            'grey' => [201, 203, 207],    // Highlighted: Grey
            'lightBlue' => [0, 204, 255],  // Highlighted: Light Blue
            'pink' => [255, 99, 255],      // Highlighted: Pink
            'teal' => [0, 128, 128],       // Highlighted: Teal
            'lime' => [0, 255, 0],         // Highlighted: Lime
            'cyan' => [0, 255, 255],       // Highlighted: Cyan
            'magenta' => [255, 0, 255],    // Highlighted: Magenta
            'brown' => [165, 42, 42],      // Highlighted: Brown
            'gold' => [255, 215, 0],       // Highlighted: Gold
            'navy' => [0, 0, 128],         // Highlighted: Navy
            'indigo' => [75, 0, 130],      // Highlighted: Indigo
            'salmon' => [250, 128, 114],   // Highlighted: Salmon
            'olive' => [128, 128, 0],      // Highlighted: Olive
            'coral' => [255, 127, 80],     // Highlighted: Coral
        ])->map(function($rgb) {
            return 'rgb(' . implode(', ', $rgb) . ')';
        })->values()->take($lenght)->toArray();
    }

    protected function getData(): array
    {

        $records = ProjectMember::where('project_id', $this->project->id)->selectRaw('count(user_id) as count, role')->groupby('role')->pluck('count', 'role');

        $labels = $records->keys()->map(fn($item) => ProjectMember::SELECT_ROLE[$item])->toArray();

        $data = $records->values()->toArray();

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $this->colors(count($data)),
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
