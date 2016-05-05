@extends('layouts.master')

@section('head')
    <link href='/css/books_index.css' rel='stylesheet'>
@stop

@section('title')
    All books
@stop

@section('content')

    <h1>All the books</h1>

    @if(sizeof($books) == 0)
        You have not added any books, you can <a href='/books/create'>add a book now to get started</a>.
    @else
        <div id='books' class='cf'>
            @foreach($books as $book)
                <section class='book'>

                    <a href='/books/show/{{$book->id}}'><h2 class='truncate'>{{ $book->title }}</h2></a>

                    <h3 class='truncate'>{{ $book->author->first_name }} {{ $book->author->last_name }}</h3>

                    <img class='cover' src='{{ $book->cover }}' alt='Cover for {{$book->title}}'>

                    <div class='tags'>
                        @foreach($book->tags as $tag)
                            <div class='tag'>{{ $tag->name }}</div>
                        @endforeach
                    </div>

                    <a href='/books/edit/{{$book->id}}'><i class='fa fa-pencil'></i> Edit</a><br>
                    <a href='/books/show/{{$book->id}}'><i class='fa fa-eye'></i> View</a><br>
                    <a href='/books/confirm-delete/{{$book->id}}'>Delete</a><br>

                </section>
            @endforeach
        </div>
    @endif

@stop