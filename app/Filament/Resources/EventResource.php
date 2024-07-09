<?php

namespace App\Filament\Resources;

use App\Enums\Category;
use App\Enums\Prefecture;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

//    protected static ?string $navigationLabel = 'All Events';
//    protected static ?string $navigationIcon = 'heroicon-o-user-group';
//    protected static ?string $navigationGroup = 'Events';

// ADD THIS TO A NEW RESOURCE???
//    public static function getEloquentQuery(): Builder
//    {
//        return parent::getEloquentQuery()
//            ->join('event_user', 'events.id', '=', 'event_user.event_id')
//            ->where('participation_status', 1);
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Event::getForm());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(Event::getInfoList());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(Event::getTheTable())
            ->defaultSort('date', 'start_time')
            ->filters([
                Filter::make('date')
                    ->form([
                        DatePicker::make('date')->default(now())
                            ->label('Events on and after'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            );
                    })
                    ->columnSpanFull(),
                Tables\Filters\SelectFilter::make('prefecture')
                    ->options(Prefecture::class)
                    ->multiple(),
                Tables\Filters\SelectFilter::make('category')
                    ->options(Category::class)
                    ->multiple(),
                Filter::make('event_creator')
                    ->query(function (Builder $query): Builder {
                        return $query->whereHas('users', function (Builder $query) {
                            $query->where('event_user.event_creator', 1)
                                ->where('users.id', auth()->id());
                        });
                    })
                    ->default(false)
                    ->label('Events I\'m hosting')
                    ->columnSpanFull()->toggle(),
                Filter::make('participation_status')
                    ->query(function (Builder $query): Builder {
                        return $query->whereHas('users', function (Builder $query) {
                            $query->where('event_user.participation_status', 1)
                                ->where('users.id', auth()->id());
                        });
                    })
                    ->default(false)
                    ->label('Events I\'m going to')
                    ->columnSpanFull()->toggle(),

            ], layout: FiltersLayout::Modal)
            ->hiddenFilterIndicators()
            ->persistFiltersInSession()
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\deleteAction::make(),
            ])
            ->searchable(true);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
            RelationManagers\UsersRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }

}
