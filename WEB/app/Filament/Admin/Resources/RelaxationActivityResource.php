<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RelaxationActivityResource\Pages;
use App\Models\RelaxationActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RelaxationActivityResource extends Resource
{
    protected static ?string $model = RelaxationActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'Activités de détente';

    protected static ?string $modelLabel = 'Activité de détente';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'video' => 'Vidéo',
                        'audio' => 'Audio',
                        'article' => 'Article',
                        'exercice' => 'Exercice',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->label('Durée (min)')
                    ->numeric(),
                Forms\Components\TextInput::make('url')
                    ->label('Lien / URL')
                    ->url()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durée')
                    ->suffix(' min'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'video' => 'Vidéo',
                        'audio' => 'Audio',
                        'article' => 'Article',
                        'exercice' => 'Exercice',
                    ]),
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
            'index' => Pages\ListRelaxationActivities::route('/'),
            'create' => Pages\CreateRelaxationActivity::route('/create'),
            'edit' => Pages\EditRelaxationActivity::route('/{record}/edit'),
        ];
    }
}
