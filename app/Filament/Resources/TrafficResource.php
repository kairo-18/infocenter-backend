<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrafficResource\Pages;
use App\Filament\Resources\TrafficResource\RelationManagers;
use App\Models\Traffic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrafficResource extends Resource
{
    protected static ?string $model = Traffic::class;

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

                Forms\Components\Select::make('reason')
                ->label('Reason')
                ->options([
                    'Road Construction' => 'Road Construction',
                    'Traffic Accident' => 'Traffic Accident',
                    'Others' => 'Others',
                ])
                ->reactive()
                ->afterStateUpdated(fn (callable $set, $state) => $set('custom_reason', $state === 'Others' ? '' : null))
                ->required(),
            
            Forms\Components\TextInput::make('custom_reason')
                ->label('Specify Reason')
                ->hidden(fn (callable $get) => $get('reason') !== 'Others')
                ->reactive(),
            
            Forms\Components\Hidden::make('reason')
                ->dehydrated()
                ->dehydrateStateUsing(fn (callable $get) => $get('reason') === 'Others' ? $get('custom_reason') : $get('reason'))            
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

                Tables\Columns\TextColumn::make('reason')
                ->label('Reason')
                ->sortable()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'road_construction' => 'Road Construction',
                    'traffic_accident' => 'Traffic Accident',
                    default => $state, 
                }),            
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
            'index' => Pages\ListTraffic::route('/'),
            'create' => Pages\CreateTraffic::route('/create'),
            'edit' => Pages\EditTraffic::route('/{record}/edit'),
        ];
    }
}
