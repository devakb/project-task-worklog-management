<?php

namespace App\Livewire\WorkLogs;

use DateTime;
use App\Models\Task;
use App\Models\WorkLog;
use Livewire\Component;
use Filament\Tables\Table;
use App\Rules\HourLogFormat;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Hidden;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TaskWorkLogs extends Component implements HasTable, HasForms
{

    use InteractsWithForms, InteractsWithTable;

    public Task $task;

    public function mount(Task $task){
        $this->task = $task;
    }

    public function render()
    {
        return view('livewire.work-logs.task-work-logs');
    }


    public function table(Table $table): Table{
        return $table->query(WorkLog::with('user')->where('task_id', $this->task->id))
                    ->heading("Work Logs")
                    ->paginated(false)
                    ->searchable(false)
                    ->defaultSort('id', 'desc')
                    ->columns([
                        Split::make([
                            ImageColumn::make('user.avatar')
                                ->circular()
                                ->grow(false),
                            Stack::make([
                                TextColumn::make('user.name')
                                    ->weight(FontWeight::Bold)
                                    ->searchable(),
                                TextColumn::make('user.email'),
                                TextColumn::make('logged_hours_string')
                                ->badge()
                                ->color('info')
                                ->prefix("Time Logged: ")
                                ->label("Time Spent")
                            ]),
                        ]),

                        TextColumn::make('description')
                            ->html()
                            ->formatStateUsing(function($record) {
                                return "<div class='pt-4 px-12'><div class='prose  max-w-none !border-none py-1.5 text-base text-gray-950 dark:prose-invert focus-visible:outline-none dark:text-white sm:text-sm sm:leading-6'>
                                    {$record->description}
                                </div></div>";
                            })
                            ->wrap()
                    ])
                    ->headerActions([
                        CreateAction::make()->label("Give Log Work")->form([

                            Hidden::make('user_id')->dehydrateStateUsing(fn($state) => optional(auth())->id() ?? 1),
                            Hidden::make('task_id')->dehydrateStateUsing(fn($state) => $this->task->id),
                            Hidden::make('project_id')->dehydrateStateUsing(fn($state) => $this->task->project_id),

                            TextInput::make('logged_hours_string')->label("Time Spent")
                            ->validationAttribute("Time Spent")
                            ->required()->rules([ new HourLogFormat]),

                            Placeholder::make('Format')
                                ->content(new HtmlString('<h5 class="font-medium">Use the format: 2w 4d 6h 45m</h5><ul> <li>. w = weeks</li> <li>. d = days</li> <li>. h = hours</li> <li>. m = minutes</li> </ul>')),

                            DateTimePicker::make('date')->default(now())->time(false)->required(),

                            RichEditor::make('description'),
                        ])
                    ])
                    ->actions([
                        EditAction::make()->button()->color('info')->form([
                            TextInput::make('logged_hours_string')->label("Time Spent")
                            ->validationAttribute("Time Spent")
                            ->required()->rules([ new HourLogFormat]),

                            Placeholder::make('Format')
                                ->content(new HtmlString('<h5 class="font-medium">Use the format: 2w 4d 6h 45m</h5><ul> <li>. w = weeks</li> <li>. d = days</li> <li>. h = hours</li> <li>. m = minutes</li> </ul>')),

                          //  Date::make('date')->required()->rules(['date_format:Y-m-d']),
                            DateTimePicker::make('date')->default(now())->time(false)->required(),

                            RichEditor::make('description'),
                        ])->hidden(fn($record) => $record->user_id != optional(auth())->id()),

                        DeleteAction::make()->button()->hidden(fn($record) => $record->user_id != optional(auth())->id()),
                    ]);
    }
}

// Use the format: 2w 4d 6h 45m

// . w = weeks

// . d = days
// . h = hours

// . m = minutes
