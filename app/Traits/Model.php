<?php

namespace App\Traits;

trait Model
{

    public function storeImage($image, $folder)
    {
        $extension = $image->extension();
        $photoName = time() . uniqid() . '.' . $extension;
        $image->storeAs("images/$folder", $photoName, 'public');
        return $photoName;
    }

}
