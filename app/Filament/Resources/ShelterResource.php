<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShelterResource\Pages;
use App\Filament\Resources\ShelterResource\RelationManagers;
use App\Models\Shelter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShelterResource extends Resource
{
    protected static ?string $model = Shelter::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->label('Address')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('description')
                    ->required(),
                Forms\Components\TextInput::make('latitude')
                    ->label('Latitude')
                    ->required()
                    ->numeric() 
                    ->rule('between:-90,90') 
                    ->placeholder('Enter latitude (-90 to 90)'),
                Forms\Components\TextInput::make('longitude')
                    ->label('Longitude')
                    ->required()
                    ->numeric()
                    ->rule('between:-180,180') 
                    ->placeholder('Enter longitude (-180 to 180)'),
                Forms\Components\TextInput::make('locationLink')
                    ->label('Location Link')
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
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('latitude')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => number_format($state, 8)),
                Tables\Columns\TextColumn::make('longitude')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => number_format($state, 8)),                            
                Tables\Columns\TextColumn::make('locationLink')
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
            'index' => Pages\ListShelters::route('/'),
            'create' => Pages\CreateShelter::route('/create'),
            'edit' => Pages\EditShelter::route('/{record}/edit'),
        ];
    }
}
