@extends('layout')

@section('content')
    <article>
        <p>
            <h1> {{$post->title}} </h1>
            <div> {!!$post->body!!} </div>
        </p>
    </article>

    <a href="/">Go Back</a>
@endsection