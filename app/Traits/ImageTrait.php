<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

trait ImageTrait
{
    protected function saveImage(Model $model, string $dir): void
    {
        $img = last(explode('/', $model->image_name));

        $size = Storage::disk('public')->size("$dir/" . $img);
        $mimetype = File::mimeType(Storage::disk('public')->path("$dir/" . $img));

        $manager = new ImageManager(new Driver());
        $image = $manager->read(Storage::disk('public')->path("$dir/" . $img));

        $model->update([
            'image_name' => $img,
            'image_size' => $size,
            'image_mime_type' => $mimetype,
            'image_dimensions' => $image->width() . "," . $image->height(),
        ]);
    }
}
