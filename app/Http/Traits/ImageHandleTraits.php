<?php

namespace App\Http\Traits;

use Storage;
use File;
use Image;

trait ImageHandleTraits{

    public function checkValidImage($image)
    {
        $extention = $image->getClientOriginalExtension();
        if ($extention === 'jpeg' || $extention === 'jpg' || $extention === 'png' || $extention === 'gif' || $extention === 'svg') {
            return $extention;
        } else {
            return 'Invalid image format. Please try again';
        }
    }

    public function deleteImage($image, $path)
    {
        $oldImagePath = public_path("$path/$image");
        $oldImagePaththumb = public_path("$path/thumb/$image");
        if (File::exists($oldImagePath)) 
            if(File::delete($oldImagePath))
                if (File::exists($oldImagePaththumb)) 
                    return File::delete(($oldImagePaththumb));
                
        
            return true;
    }

    public function resizeImage($image, $path,$resize=false,$w=0,$h=0,$ratio=false){
        $imageName = time() . "." . $this->checkValidImage($image);
        $destinationPath = public_path("$path");
        if (!File::exists($destinationPath.'/thumb')) {
            File::makeDirectory($destinationPath.'/thumb', 0775, true, true);
        }
        $img = Image::make($image->path());
        if($ratio)
            $img->resize($w, $h, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.'/thumb/'.$imageName);
        else
            $img->resize($w, $h)->save($destinationPath.'/thumb/'.$imageName);

        
        $image->move($destinationPath, $imageName);
        return $imageName;
    }
}