<?php

namespace App\Traits;

trait AppHelperTrait
{

        /**
     * Document Store
     *
     * @param string $message
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function processFile($file, $directoryName)
    {
        $image = $file;
        $namewithextension = str_replace(' ', '', $image->getClientOriginalName());
        $name = explode('.', $namewithextension)[0];
        $extension = $image->getClientOriginalExtension();
        $uploadname = $name . "_" . time() . '.' . $extension;

        $path = $file->storeAs('public/'.$directoryName, $uploadname);

        return $uploadname;

    }

}
