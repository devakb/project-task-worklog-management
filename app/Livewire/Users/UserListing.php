<?php

namespace App\Livewire\Users;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn\TextColumnSize;

class UserListing extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function render()
    {
        return view('livewire.users.user-listing');
    }

    public function table(Table $table) : Table{

        return $table->query(User::query())
        ->heading("Users")
                    ->columns([
                        TextColumn::make('id')->sortable()->searchable(),
                        TextColumn::make('name')->label("Name & Email")->description(function(User $record){
                            return $record->email;
                        })->searchable(['name', 'email']),
                        TextColumn::make('created_at')->label("Joining Date")
                        ->since()
                        ->dateTimeTooltip()
                        ->sortable(),
                        SelectColumn::make('is_senior_member')->label("Access Level")
                        ->options(User::SELECT_ROLE_TYPES)
                        ->rules(['required'])
                        ->selectablePlaceholder(false)
                        ->afterStateUpdated(function ($record, $state) {
                            Notification::make()
                                ->success()
                                ->title('User Access Level Updated')
                                ->body('The user Access Level has been updated successfully.')
                                ->send();
                        }),
                        SelectColumn::make('status')->label("Status")
                        ->options(User::SELECT_STATUS_TYPES)
                        ->rules(['required'])
                        ->selectablePlaceholder(false)
                        ->afterStateUpdated(function ($record, $state) {
                            Notification::make()
                                ->success()
                                ->title('User Status Updated')
                                ->body('The user status has been updated successfully.')
                                ->send();
                        }),

                    ])->filters([
                        SelectFilter::make('is_senior_member')
                            ->options(User::SELECT_ROLE_TYPES),

                        Filter::make('created_at')
                            ->form([
                                DatePicker::make('created_from'),
                                DatePicker::make('created_until'),
                            ])
                            ->query(function (Builder $query, array $data): Builder {
                                return $query
                                    ->when(
                                        $data['created_from'],
                                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                                    )
                                    ->when(
                                        $data['created_until'],
                                        fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                                    );
                            })
                            ->indicateUsing(function (array $data): array {
                                $indicators = [];

                                if ($data['created_from'] ?? null) {
                                    $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                                        ->removeField('created_from');
                                }

                                if ($data['created_until'] ?? null) {
                                    $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                                        ->removeField('created_until');
                                }

                                return $indicators;
                            })
                    ])
                    ->defaultSort('id', 'desc')
                    ->searchPlaceholder("Search (ID, Name & Email")
                    ->headerActions([
                        CreateAction::make()->label("Create New User")->form([
                            TextInput::make('name')->required(),
                            TextInput::make('email')->email()->required(),
                            TextInput::make('password')->password()->required(),
                            Select::make('is_senior_member')->label("Access Level")->options(User::SELECT_ROLE_TYPES),
                            Select::make('status')->label("Status")->options(User::SELECT_STATUS_TYPES)
                        ]),
                    ])
                    ->actions([
                        EditAction::make()
                            ->button()
                            ->color("gray")
                            ->form([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->email()->required(),
                                Select::make('is_senior_member')->label("Access Level")->options(User::SELECT_ROLE_TYPES),
                                Select::make('status')->label("Status")->options(User::SELECT_STATUS_TYPES)
                            ]),

                        Action::make("change_password")
                            ->button()
                            ->color("gray")
                            ->icon("heroicon-m-document-text")
                            ->label("Change Password")->form([
                                TextInput::make('password')
                                ->password()
                                ->required()
                                ->revealable(),
                                TextInput::make('password_confimation')
                                ->password()
                                ->required()
                                ->same('password')
                                ->label('Confirm Password')
                                ->revealable(),
                            ])->after(function(){
                                Notification::make()
                                    ->success()
                                    ->title('User Password Changed')
                                    ->body('The user password has been updated successfully.')
                                    ->send();
                            }),
                        // DeleteAction::make()
                        //     ->button()
                        //     ->color("danger")

                    ]);

    }
}
