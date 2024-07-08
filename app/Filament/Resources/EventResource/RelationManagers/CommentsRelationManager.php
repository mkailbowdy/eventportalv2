<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $title = "Comments";


    public function isReadOnly(): bool
    {
        return false;
    }

//    protected static ?string $badge = 'new';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('body')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar_url')
                    ->circular()
                    ->label(false),
                Tables\Columns\TextColumn::make('body')
                    ->label(false),
                TextColumn::make('user.name'),
                TextColumn::make('created_at')
                    ->sortable()
                    ->formatStateUsing(function (Carbon $state) {
                        $diff = $state->diffForHumans([
                            'parts' => 1,
                            'short' => true,
                        ]);
                        return $diff;
                    })
                    ->tooltip(fn(Carbon $state) => $state->format('F j, Y, g:i A')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
//            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
//            ]);
    }
}
