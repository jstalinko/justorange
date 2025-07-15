<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Domain;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use App\Filament\Resources\DomainResource\Pages;
use App\Filament\Resources\DomainResource\RelationManagers;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('domain')
                    ->required(),
                Forms\Components\Toggle::make('connected')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain')
                    ->searchable(),
                Tables\Columns\IconColumn::make('connected')
                    ->boolean(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                Tables\Actions\BulkAction::make('Test Connect')->action(function($records){
                    foreach($records as $record){
                        
                    $settings = Storage::get('settings.json'); // storage/app/settings.json  
                    $setting= json_decode($settings, true) ?? [];
                        $domen = $record->domain;
                        $domainIp = gethostbyname($domen);
                        $ip_server = $setting['server_ip'];

                        if($domainIp == $ip_server)
                        {
                            Notification::make('ip_'.$domen)->title('DOMAIN: '.$domen.' Connected!' )->body('Domain '.$domen.' Resolved to IP '.$ip_server)->success()->send();
                            Domain::find($record->id)->update(['connected'=> true]);
                        }else{
                            Notification::make('ip_'.$domen)->title('DOMAIN:'. $domen.' Not connected')->body('This check is not supported for Cloudflare domain or your domain not yet pointing to IP Server')->danger()->send();
                            Domain::find($record->id)->update(['connected'=> false]);

                        }
                    }
                      
                })->deselectRecordsAfterCompletion()
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
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'view' => Pages\ViewDomain::route('/{record}'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
