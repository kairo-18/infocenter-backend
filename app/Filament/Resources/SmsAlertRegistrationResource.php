<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsAlertRegistrationResource\Pages;
use App\Filament\Resources\SmsAlertRegistrationResource\RelationManagers;
use App\Models\SmsAlertRegistration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SmsAlertRegistrationResource extends Resource
{
    protected static ?string $model = SmsAlertRegistration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('LastName')
                ->label('Last Name')
                ->required()
                ->maxLength(50),

            Forms\Components\TextInput::make('FirstName')
                ->label('First Name')
                ->required()
                ->maxLength(50),

            Forms\Components\TextInput::make('MiddleName')
                ->label('Middle Name')
                ->nullable()
                ->maxLength(50),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email() 
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('ContactNumber')
                ->label('Contact Number')
                ->required()
                ->maxLength(12) 
                ->numeric(), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('LastName')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('FirstName')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('MiddleName')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ContactNumber')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSmsAlertRegistrations::route('/'),
            'create' => Pages\CreateSmsAlertRegistration::route('/create'),
            'edit' => Pages\EditSmsAlertRegistration::route('/{record}/edit'),
        ];
    }
}
