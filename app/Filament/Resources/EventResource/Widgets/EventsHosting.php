<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class EventsHosting extends BaseWidget
{
//    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()->where('owner_id', auth()->id())
            )
            ->columns([
                ImageColumn::make('featured_image')
                    ->label(false)
                    ->square()->height(150)->width(100),
                TextColumn::make('name')
                    ->searchable()
                    ->wrap()
                    ->limit(100),
                TextColumn::make('date')
                    ->dateTime('M j, Y'),
                TextColumn::make('start_time')
                    ->dateTime('H:i'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\deleteAction::make(),
            ]);
    }
}
