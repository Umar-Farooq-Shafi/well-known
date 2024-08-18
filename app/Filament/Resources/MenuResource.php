<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use App\Models\MenuElementTranslation;
use App\Traits\LinkTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Illuminate\Support\Str;

class MenuResource extends Resource
{
    use LinkTrait;

    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-down';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'SUPER_ADMIN') || str_contains($role, 'ADMINISTRATOR');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Translation')
                    ->schema([
                        Forms\Components\Tabs::make('Translation')
                            ->columnSpanFull()
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('En (Default)')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-en')
                                            ->label('Menu Name')
                                            ->required(),

                                        Forms\Components\TextInput::make('header-en')
                                            ->label('Header Text'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Fr')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-fr')
                                            ->label('Nom du menu'),

                                        Forms\Components\TextInput::make('header-fr')
                                            ->label('En-tête'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Es')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-es')
                                            ->label('Nombre du menú'),

                                        Forms\Components\TextInput::make('header-es')
                                            ->label('En cabeza'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Ar')
                                    ->schema([
                                        Forms\Components\TextInput::make('name-ar')
                                            ->label('اسم القائمة'),

                                        Forms\Components\TextInput::make('header-ar')
                                            ->label('نص العنوان'),
                                    ]),
                            ]),

                        Forms\Components\Repeater::make('menuElements')
                            ->relationship()
                            ->label('Menu elements')
                            ->required()
                            ->mutateRelationshipDataBeforeFillUsing(function ($data) {
                                $translations = MenuElementTranslation::whereTranslatableId($data['id'])->get();

                                foreach ($translations as $translation) {
                                    if ($translation->locale === 'ar') {
                                        $data['label-ar'] = $translation->label;
                                    }

                                    if ($translation->locale === 'en') {
                                        $data['label-en'] = $translation->label;
                                    }

                                    if ($translation->locale === 'fr') {
                                        $data['label-fr'] = $translation->label;
                                    }

                                    if ($translation->locale === 'es') {
                                        $data['label-es'] = $translation->label;
                                    }
                                }

                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function ($data, $record) {
                                $trans = [
                                    'ar', 'en', 'fr', 'es'
                                ];

                                foreach ($trans as $tran) {
                                    if (
                                        !$record->menuElementTranslations()
                                            ->where('locale', $tran)
                                            ->exists() &&
                                        $data['label-' . $tran] !== null
                                    ) {
                                        MenuElementTranslation::create([
                                            'translatable_id' => $record->id,
                                            'label' => $data['label-' . $tran],
                                            'slug' => Str::slug($data['label-' . $tran]),
                                            'locale' => $tran,
                                        ]);
                                    }
                                }

                                foreach ($record->menuElementTranslations as $menuElementTrans) {
                                    if ($menuElementTrans->locale === 'ar') {
                                        $menuElementTrans->label = $data['label-ar'];
                                    }

                                    if ($menuElementTrans->locale === 'en') {
                                        $menuElementTrans->label = $data['label-en'];
                                    }

                                    if ($menuElementTrans->locale === 'fr') {
                                        $menuElementTrans->label = $data['label-fr'];
                                    }

                                    if ($menuElementTrans->locale === 'es') {
                                        $menuElementTrans->label = $data['label-es'];
                                    }

                                    $menuElementTrans->save();
                                }

                                return $data;
                            })
                            ->mutateRelationshipDataBeforeCreateUsing(function ($data) {
                                $data['position'] = 0;

                                return $data;
                            })
                            ->schema([
                                IconPicker::make('icon')
                                    ->sets(['fontawesome-solid'])
                                    ->preload(),

                                Forms\Components\Tabs::make('Translation')
                                    ->columnSpanFull()
                                    ->tabs([
                                        Forms\Components\Tabs\Tab::make('En (Default)')
                                            ->schema([
                                                Forms\Components\TextInput::make('label-en')
                                                    ->label('Menu Name')
                                                    ->required(),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Fr')
                                            ->schema([
                                                Forms\Components\TextInput::make('label-fr')
                                                    ->label('Nom du menu'),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Es')
                                            ->schema([
                                                Forms\Components\TextInput::make('label-es')
                                                    ->label('Nombre du menú'),
                                            ]),
                                        Forms\Components\Tabs\Tab::make('Ar')
                                            ->schema([
                                                Forms\Components\TextInput::make('label-ar')
                                                    ->label('اسم القائمة'),
                                            ]),
                                    ]),

                                Forms\Components\Select::make('link')
                                    ->label('Choose the link destination page')
                                    ->searchable()
                                    ->options(static::getLinks())
                                    ->required(),

                                Forms\Components\TextInput::make('custom_link')
                                    ->label('Custom link')
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('header'),

                Tables\Columns\TextColumn::make('menu_elements_count')
                    ->label('Elements')
                    ->counts('menuElements')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
