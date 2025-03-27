<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmsResource\Pages;
use App\Filament\Resources\SmsResource\RelationManagers;
use App\Models\Sms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendSmsJob;


class SmsResource extends Resource
{
    protected static ?string $model = Sms::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2) // Creates a two-column layout
                    ->schema([
                        Select::make('type')
                            ->label('Type')
                            ->options([
                                'Flood' => 'Flood',
                                'General' => "General",
                                'Fire' => "Fire",
                                'Garbage' => "Garbage",
                                'Traffic' => "Traffic",
                                'Tsunami' => "Tsunami",
                                'Utilities' => "Utilities",
                            ])
                            ->searchable() // Enables search inside the dropdown
                            ->native(false) // Uses a custom dropdown style
                            ->placeholder('Select a type') // Placeholder text
                            ->required(),

                        Textarea::make('message')
                            ->label('Message')
                            ->placeholder('Enter your message here...')
                            ->rows(4) // Adjusts the height
                            ->columnSpanFull() // Makes it take the full width
                            ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->sortable()
                    ->searchable(),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('sendSms')
                    ->label('Send SMS')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm SMS Sending')
                    ->modalDescription('Are you sure you want to send this SMS? This action cannot be undone.')
                    ->modalButton('Yes, Send SMS')
                    ->action(fn (Sms $record) => self::sendSmsRequest($record)),
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
            'index' => Pages\ListSms::route('/'),
            'create' => Pages\CreateSms::route('/create'),
            'edit' => Pages\EditSms::route('/{record}/edit'),
        ];
    }

    protected static function sendSmsRequest(Sms $record)
    {
        SendSmsJob::dispatch($record);
    }
}
