<?php

namespace App\Filament\Resources;

use App\Enums\Category;
use App\Enums\Prefecture;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    // Don't need to declare it, but just as example...
    protected static ?string $navigationLabel = 'Events';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Event::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label(false)
                    ->square()->height(150)->width(100),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->wrap()
                    ->limit(100),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime('M j, Y'),
//                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime('H:i'),
//                    ->sortable(),
            ])
            ->defaultSort('date', 'start_time')
            ->filters([
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Event Information')
                    ->columnSpanFull()
                    ->label(false)
                    ->footerActions([
                        Action::make('join')
                            ->label('I want to join!')
                            ->action(function (Event $event) {
                                Event::goingOrNot($event);
                            }),
                    ])
                    ->columns(2)
                    ->schema([

                        TextEntry::make('name'),
                        ImageEntry::make('featured_image')
                            ->label(false)
                            ->width(300)
                            ->height(300)
                            ->columnSpanFull(),
                        TextEntry::make('description')
                            ->html()
                            ->columnSpanFull(),
                        TextEntry::make('meeting_spot'),
                        TextEntry::make('prefecture'),
                        TextEntry::make('category'),
                        TextEntry::make('capacity'),
                        TextEntry::make('participation_status')
                            ->label('Participation Status'),
                        TextEntry::make('participants_count')
                            ->label('Total Participants'),
                    ]),
                Section::make('When')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('start_time')
                            ->time('H:m'),
                        TextEntry::make('end_time')
                            ->time('H:m'),
                    ]),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
