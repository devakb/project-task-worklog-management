<?php

namespace App\Livewire\Projects;

use App\Models\User;
use App\Models\Project;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\ProjectMember;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;

class ProjectMembers extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public Project $project;

    public function mount(Project $project){
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.projects.project-members');
    }

    public function defaultFormFields() {
        return [
            Select::make('is_allocated')->label("Status")->options(ProjectMember::SELECT_STATUS)->default(1)->required(),
            Select::make('role')->options(ProjectMember::SELECT_ROLE)->required(),
            TagsInput::make('skills')->separator(',')->placeholder("Select skills"),
            Select::make('engagement')->options(ProjectMember::SELECT_ENGAGEMENT)->required(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading("Project Members")
            ->query(ProjectMember::with('user')->where('project_id', $this->project->id))
            ->paginated(true)
            ->searchable()
            ->filters([
                SelectFilter::make('is_allocated')->label("Status")->options(ProjectMember::SELECT_STATUS),
                SelectFilter::make('role')->options(ProjectMember::SELECT_ROLE),
                SelectFilter::make('engagement')->options(ProjectMember::SELECT_ENGAGEMENT),
            ])
            ->columns([
                ViewColumn::make('user.name')->view('filament.tables.columns.assignee_column_for_tasks')->label("User")->searchable(['name', 'email']),
                TextColumn::make('role')->formatStateUsing(fn($record) => ProjectMember::SELECT_ROLE[$record->role]),
                TextColumn::make('engagement')->label("Engagement")->formatStateUsing(fn($record) => ProjectMember::SELECT_ENGAGEMENT[$record->engagement]),
                TextColumn::make('created_at')->label("Added On")->date()->sortable(),
                // TextColumn::make('updated_at')->label("Last Modified")->dateTime("M d, Y")->sortable(),
                TextColumn::make('skills')->badge()->separator(',')->searchable()->placeholder("N/A"),
                TextColumn::make('is_allocated')->label("Status")
                ->formatStateUsing(fn($record) => ProjectMember::SELECT_STATUS[$record->is_allocated])
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    '1' => 'success',
                    '0' => 'danger',
                }),
            ])
            ->actions([
                Action::make('update_is_allocated')
                    ->label(fn($record) => $record->is_allocated == 1 ? "Deallocate" : "Re-allocate")
                    ->button()
                    ->outlined()
                    ->icon(fn($record) => $record->is_allocated == 1 ? "heroicon-m-pause-circle" : "heroicon-m-check-badge")
                    ->color(fn($record) => $record->is_allocated == 1 ? "danger" : "success")
                    ->action(fn ($record) => $record->update(["is_allocated" => !$record->is_allocated]))
                    ->hidden(!optional(auth())->user()->is_senior_member),

                EditAction::make()->button()->outlined()->color('info')->form($this->defaultFormFields())
                    ->hidden(!optional(auth())->user()->is_senior_member)
            ])
            ->headerActions([
                CreateAction::make()->label("Allocate New Member")->form([
                    Hidden::make('project_id')->dehydrateStateUsing(fn($state) => $this->project->id),
                    Select::make('user_id')
                        ->searchable(['name', 'email'])
                        ->relationship(
                            name: 'user',
                            titleAttribute: 'email',
                            ignoreRecord: true,
                            modifyQueryUsing: fn ($query) => $query->whereNotIn('id', $this->project->members()->pluck('user_id')->toArray()))
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->email})")
                        ->preload()
                        ->required(),

                    ...$this->defaultFormFields()
                ])
                ->createAnother(false)
                ->requiresConfirmation()
                ->hidden(!optional(auth())->user()->is_senior_member),
            ]);
    }
}
