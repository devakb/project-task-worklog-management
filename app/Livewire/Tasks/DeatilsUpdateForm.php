<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class DeatilsUpdateForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Task $task;

    public $data = [];

    public function mount(Task $task){
        $this->task = $task;

        $this->form->fill($this->task->toArray());
    }

    public function render()
    {
        return view('livewire.tasks.deatils-update-form');
    }

    public function form(Form $form): Form{
        return $form
                ->model($this->task)
                ->statePath('data')
                ->schema([

                    Select::make('created_by_user_id')
                            ->label("Reporter")
                            ->searchable(['name', 'email'])
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'email',
                                ignoreRecord: false,
                            )
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->email})")
                            ->disabled(true),


                    Select::make('task_status')
                            ->options(Task::SELECT_TAST_STATUSES)
                            ->default('to-do')
                            ->searchable()
                            ->selectablePlaceholder(false)
                            ->live()
                            ->required(),

                    Select::make('user_id')
                            ->label("Assignee")
                            ->searchable(['name', 'email'])
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'email',
                                ignoreRecord: false,
                            )
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->email})")
                            ->preload()->live(),

                    Select::make('task_type')
                            ->options(collect(Task::SELECT_TAST_TYPES)->map(function($item, $key){
                                $icon = Task::SELECT_TAST_TYPE_ICONS[$key];
                                return "<div class=''flex items-center><span class='inline-block mr-2'>$icon</span> $item</div>";
                            })->toArray())
                            ->searchable()
                            ->allowHtml()
                            ->default('task')
                            ->selectablePlaceholder(false)
                            ->required()
                            ->live(),


                    Select::make('task_priority')
                            ->options(collect(Task::SELECT_PRIORITIES)->map(function($item, $key){
                                $icon = "<img class='inline-block mr-2' src=\"" . asset(Task::PRIORITIES_ICON_FOLDER . "/" . $key . Task::PRIORITIES_ICON_EXTN) . "\" />";
                                return "<div class=''flex items-center>$icon $item</div>";
                            })->toArray())
                            ->searchable()
                            ->selectablePlaceholder(false)->live()
                            ->allowHtml(true)->required(),

                    DateTimePicker::make('due_date')->time(false),



                ]);
    }


    public function save(){
        $data = $this->form->getState();

        $this->task->update($data);

        $this->dispatch('reloadlivewire');

        Notification::make()
            ->success()
            ->title('Successfully Updated')
            ->send();
    }

    public function updated($property){

        if(str($property)->contains('data')){
            $this->save();
        }

    }
}
