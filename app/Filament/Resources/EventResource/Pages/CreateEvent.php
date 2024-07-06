<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function afterCreate(): void
    {
        $event = $this->record;
        $user = auth()->user();

        $event->users()->attach($user->id, ['participation_status' => 1, 'event_creator' => 1]);

    }
}
