<?php

namespace App\Livewire\Worklogs;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\User;
use App\Models\Project;
use App\Models\WorkLog;
use Livewire\Component;
use Filament\Forms\Form;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use App\Livewire\Projects\ProjectMembers;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class WorklogListing extends Component implements HasForms
{
    use InteractsWithForms;

    public $page_filters = [];

    public Project $project;

    public function mount(Project $project){
        $this->project = $project;

        $this->form->fill([
            "from_date" => now()->startOfWeek()->format("Y-m-d"),
            "to_date" => now()->endOfWeek()->format("Y-m-d"),
        ]);
    }

    public function form(Form $form) : Form{
        return $form
        ->statePath("page_filters")
        ->schema([

            Section::make()->columns(4)->schema([


                DateTimePicker::make('from_date')
                    ->time(false)
                    ->native(false)
                    ->weekStartsOnSunday()
                    ->format("Y-m-d")
                    ->reactive(),

                DateTimePicker::make('to_date')
                    ->time(false)
                    ->native(false)
                    ->weekStartsOnSunday()
                    ->format("Y-m-d")
                    ->maxDate(now())
                    ->reactive(),


            ])

        ]);
    }

    public function render()
    {
        $query = WorkLog::selectRaw('user_id, date, sum(logged_minutes_int) as logged_minutes')
                ->where('project_id', $this->project->id)
                ->groupBy('user_id', 'date')
                ->whereBetween('date', [
                                    Carbon::parse($this->page_filters["from_date"])->format("Y-m-d"),
                                    Carbon::parse($this->page_filters["to_date"])->format("Y-m-d")
                                ]);

        $date_wise_columns = $this->getDates();

        $selectable = [];
        foreach($date_wise_columns as $date){
            $selectable[] = "SUM(case when date = DATE('$date') then logged_minutes else 0 end) as '$date'";
        }
        $selectable = implode(",", $selectable);

        $records = DB::table($query)->selectRaw("user_id, name, email, $selectable, SUM(logged_minutes) as total_logged")
            ->groupBy('user_id')
            ->join('users', 'users.id', '=', 'user_id')->get();

        $members = array_merge(ProjectMember::where('project_id', $this->project->id)->pluck('user_id')->toArray(), $records->pluck('user_id')->toArray());

        $users = User::whereIn("id", $members)->get();

        $records = collect($records->toArray())->groupby('user_id')->toArray();

        return view('livewire.worklogs.worklog-listing', compact('records', 'date_wise_columns', 'users'));
    }

    protected function getDates(){


        $begin = new DateTime($this->page_filters["from_date"]);
        $end = new DateTime($this->page_filters["to_date"]);

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);
        $dates = [];
        foreach($daterange as $date){
            $dates[] = $date->format("Y-m-d");
        }
        $dates[] = Carbon::parse($this->page_filters["to_date"])->format("Y-m-d");


        return $dates;
    }

}
