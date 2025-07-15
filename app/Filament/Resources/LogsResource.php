<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Link;
use App\Models\Logs;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LogsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LogsResource\RelationManagers;

class LogsResource extends Resource
{
    protected static ?string $model = Logs::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('link_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('referer')
                    ->required(),
                Forms\Components\TextInput::make('ip')
                    ->required(),
                Forms\Components\TextInput::make('device')
                    ->required(),
                Forms\Components\TextInput::make('browser')
                    ->required(),
                Forms\Components\TextInput::make('country')
                    ->required(),
                Forms\Components\TextInput::make('user_agent')
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->required(),
            ]);
    }
    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('link.slug')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        return $record?->link?->domain . $record?->link?->slug ?? '-';
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()->badge()->color(fn($state) => match ($state) {
                        'allow' => 'success',
                        'block' => 'error'
                    }),
                Tables\Columns\TextColumn::make('referer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('device')
                    ->searchable(),
                Tables\Columns\TextColumn::make('browser')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_agent')
                    ->searchable()->wrap(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('link.slug')
                    ->options(function () {
                        return \App\Models\Link::all()->mapWithKeys(function ($link) {
                            $label = $link->domain  . $link->slug;
                            return [$link->id => $label];
                        })->toArray();
                    }),
                SelectFilter::make('type')->options(['allow' => 'Allow', 'block' => 'Block'])

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListLogs::route('/'),
            'create' => Pages\CreateLogs::route('/create'),
            'view' => Pages\ViewLogs::route('/{record}'),
            'edit' => Pages\EditLogs::route('/{record}/edit'),
        ];
    }
}
