<?php

namespace App\Listeners;

// use Unisharp\Laravelfilemanager\Events\ImageIsUploading;
// use UniSharp\LaravelFilemanager\Events\ImageWasUploaded;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class IsUploadingImageListener
{
    /**
     * Handle the event.
     *
     * @param  ImageIsUploading  $event
     * @return void
     */
    public function handle(ImageWasUploaded $event)
    {
        // $path_parts = pathinfo($event->path());

        // // echo $path_parts['dirname'];
        // // echo $path_parts['basename']."xxxx";
        // // echo $path_parts['extension']."xxxx";
        // // echo $path_parts['filename']."xxxx";

        // $ppp = str_replace('\\','/',$path_parts['dirname']);
        // $pat = explode('/app/public/', $ppp);
        // // print_r($pat);
        // // die();
        // $real_pat = $pat[1];
        // //  echo $real_pat;
        // $old = $real_pat.'/'.$path_parts['filename'].'.'.$path_parts['extension'];
        // $new = $real_pat.'/'.Auth::user()->id.'_-'.$path_parts['filename'].'.'.$path_parts['extension'];

        // $old_thumbs = $real_pat.'/thumbs/'.$path_parts['filename'].'.'.$path_parts['extension'];
        // $new_thumbs = $real_pat.'/thumbs/'.Auth::user()->id.'_-'.$path_parts['filename'].'.'.$path_parts['extension'];
        // // echo $old;
        // // echo $new;
        // //  die();
        // Storage::disk('public')->move($old, $new);
        // if(Storage::disk('local')->exists($old_thumbs))
        // {
        //     Storage::disk('public')->move($old_thumbs, $new_thumbs);
        // }
        // // Storage::delete('photos/1/bukti up.PNG');
       
    }
}