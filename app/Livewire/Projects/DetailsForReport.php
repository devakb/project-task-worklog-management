<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Filament\Forms\Get;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class DetailsForReport extends Component implements HasForms
{
    use InteractsWithForms;

    public Project $project;
    public $page_filters = [];

    public function mount(Project $project){
        $this->project = $project;

        $this->form->fill([
            "date_filter_type" => "all",
            "start_date" => null,
            "end_date" => null,
        ]);
    }

    public function render()
    {
        return view('livewire.projects.details-for-report');
    }

    public function form(Form $form) : Form{
        return $form
        ->statePath("page_filters")
        ->schema([

            Section::make()->columns(4)->schema([
                Select::make('date_filter_type')->selectablePlaceholder(false)->options([
                    'all' => "All Times",
                    "td" => "Today",
                    "yt" => "Yesterday",
                    "7ds" => "Last 7 Days",
                    "mth" => "This Month",
                    "lmth" => "Last Month",
                    "custom" => "Custom"
                ])->reactive(),

                DateTimePicker::make('start_date')->live()->time(false)->visible(fn (Get $get): bool => $get('date_filter_type') == 'custom'),
                DateTimePicker::make('end_date')->live()->time(false)->visible(fn (Get $get): bool => $get('date_filter_type') == 'custom'),
            ])

        ]);
    }

    public function updated($property){

        if(str($property)->contains('page_filters')){
            $this->dispatch('$refresh');
        }

    }

}
