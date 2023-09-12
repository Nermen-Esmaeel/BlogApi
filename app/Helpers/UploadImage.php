<?php
use Illuminate\Support\Facades\{Storage,File};


   function UploadImage($folder = null, $filename = null)

    {
        $FileName = time() . '.' .   $filename->getClientOriginalName();
        $path =  $filename->storeAs($folder, $FileName , 'public');
        return $path;
    }



  function DeleteImageFromStorage($filename = null)

    {
        //check if image exist in file
        if(File::exists(public_path().'/storage/'.$filename)){

            File::delete(public_path().'/storage/'.$filename);
        }


    }
