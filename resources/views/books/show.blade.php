@extends('layouts.master')

@section('title')
    Show book
@stop

{{--
This `head` section will be yielded right before the closing </head> tag.
Use it to add specific things that *this* View needs in the head,
such as a page specific stylesheets.
--}}

@section('head')
    <link href="/css/show.css" type='text/css' rel='stylesheet'>
@stop


@section('content')

    <h1 class='truncate'>{{ $book->title }}</h1>

    <h2 class='truncate'>{{ $book->author->first_name }} {{ $book->author->last_name }}</h2>

    <img class='cover' src='{{ $book->cover }}' alt='Cover for {{$book->title}}'>

    <div class='tags'>
        @foreach($book->tags as $tag)
            <div class='tag'>{{ $tag->name }}</div>
        @endforeach
    </div>

    <h3>Other books by {{ $book->author->first_name }} {{ $book->author->last_name }}</h3>
    @foreach($otherBooksByThisAuthor as $otherBook)
        <a href='{{$otherBook['volumeInfo']['infoLink']}}'>{{ $otherBook['volumeInfo']['title'] }}</a><br>
    @endforeach

@stop

{{--
This `body` section will be yielded right before the closing </body> tag.
Use it to add specific things that *this* View needs at the end of the body,
such as a page specific JavaScript files.
--}}

@section('body')
    <script src="/js/books/show.js"></script>
@stop