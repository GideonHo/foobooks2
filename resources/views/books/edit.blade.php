@extends('layouts.master')

@section('title')
    Edit book {{ $book->title }}
@stop

@section('content')

    <h1>Edit book {{ $book->title }}</h1>

    <form method='POST' action='/books/edit'>

        <input type='hidden' name='id' value='{{$book->id}}'>

        {{ csrf_field() }}

        <div class='form-group'>
           <label>Title:</label>
            <input
                type='text'
                id='title'
                name='title'
                value='{{ $book->title }}'
            >
           <div class='error'>{{ $errors->first('title') }}</div>
        </div>

        <div class='form-group'>
            <label for='author_id'>* Author:</label>
            <select id='author_id' name='author_id'>
                @foreach($authors_for_dropdown as $author_id => $author_name)
                     <option value='{{$author_id}}' {{ ($book->author_id == $author_id) ? 'SELECTED' : '' }}>
                         {{$author_name}}
                     </option>
                 @endforeach
            </select>
            <div class='error'>{{ $errors->first('author') }}</div>
        </div>

        <div class='form-group'>
           <label>Published Year (YYYY):</label>
           <input
               type='text'
               id='published'
               name='published'
               value='{{ $book->published }}'
           >
           <div class='error'>{{ $errors->first('published') }}</div>
        </div>

        <div class='form-group'>
           <label>URL of cover image:</label>
           <input
               type='text'
               id='cover'
               name='cover'
               value='{{ $book->cover }}'
           >
           <div class='error'>{{ $errors->first('cover') }}</div>
        </div>

        <div class='form-group'>
           <label>URL to purchase this book:</label>
           <input
               type='text'
               id='purchase_link'
               name='purchase_link'
               value='{{ $book->purchase_link }}'
           >
           <div class='error'>{{ $errors->first('purchase_link') }}</div>
        </div>

        <div class='form-group'>
            <fieldset>
                <legend>Tags:</legend>
                @foreach($tags_for_checkboxes as $tag_id => $tag_name)
                    <label>
                    <input
                        type='checkbox'
                        value='{{ $tag_id }}'
                        name='tags[]'
                        {{ (in_array($tag_name, $tags_for_this_book)) ? 'CHECKED' : '' }}
                    >
                    {{$tag_name}}
                    </label>
                @endforeach
            </fieldset>
        </div>

        <div class='form-instructions'>
            All fields are required
        </div>

        <button type="submit" class="btn btn-primary">Save changes</button>

        <div class='error'>
            @if(count($errors) > 0)
                Please correct the errors above and try again.
            @endif
        </div>

    </form>

@stop