<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\HtmlString;

class Register extends BaseRegister
{

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->native(false)
                            ->placeholder('Jan 1, 1996')
                            ->minDate(now()->subYears(100))
                            ->maxDate(now()->subYears(18))
                            ->helperText('Must be over 18 years old to register')
                            ->required(),
                        Checkbox::make('agree')
                            ->label(new HtmlString("I have read the <a href='/rules'><span class='text-red-500 font-bold underline'>Community Guidelines</span></a> and agree to follow and abide by the terms stated"))
                            ->required()
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}


