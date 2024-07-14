<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
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
                            ->minDate(now()->subYears(150))
                            ->minDate(now()->subYears(18))
                            ->helperText('Must be over 18 years old to register')
                            ->required(),
                        Checkbox::make('agree_to_terms')
                            ->label(new HtmlString("I have read the <a href='/rules'><span class='text-red-500 font-bold underline'>Community Guidelines</span></a> and agree to follow and abide by the terms of stated"))
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}


