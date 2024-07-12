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
            ->query(Event::query())
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
