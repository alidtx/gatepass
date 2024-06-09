<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
trait FileProcessTrait
{

    public function processFile($file, $name)
    {
        $requestFile = $file;
        $fileName = time() . rand(11111, 99999). '_'. $name;
        $ext = strtolower($requestFile->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $fileName . '.' .$ext;
        $upload_path = $name;    //Creating Sub directory in Public folder to put image
        $requestFile->storeAs($upload_path, $image_full_name, 'public');
        return $upload_path . '/' . $image_full_name;
        
    }

    public function processMultiple($files, $name, $path):array
    {
        $fileList = [];
        foreach ($files as $file){
            $image_name = $this->fileName($name);
            $ext = strtolower($file->getClientOriginalExtension()); // You can use also getClientOriginalName()
            $image_full_name = $image_name . '.' . $ext;
            $upload_path = public_path('uploads') .'/'. $path;    //Creating Sub directory in Public folder to put image
            if (!File::isDirectory($upload_path)) {
                File::makeDirectory($upload_path, 0777, true, true);
            }
            $file->move($upload_path, $image_full_name);
            array_push($fileList, $path . '/' . $image_full_name); // Just return image path
        }
        return $fileList;
    }

    public function processSingle($file, $name, $path, $oldImage = null):string
    {
        $image_name = $this->fileName($name);
        $ext = strtolower($file->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name . '.' .$ext;
        $upload_path = public_path('uploads') .'/'. $path;    //Creating Sub directory in Public folder to put image
        if (!File::isDirectory($upload_path)) {
            File::makeDirectory($upload_path, 0777, true, true);
        }
        $file->move($upload_path, $image_full_name);
        $oldName = explode('.', str_replace('/', '.', $oldImage));
        if($oldImage && in_array('no-image', $oldName)){
            $oldImageName = Arr::last(explode('/uploads/', $oldImage));
            if(!strpos($oldImageName, 'no-image')) {
                File::exists('uploads/'.$oldImageName) ? File::delete('uploads/'.$oldImageName) : null;
            }
        }
        return $path . '/' . $image_full_name; // Just return image path

    }

    public function processSingleInStorage($file, $name, $path):string
    {
        $image_name = $name ? $name : time() . rand(11111, 99999) . auth()->user()->id;
        $ext = strtolower($file->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name . '.' . $ext;
        $upload_path = storage_path('app/' . $path);    //Creating Sub directory in Public folder to put image
        $file->move($upload_path, $image_full_name);
        return $path . '/' . $image_full_name; // Just return image path
    }

    public function extensionValidation($fileName, $allowed = []):bool
    {
        $filename = $_FILES[$fileName]['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), $allowed)) {
            return true;
        }
        return false;
    }

    private function fileName($name):string{
        return $name ? $name : time() . rand(11111, 99999) . auth()->user()->id;
    }
}
