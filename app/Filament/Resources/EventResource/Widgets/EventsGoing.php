<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\EventResource\Pages;
use Illuminate\Database\Eloquent\Model;


class EventsGoing extends BaseWidget
{
    protected static ?string $heading = 'Your Events';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $userId = auth()->id(); // Get the current authenticated user's ID
        return $table
            ->query(
                Event::query()
                    ->join('event_user', 'events.id', '=', 'event_user.event_id')
                    ->where('event_user.user_id', $userId)
                    ->where('event_user.participation_status', 1)
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
            ->recordUrl(
                function (Model $record): string {
                    return '../events/'.$record->id;
//                    return route('users.view', ['record' => $record]);
                },
            )
            ->actions([
                Action::make('view')
                    ->url(fn(Event $record): string => route('filament.app.resources.events.view',
                        $record))
                    ->icon('heroicon-m-eye')
                    ->color('gray')
                ,
                Action::make('edit')
                    ->url(fn(Event $record): string => route('filament.app.resources.events.edit',
                        $record))
                    ->icon('heroicon-c-pencil-square')
                    ->visible(function (Event $record): bool {
                        return $record->owner_id === auth()->user()->id;
                    }),

            ]);
    }
}
