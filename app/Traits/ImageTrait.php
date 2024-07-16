<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

trait ImageTrait
{
    protected static function saveImage(Model $model, string $dir, bool $isCreate = false): void
    {
        if ($model->isDirty('image_name') || $isCreate) {
            $img = last(explode('/', $model->image_name));

            $size = Storage::disk('public')->size("$dir/" . $img);
            $mimetype = File::mimeType(Storage::disk('public')->path("$dir/" . $img));

            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::disk('public')->path("$dir/" . $img));

            $model->image_name = $img;
            $model->image_size = $size;
            $image->image_mime_type = $mimetype;
            $model->image_dimensions = $image->width() . "," . $image->height();
        }
    }

}
