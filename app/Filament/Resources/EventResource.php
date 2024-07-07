<?php

namespace App\Filament\Resources;

use App\Enums\Category;
use App\Enums\Prefecture;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationLabel = 'All Events';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Events';

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
//            https://filamentphp.com/docs/3.x/tables/filters/getting-started
            ->filters([
//                Filter::make('event_creator')
//                    ->query(function (Builder $query): Builder {
//                        return $query->whereHas('users', function (Builder $query) {
//                            $query->where('event_user.event_creator', 1);
//                        });
//                    })
//                    ->label('Created by me'),
                Tables\Filters\SelectFilter::make('prefecture')
                    ->options(Prefecture::class)
                    ->multiple(),
                Tables\Filters\SelectFilter::make('category')
                    ->options(Category::class)
                    ->multiple()
            ], layout: FiltersLayout::AboveContent)
            ->hiddenFilterIndicators()
            ->persistFiltersInSession()
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\deleteAction::make(),
            ])
            ->searchable(false);
//            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
//            ]);
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
