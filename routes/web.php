<?php

use App\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Spatie\YamlFrontMatter\YamlFrontMatter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $posts = [];
    $files=  File::files(resource_path("posts/"));

    foreach ($files as $file)
    {
        $documents= YamlFrontMatter::parseFile($file);

        $posts[] = new Post(
            $documents->matter('title'),
            $documents->matter('date'),
            $documents->body(),
        );
        
    }

    dd($posts[0]->body);

    return view('posts', ['posts' => $posts[1]]);

    // $posts = Post::alll();
    // // dd($posts);
    // // dd($posts[0]->getContents());
    // return view('posts' , ['posts' => $posts]);
});


Route::get('/posts/{post}', function ($slug) {

    // find  a post with it's slug and send it to the view

    $post = Post::find($slug);
    return view ('post' , ['post' => $post]);

    // return view ('post' , ['post' => Post::find($slug)]);
})->where('post', '[A-z_\-]+');