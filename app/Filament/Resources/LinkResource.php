<?php

namespace App\Filament\Resources;

use App\Helper;
use Filament\Forms;
use App\Models\Link;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\LinkResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LinkResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;
use Webbingbrasil\FilamentCopyActions\Tables\CopyableTextColumn;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Link Setting')->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Select::make('domain')->options(\App\Models\Domain::all()->pluck('domain','domain')),
                            Forms\Components\Select::make('slug_prefix')
                                ->label('Slug Prefix')
                                ->options([
                                    '/v/?id=' => '/v/?id=',
                                    '/s/' => '/s/',
                                    'none' => '/',
                                ])
                                ->default('none')
                                ->live()
                                // Jangan simpan field ini ke database
                                ->dehydrated(false)
                                // Logika untuk mengisi prefix saat mengedit data yang ada
                                ->afterStateHydrated(function (Get $get, Set $set, ?string $state, ?Link $record) {
                                    if (!$record) {
                                        return;
                                    }
                                    $slug = $record->slug;
                                    $prefixes = ['/v/?id=', '/s/'];
                                    foreach ($prefixes as $prefix) {
                                        if (str_starts_with($slug, $prefix)) {
                                            $set('slug_prefix', $prefix);
                                            $set('slug', Str::after($slug, $prefix));
                                            return;
                                        }
                                    }
                                    $set('slug_prefix', 'none');
                                }),

                            Forms\Components\TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique(Link::class, 'slug', ignoreRecord: true)
                                // Gabungkan prefix dan slug sebelum menyimpan
                                ->dehydrateStateUsing(
                                    fn(Get $get, ?string $state): string =>
                                    $get('slug_prefix') !== 'none' ? $get('slug_prefix') . $state : $state
                                )
                                ->suffixAction(
                                    Action::make('generate_slug')
                                        ->label('Generate')
                                        ->icon('heroicon-o-arrow-path')
                                        ->action(fn(Set $set) => $set('slug', Str::random(8)))
                                ),
                        ])->columns(3)

                ]),

                Forms\Components\Section::make('Cloaking Setting')->schema([
                    Forms\Components\Select::make('cloaking_method')
                        ->options([
                            'template' => 'Template',
                            'redirect' => 'Redirect',
                        ])
                        ->required()
                        ->live(), // Penting untuk membuat form reaktif

                    // Muncul jika cloaking_method == 'redirect'
                    Forms\Components\TextInput::make('cloaking_url')
                        ->label('Cloaking URL (Redirect)')
                        ->url()
                        ->visible(fn(Get $get): bool => $get('cloaking_method') === 'redirect')
                        ->required(fn(Get $get): bool => $get('cloaking_method') === 'redirect'),

                    // Muncul jika cloaking_method == 'template'
                    Forms\Components\Select::make('template')
                        ->options([
                            'videy' => 'Videy',
                            'lorem' => 'Lorem',
                            'blank_page' => 'Blank Page',
                        ])
                        ->visible(fn(Get $get): bool => $get('cloaking_method') === 'template')
                        ->required(fn(Get $get): bool => $get('cloaking_method') === 'template'),

                    Forms\Components\Toggle::make('random_target_url')
                        ->label('Use Multiple Random Target URLs?')
                        ->required()
                        ->live(), // Penting untuk membuat form reaktif

                    // Muncul jika random_target_url == false
                    Forms\Components\TextInput::make('target_url')
                        ->label('Target URL')
                        ->url()
                        ->columnSpanFull()
                        ->visible(fn(Get $get): bool => !$get('random_target_url'))
                        ->required(fn(Get $get): bool => !$get('random_target_url')),

                    // Muncul jika random_target_url == true
                    Forms\Components\Textarea::make('target_url')
                        ->label('Target URLs (One URL per line)')
                        ->columnSpanFull()
                        ->visible(fn(Get $get): bool => $get('random_target_url'))
                        ->required(fn(Get $get): bool => $get('random_target_url'))
                        ->helperText('Each URL will be chosen randomly for redirection.'),

                ])->columns(3),


                Forms\Components\Section::make('Security Setting')->schema([

                    Forms\Components\Select::make('lock_country')
                        ->multiple()
                        ->searchable()
                        ->options(Helper::getCountryLists()) // Memanggil fungsi helper untuk daftar negara
                        ->required(),

                    Forms\Components\Select::make('lock_platform')
                        ->options([
                            'mobile' => 'Mobile-Only',
                            'desktop' => 'Desktop-Only',
                            'FBBrowser' => 'Fb-Browser',
                            'All' => 'All',
                        ])
                        ->required(),

                    Forms\Components\Select::make('lock_referer')
                        ->options([
                            'FacebookAds' => 'FacebookAds',
                            'GoogleAds' => 'GoogleAds',
                            'All' => 'All',
                        ])
                        ->required(),

                    Forms\Components\Toggle::make('active')
                        ->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                ->formatStateUsing(fn( $record) => $record->domain.$record->slug)
                ->copyable()
                ->copyableState(fn ($record): string => "{$record->domain}{$record->slug}")
                ->icon('heroicon-o-clipboard')->iconPosition('after')
                ->tooltip(fn() => 'Click to copy')
                ,
                Tables\Columns\TextColumn::make('cloaking_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lock_country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lock_platform')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lock_referer')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('active'),
                Tables\Columns\TextColumn::make('clicks')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }
}
