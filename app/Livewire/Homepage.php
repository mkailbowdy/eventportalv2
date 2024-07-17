<?php

namespace App\Livewire;

use App\Enums\Category;
use App\Enums\Prefecture;
use App\Models\Event;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

use Filament\Tables;


class Homepage extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;


    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 3,
            ])
            ->query(Event::query()->with(['owner', 'users']))
            ->columns([
                Split::make([
                    ImageColumn::make('featured_image')
                        ->label(false)
                        ->square()
                        ->size(150)
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('name')
                            ->searchable()
                            ->wrap()
                            ->limit(100),
                        TextColumn::make('start_time')
                            ->dateTime('H:i'),
                        TextColumn::make('date')
                            ->dateTime('M j, Y'),
                        ImageColumn::make('owner.avatar_url')
                            ->label(false)
                            ->defaultImageUrl(function ($record) {
                                $firstLetter = substr($record->owner->name, 0, 1);
                                $avatarUrlIsNull = 'https://ui-avatars.com/api/?name='.urlencode($firstLetter).'&color=FFFFFF&background=030712';
                                return $record->avatar_url ?? $avatarUrlIsNull;
                            })
                            ->circular(),
                    ]),
                ]),

//                Panel::make([
//                    Stack::make([
//                        ImageColumn::make('owner.avatar_url')
//                            ->label(false)
//                            ->defaultImageUrl(function ($record) {
//                                $firstLetter = substr($record->owner->name, 0, 1);
//                                $avatarUrlIsNull = 'https://ui-avatars.com/api/?name='.urlencode($firstLetter).'&color=FFFFFF&background=030712';
//                                return $record->avatar_url ?? $avatarUrlIsNull;
//                            })
//                            ->circular(),
//                        TextColumn::make('owner.name')
//                            ->label('Host'),
//                    ]),
//                ])->collapsed()

            ])
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

            ], layout: FiltersLayout::Modal)
            ->hiddenFilterIndicators()
            ->persistFiltersInSession()
            ->filtersFormColumns(2)
            ->searchable(true);
    }

    public function render()
    {
        return view('livewire.homepage');
    }
}
