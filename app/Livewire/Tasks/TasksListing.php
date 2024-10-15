<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Livewire\Component;
use Filament\Tables\Table;
use Livewire\Attributes\Url;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use App\Filament\Exports\TaskExporter;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TasksListing extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;



    public ?int $project_id = null;

    public function render()
    {
        return view('livewire.tasks.tasks-listing');
    }

    public function mount(){
        if(!request()->filled('project')){
            abort(404);
        }

        $this->project_id = request()->project;
    }

    public function defaultForm() {
        return [
            Select::make('task_type')
                    ->options(collect(Task::SELECT_TAST_TYPES)->map(function($item, $key){
                        $icon = Task::SELECT_TAST_TYPE_ICONS[$key];
                        return "<div class=''flex items-center><span class='inline-block mr-2'>$icon</span> $item</div>";
                    })->toArray())
                    ->searchable()
                    ->allowHtml()
                    ->default('task')
                    ->selectablePlaceholder(false)
                    ->required(),


            Select::make('task_status')
                    ->options(Task::SELECT_TAST_STATUSES)
                    ->default('to-do')
                    ->searchable()
                    ->selectablePlaceholder(false)
                    ->required(),


            TextInput::make('title')->label("Summary")->required(),

            RichEditor::make('description')->required(),

            Select::make('task_priority')
                    ->options(collect(Task::SELECT_PRIORITIES)->map(function($item, $key){
                        $icon = "<img class='inline-block mr-2' src=\"" . asset(Task::PRIORITIES_ICON_FOLDER . "/" . $key . Task::PRIORITIES_ICON_EXTN) . "\" />";
                        return "<div class=''flex items-center>$icon $item</div>";
                    })->toArray())
                    ->searchable()
                    ->selectablePlaceholder(false)
                    ->allowHtml(true)->required(),

            DateTimePicker::make('due_date')->time(false),

            Select::make('user_id')
                    ->label("Assignee")
                    ->searchable(['name', 'email'])
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'email',
                        ignoreRecord: false,
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->email})")
                    ->preload(),
        ];
    }

    public function assignToMe($id){
        Task::where('id', $id)->update(['user_id' => optional(auth())->id() ?? 1]);

        Notification::make()
                    ->success()
                    ->title('Task assigned to you successfully.')
                    ->send();
    }

    public function table(Table $table) : Table{
        return $table->query(Task::with('project', 'user')->when($this->project_id, fn($query) => $query->where('project_id', $this->project_id)))
                    ->searchable()
                    ->defaultSort('id','desc')
                    ->defaultPaginationPageOption(50)
                    ->heading("Tasks")
                    ->filters([
                        SelectFilter::make('task_type')
                        ->multiple()
                        ->options(Task::SELECT_TAST_TYPES),

                        SelectFilter::make('task_status')
                        ->multiple()
                        ->options(Task::SELECT_TAST_STATUSES),

                        SelectFilter::make('user_id')
                        ->label("Assignee")
                        ->multiple()
                        ->searchable()
                        ->options(User::selectRaw("CONCAT(name,' (',email,')') as name, id")->whereIn('id', [0, ...Task::pluck('user_id')->toArray()])->pluck('name', 'id')),

                        // SelectFilter::make('project_id')
                        // ->label("Project")
                        // ->multiple()
                        // ->searchable()
                        // ->options(Project::whereIn('id', [0, ...Task::pluck('project_id')->toArray()])->pluck('name', 'id'))
                    ])
                    ->headerActions([


                        CreateAction::make()->form([
                            Hidden::make('created_by_user_id')->dehydrateStateUsing(fn($state) => optional(auth())->id()),
                            Hidden::make('project_id')->dehydrateStateUsing(fn($state) =>  $this->project_id),
                            // Select::make('project_id')
                            //         ->searchable(['name', 'code'])
                            //         ->relationship(
                            //             name: 'project',
                            //             titleAttribute: 'code',
                            //             ignoreRecord: false,
                            //         )
                            //         ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->code})")
                            //         ->preload()
                            //         ->required(),

                            ...$this->defaultForm()
                        ])
                    ])
                    ->actions([
                        EditAction::make()->button()->outlined()->color('info')->form($this->defaultForm()),

                        Action::make('view')->button()->outlined()->color('info')->icon('heroicon-m-eye')->label("View")->url(fn($record) => route('tasks.details', ["task" => $record, 'project' => $record->project->id])),
                    ])
                    ->columns([

                        TextColumn::make('task_priority')->label("Priority")->formatStateUsing(function($record){
                            return "<img class='inline-block mr-2' src=\"" . asset(Task::PRIORITIES_ICON_FOLDER . "/" . $record->task_priority . Task::PRIORITIES_ICON_EXTN) . "\" />";
                        })->html()
                        ->tooltip(fn($record) => Task::SELECT_PRIORITIES[$record->task_priority])
                        ->alignCenter(),

                        TextColumn::make('task_type')->label("Type")->formatStateUsing(function($record){
                            return Task::SELECT_TAST_TYPE_ICONS[$record->task_type];
                        })->html()
                        ->tooltip(fn($record) => Task::SELECT_TAST_TYPES[$record->task_type])
                        ->alignCenter(),

                        TextColumn::make('task_code')->label("Key")->searchable(),
                        TextColumn::make('title')->label("Summary")->searchable()->width('30%')->wrap(),
                        TextColumn::make('project.name')->label("Project")->badge()->color('indigo'),

                        TextColumn::make('task_status')
                            ->formatStateUsing(fn($record) => str()->upper(Task::SELECT_TAST_STATUSES[$record->task_status]))
                            ->badge()
                            ->color(fn($record) => Task::STATUS_TAST_STATUS_COLORS[$record->task_status]),
                        // SelectColumn::make('task_status')
                        //     ->options(Task::SELECT_TAST_STATUSES)
                        //     ->selectablePlaceholder(false),

                        // TextColumn::make('user.name')->description(fn($record) => $record->user->email ?? "Unassigned")->placeholder("Unassigned"),

                        ViewColumn::make('user.name')->view("filament.tables.columns.assignee_column_for_tasks"),
                        TextColumn::make('due_date')->label(new HtmlString("<i class='la la-calendar'></i> Due Date"))->date()->placeholder("NA")->badge()->color('gray'),
                    ]);
    }

}
