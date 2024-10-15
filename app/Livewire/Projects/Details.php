<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class Details extends Component
{
    public Project $project;

    public function mount(Project $project){
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.projects.details');
    }

    public function detailsPanel(){

        return  Infolist::make()->record($this->project)->schema([
            Section::make("Projects Details")
                ->columns(2)
                ->schema([
                    TextEntry::make("name"),
                    TextEntry::make("code"),
                    TextEntry::make("start_date")->date(),
                    TextEntry::make("end_date")->date(),
                    TextEntry::make("status")->formatStateUsing(fn($record) => str()->upper($record->status))->weight(FontWeight::Bold),
                ]),

            Section::make("Client Details")
                ->columns(3)
                ->schema([
                    TextEntry::make("client_name"),
                    TextEntry::make("client_email"),
                    TextEntry::make("client_phone"),
                ])
        ]);



    }
}
