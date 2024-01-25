<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post extends Model
{
    use HasFactory;

    public $title ; 

    public $date ; 

    public $excerpt;

    public $body ; 
    
    public $slug ; 

    public function __construct($title , $date , $excerpt, $body, $slug )
    {
        $this->title = $title;
        $this->date = $date;
        $this->excerpt = $excerpt;
        $this->body = $body;
        $this->slug = $slug;
        
    }

    public static function alll()
    {
        $files=  File::files(resource_path("posts/"));
        return cache()->remember('posts.all', '2', function() use($files){
            return  collect($files)
            ->map(function ($file){
                    $documents= YamlFrontMatter::parseFile($file);
                    return new Post(
                        $documents->matter('title'),
                        $documents->matter('date'),
                        $documents->matter('excerpt'),
                        $documents->body(),
                        $documents->matter('slug'),
                    );
                })
                ->sortByDesc('date');
        } );

            
    }

    public static function find($slug)
    {
        $posts = static::alll();
        return $posts->firstWhere('slug', $slug);
    }

    public static function findOrFail($slug)
    {
        $post = static::find($slug);

        if( ! $post)
        {
            throw new ModelNotFoundException();
        }
        return $post;

    }

}
