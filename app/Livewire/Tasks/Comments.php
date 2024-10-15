<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\TaskComment;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Contracts\HasForms;
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
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class Comments extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    public Task $task;

    public function mount(Task $task){
        $this->task = $task;
    }

    public function render()
    {
        return view('livewire.tasks.comments');
    }

    public function table(Table $table): Table{
        return $table->query(TaskComment::with('user')->where('task_id', $this->task->id))
                    ->heading("Comments")
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
                            ]),
                        ]),

                        TextColumn::make('comment')
                            ->html()
                            ->formatStateUsing(function($record) {
                                return "<div class='pt-4 px-12'><div class='prose  max-w-none !border-none py-1.5 text-base text-gray-950 dark:prose-invert focus-visible:outline-none dark:text-white sm:text-sm sm:leading-6'>
                                    {$record->comment}
                                </div></div>";
                            })
                            ->wrap()
                    ])
                    ->headerActions([
                        CreateAction::make()->label("Add Comment")->form([

                            Hidden::make('user_id')->dehydrateStateUsing(fn($state) => optional(auth())->id() ?? 1),
                            Hidden::make('task_id')->dehydrateStateUsing(fn($state) => $this->task->id),

                            RichEditor::make('comment')->required(),
                        ])
                    ])
                    ->actions([
                        EditAction::make()->button()->color('info')->form([

                            RichEditor::make('comment')->required(),
                        ])->hidden(fn($record) => $record->user_id != optional(auth())->id()),

                        DeleteAction::make()->button()->hidden(fn($record) => $record->user_id != optional(auth())->id()),
                    ]);
    }
}
