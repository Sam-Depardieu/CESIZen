<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StressEventResource\Pages;
use App\Models\StressEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StressEventResource extends Resource
{
    protected static ?string $model = StressEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Événements de stress';

    protected static ?string $modelLabel = 'Événement de stress';

    protected static ?string $pluralModelLabel = 'Événements de stress';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event_name')
                    ->label('Nom de l\'événement')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('points')
                    ->label('Points de stress')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_name')
                    ->label('Événement')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')
                    ->label('Points')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Dernière modification')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListStressEvents::route('/'),
            'create' => Pages\CreateStressEvent::route('/create'),
            'edit' => Pages\EditStressEvent::route('/{record}/edit'),
        ];
    }
}
