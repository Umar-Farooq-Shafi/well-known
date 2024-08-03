<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class MyOrganizerProfilePage extends Page
{
    protected static ?string $navigationIcon = 'fas-address-card';

    protected static string $view = 'filament.pages.my-organizer-profile-page';

    public array $data = [];

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'ORGANIZER');
    }

    public function mount(): void
    {
        $organizer = auth()->user()->organizer;

        $this->form->fill([
            'name' => $organizer->name,
            'description' => $organizer->description,
            'categories' => $organizer->categories()->pluck('id'),
            'logo_name' => $organizer->logo_name,
            'logo_original_name' => $organizer->logo_original_name,
            'cover_name' => $organizer->cover_name,
            'cover_original_name' => $organizer->cover_original_name,
            'country_id' => $organizer->country_id,
            'website' => $organizer->website,
            'email' => $organizer->email,
            'phone' => $organizer->phone,
            'facebook' => $organizer->facebook,
            'twitter' => $organizer->twitter,
            'instagram' => $organizer->instagram,
            'googleplus' => $organizer->googleplus,
            'linkedin' => $organizer->linkedin,
            'youtubeurl' => $organizer->youtubeurl,
            'showvenuesmap' => $organizer->showvenuesmap,
            'showfollowers' => $organizer->showfollowers,
            'showreviews' => $organizer->showreviews,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Organizer name')
                    ->required(),

                Forms\Components\RichEditor::make('description')
                    ->label('About the organizer')
                    ->required(),

                Forms\Components\Select::make('categories')
                    ->label('Categories')
                    ->required()
                    ->searchable()
                    ->multiple()
                    ->options(function () {
                        $categories = Category::all();
                        $options = [];

                        foreach ($categories as $category) {
                            $options[$category->id] = $category->categoryTranslations()
                                ->where('locale', App::getLocale())->first()?->name;
                        }

                        return $options;
                    })
                    ->getSearchResultsUsing(
                        function (string $search) {
                            $options = [];

                            $categories = Category::with([
                                'categoryTranslations' => function ($query) use ($search) {
                                    $query->where('locale', App::getLocale())
                                        ->where('name', 'LIKE', "%{$search}%");
                                }
                            ])
                                ->get();

                            foreach ($categories as $category) {
                                if ($name = $category->categoryTranslations->first()?->name) {
                                    $options[$category->id] = $name;
                                }
                            }

                            return $options;
                        }
                    ),

                Forms\Components\FileUpload::make('logo_name')
                    ->label('Organizer Logo')
                    ->required()
                    ->disk('public')
                    ->directory('organizers')
                    ->formatStateUsing(fn($state) => $state ? ['organizers/' . $state] : null)
                    ->visibility('public')
                    ->storeFileNamesIn('logo_original_name'),

                Forms\Components\FileUpload::make('cover_name')
                    ->label('Cover Photo')
                    ->helperText('Optionally add a cover photo to showcase your organizer activities')
                    ->disk('public')
                    ->directory('organizers/covers')
                    ->formatStateUsing(fn($state) => $state ? ['organizers/covers/' . $state] : null)
                    ->visibility('public')
                    ->storeFileNamesIn('cover_original_name'),

                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->options(function () {
                        $countries = Country::all();
                        $options = [];

                        foreach ($countries as $country) {
                            $options[$country->id] = $country->countryTranslations()
                                ->where('locale', App::getLocale())->first()->name;
                        }

                        return $options;
                    }),

                Forms\Components\TextInput::make('website'),

                Forms\Components\TextInput::make('email'),

                PhoneInput::make('phone'),

                Forms\Components\TextInput::make('facebook'),

                Forms\Components\TextInput::make('twitter'),

                Forms\Components\TextInput::make('instagram'),

                Forms\Components\TextInput::make('googleplus'),

                Forms\Components\TextInput::make('linkedin'),

                Forms\Components\TextInput::make('youtubeurl')
                    ->helperText('If you have an Youtube video that represents your activities as an event organizer, add it in the standard format: https://www.youtube.com/watch?v=FzG4uDgje3M')
                    ->label('Youtube video url'),

                Forms\Components\Radio::make('showvenuesmap')
                    ->boolean()
                    ->required()
                    ->label('Show venues map')
                    ->helperText('Show a map at the bottom of your organizer profile page containing the venues you added'),

                Forms\Components\Radio::make('showfollowers')
                    ->boolean()
                    ->required()
                    ->label('Show followers')
                    ->helperText('Show the number and list of people that follow you'),

                Forms\Components\Radio::make('showreviews')
                    ->boolean()
                    ->required()
                    ->label('Show reviews')
                    ->helperText('Show the reviews that you received for your events')
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        $organizer = auth()->user()->organizer;

        $organizer->update([
            'name' => $this->data['name'],
            'description' => $this->data['description'],
            'country_id' => $this->data['country_id'],
            'website' => $this->data['website'],
            'email' => $this->data['email'],
            'phone' => $this->data['phone'],
            'facebook' => $this->data['facebook'],
            'twitter' => $this->data['twitter'],
            'instagram' => $this->data['instagram'],
            'googleplus' => $this->data['googleplus'],
            'linkedin' => $this->data['linkedin'],
            'youtubeurl' => $this->data['youtubeurl'],
            'showvenuesmap' => $this->data['showvenuesmap'],
            'showfollowers' => $this->data['showfollowers'],
            'showreviews' => $this->data['showreviews'],
        ]);

        $organizer->categories()->detach();
        $organizer->categories()->attach($this->data['categories']);

        $this->uploadImage(head($this->data['logo_name']), true, $organizer);

        if (data_get($this->data, 'cover_name') && count($this->data['cover_name'])) {
            $this->uploadImage(head($this->data['cover_name']), false, $organizer);
        }

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

    private function uploadImage($tempFileUpload, $isLogo, $organizer): void
    {
        $img = Str::ulid() . '.' . $tempFileUpload->getClientOriginalExtension();

        $disk = $isLogo ? "organizers" : "organizers/covers";

        $tempFileUpload->storeAs($disk, $img, 'public');

        $size = Storage::disk('public')->size("$disk/" . $img);
        $mimetype = File::mimeType(Storage::disk('public')->path("$disk/" . $img));

        $manager = new ImageManager(new Driver());
        $image = $manager->read(Storage::disk('public')->path("$disk/" . $img));

        if ($isLogo) {
            $organizer->update([
                'logo_name' => $img,
                'logo_size' => $size,
                'logo_mime_type' => $mimetype,
                'logo_dimensions' => $image->width() . "," . $image->height(),
                'logo_original_name' => $tempFileUpload->getClientOriginalName()
            ]);
        } else {
            $organizer->update([
                'cover_name' => $img,
                'cover_size' => $size,
                'cover_mime_type' => $mimetype,
                'cover_dimensions' => $image->width() . "," . $image->height(),
                'logo_original_name' => $tempFileUpload->getClientOriginalName()
            ]);
        }
    }

}
