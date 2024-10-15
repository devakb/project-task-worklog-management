<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\WorkLog;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;

class WorklogsChart extends ChartWidget
{
    protected static ?string $heading = 'User Wise Worklogs';

    protected $listeners = [
        '$refresh'
    ];

    protected static ?string $maxHeight = "340px";

    public Project $project;

    public $page_filters = [];

    public function getFilterDate(){

        switch($this->page_filters['date_filter_type']){
            case "td":
                return DB::raw("`date` = DATE('".now()->format('Y-m-d')."')");
            case "yt":
                return DB::raw("`date` = DATE('".now()->subDay()->format('Y-m-d')."')");
            case "7ds":
                return DB::raw("`date` BETWEEN DATE('".now()->format('Y-m-d')."') AND DATE('".now()->subDay(7)->format('Y-m-d')."')");
            case "mth":
                return DB::raw("`date` BETWEEN DATE('".now()->startOfMonth()->format('Y-m-d')."') AND DATE('".now()->endOfMonth(7)->format('Y-m-d')."')");
            case "lmth":
                return DB::raw("`date` BETWEEN DATE('".now()->subMonth()->startOfMonth()->format('Y-m-d')."') AND DATE('".now()->subMonth()->endOfMonth(7)->format('Y-m-d')."')");
            default:
                if(!empty($this->page_filters['start_date'])){
                    $startDate = Carbon::parse($this->page_filters['start_date']);
                    $endDate =  Carbon::parse($this->page_filters['end_date'] ?? now());

                    return DB::raw("`date` BETWEEN '".($startDate?? now()->format('Y-m-d')). "' AND '".($endDate?? now()->format('Y-m-d')). "'");
                }

                return null;

        }
    }


    protected function getData(): array
    {

        $worklogs = WorkLog::selectRaw("SUM(logged_minutes_int) / 60 as seconds, users.name as user_name")
                    ->join('users', 'work_logs.user_id', '=', 'users.id')
                    ->when($this->getFilterDate(), function($query){
                        $query->whereRaw($this->getFilterDate());
                    })
                    ->where('project_id', $this->project->id)->groupby('user_id')->pluck('seconds', 'user_name');


        return [
            'datasets' => [
                [
                    'label' => 'Worked Hours',
                    'data' => $worklogs->values()->toArray(),
                    'backgroundColor' => '#c7fcd7',
                    'borderColor' => '#69db8b',
                    // 'barThickness' => 20,
                ],
            ],
            'labels' => $worklogs->keys()->toArray(),
        ];
    }

    protected function getOptions(): array | RawJs | null
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            min: 0,
                            callback: (value) => value + ' Hours',
                        },
                    },
                },

                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                var label = 'Logged Hours';
                                var value = context.parsed.y.toFixed(2);
                                return label + ': ' + convertMinutesToFormat(value * 60);
                            },
                        },
                    },
                }
            }
        JS);
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
