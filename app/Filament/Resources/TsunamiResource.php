<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TsunamiResource\Pages;
use App\Filament\Resources\TsunamiResource\RelationManagers;
use App\Models\Tsunami;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TsunamiResource extends Resource
{
    protected static ?string $model = Tsunami::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('Enter the name of '),
                Forms\Components\TextInput::make('description')
                    ->label('description')
                    ->required()
                    ->placeholder('Enter the description of the tsunami'),
                Forms\Components\TextInput::make('severity')
                    ->label('Severity')
                    ->required()
                    ->placeholder('Enter the severity of the tsunami'),
                Forms\Components\DateTimePicker::make('date')
                    ->label('Date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('severity')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
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
            'index' => Pages\ListTsunamis::route('/'),
            'create' => Pages\CreateTsunami::route('/create'),
            'edit' => Pages\EditTsunami::route('/{record}/edit'),
        ];
    }
}
