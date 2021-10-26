<?php

use Illuminate\Support\Facades\Config;
use App\Models\Language;

function get_languages(){
    $lang = Language::active()->selection()->get();
    return $lang;
}

function get_default_lang()
{
    return Config::get('app.locale');
}

function uploadImage($folder,$image){
    $image->store('/',$folder);
    $filename = $image->hashName();
    $path = 'images/'.$folder.'/'.$filename;

    return $path;
}

?>