<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;

class Post extends Model
{
    use HasFactory;

    public $title ; 

    public $date ; 

    public $body ; 

    public function __construct($title , $date , $body )
    {
        $this->title = $title;
        $this->date = $date;
        $this->body = $body;
    }

    public static function alll()
    {
        $files=  File::files(resource_path("posts/"));
        // dd($files);
        return array_map(function($file){
            return file_get_contents($file);
        },
        $files);
    }

    public static function find($slug)
    {
        $path = resource_path("posts/{$slug}.html");
        if (! file_exists($path)) {
            // return redirect('/');
            throw new ModelNotFoundException();
        }
    
        return cache()->remember("posts.{$slug}", now()->addSeconds(5) , function () use ($path) {
            return file_get_contents($path); 
        });
    }
}
