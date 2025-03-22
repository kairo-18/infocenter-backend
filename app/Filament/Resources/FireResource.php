<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FireResource\Pages;
use App\Filament\Resources\FireResource\RelationManagers;
use App\Models\Fire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FireResource extends Resource
{
    protected static ?string $model = Fire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->required(),
                Forms\Components\DateTimePicker::make('date')
                    ->label('Date')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Fire Status')
                    ->options([
                        'option1'=>'Ongoing',
                        'option2'=>'Fire Out'
                    ])
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
                Tables\Columns\TextColumn::make('date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Fire Status')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'option1' => 'Ongoing',
                        'option2' => 'Fire Out',
                        default => 'Unknown',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListFires::route('/'),
            'create' => Pages\CreateFire::route('/create'),
            'edit' => Pages\EditFire::route('/{record}/edit'),
        ];
    }
}
