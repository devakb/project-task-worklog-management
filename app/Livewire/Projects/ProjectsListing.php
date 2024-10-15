<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ProjectsListing extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function render()
    {

        $projectStatusCounts = Project::selectRaw("status, count(status) as count")->groupby('status')->pluck('count', 'status')->toArray();

        return view('livewire.projects.projects-listing', compact('projectStatusCounts'));
    }

    public function common_form(){
        return [
            Section::make("Projects Details")
                ->columns(2)->schema(
                    [
                        TextInput::make('name')->required(),
                        TextInput::make('code')->required()->disabledOn('edit'),

                        DatePicker::make('start_date')->required(),
                        DatePicker::make('end_date')->required(),

                        Select::make('status')->options(Project::SELECT_STATUS_TYPES)->required()->columnSpan(2),
                    ]
                 ),

            Section::make("Client Details")
                ->columns(2)->schema(
                    [
                        TextInput::make('client_name')->required()->columnSpan(2)->prefixIcon("heroicon-m-user"),
                        TextInput::make('client_email')->email()->prefixIcon("heroicon-m-envelope"),
                        TextInput::make('client_phone')->prefixIcon("heroicon-m-phone"),
                    ]
                )
        ];
    }

    public function table(Table $table): Table{

        return $table->query(Project::withCount("members"))
        ->heading("Projects")
        ->columns([
            TextColumn::make('name')->label('Name')->searchable(),
            TextColumn::make('code')->label('Code')->searchable(),
            TextColumn::make('start_date')->label('Start Date')->dateTime("F d, Y"),
            TextColumn::make('end_date')->label('End Date')->dateTime("F d, Y"),
            TextColumn::make('client_name')->label('Client Name')->searchable(),
            TextColumn::make('client_email')->label('Client Contact')->description(fn($record) => $record->client_phone)->searchable(['client_email','client_phone']),

            SelectColumn::make('status')->label('Project Status')
                ->options(Project::SELECT_STATUS_TYPES)
                ->rules(['required'])
                ->selectablePlaceholder(false)
                ->afterStateUpdated(function ($record, $state) {
                    Notification::make()
                        ->success()
                        ->title('Project Status Updated')
                        ->body('The project status has been updated successfully.')
                        ->send();
                    }),
             TextColumn::make('members_count')->label('Project Members')->alignCenter(),

        ])
        ->filters([
            SelectFilter::make('status')->label('Project Status')->options(Project::SELECT_STATUS_TYPES),
        ])
        ->defaultSort('id', 'desc')
        ->headerActions([

            CreateAction::make()->label("Create New Project")->form($this->common_form())->after(function (CreateAction $action, Project $record) {
                $record->members()->create([
                    "user_id" => optional(auth())->id(),
                    "role" => "admin",
                    'is_allocated' => true,
                    'engagement' => 'sporadic',
                ]);
            }),

        ])
        ->actions([
            EditAction::make()->button()->color('info')->outlined()->form($this->common_form()),

            Action::make('details')->label("View")->button()->outlined()->color('info')->icon('heroicon-m-eye')->url(fn($record) => route('projects.details', ["project" => $record->id])),

            Action::make('taskboard')->label("Tasks")->button()->outlined()->color('info')->icon('heroicon-m-rectangle-stack')->url(fn($record) => route('tasks.listing', ["project" => $record->id])),
        ]);


    }


}
