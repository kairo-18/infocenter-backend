<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PowerResource\Pages;
use App\Filament\Resources\PowerResource\RelationManagers;
use App\Models\Power;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PowerResource extends Resource
{
    protected static ?string $model = Power::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Title')
                    ->required()
                    ->placeholder('Enter the title'),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->required()
                    ->placeholder('Enter the description'),
                Forms\Components\DateTimePicker::make('status')
                    ->label('Status')
                    ->required()
                    ->placeholder('Enter the status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
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
            'index' => Pages\ListPowers::route('/'),
            'create' => Pages\CreatePower::route('/create'),
            'edit' => Pages\EditPower::route('/{record}/edit'),
        ];
    }
}
