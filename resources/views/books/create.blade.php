@extends('layouts.master')

@section('title')
    Add a new book
@stop

@section('content')
    <h1>Add a new book</h1>

    <form method='POST' action='/books/create'>

        {{ csrf_field() }}

        <div class='form-group'>
           <label>Title:</label>
            <input
                type='text'
                id='title'
                name='title'
                value='{{ old('title','Green Eggs and Ham') }}'
            >
           <div class='error'>{{ $errors->first('title') }}</div>
        </div>

        <div class='form-group'>
            <label for='author_id'>* Author:</label>
            <select id='author_id' name='author_id'>
                @foreach($authors_for_dropdown as $author_id => $author_name)
                     <option value='{{$author_id}}'>
                         {{$author_name}}
                     </option>
                 @endforeach
            </select>
            <div class='error'>{{ $errors->first('author_id') }}</div>
        </div>

        <div class='form-group'>
           <label>Published Year (YYYY):</label>
           <input
               type='text'
               id='published'
               name='published'
               value='{{ old('published','1960') }}'
           >
           <div class='error'>{{ $errors->first('published') }}</div>
        </div>

        <div class='form-group'>
           <label>URL of cover image:</label>
           <input
               type='text'
               id='cover'
               name='cover'
               value='{{ old('cover','http://prodimage.images-bn.com/pimages/9780394800165_p0_v4_s192x300.jpg') }}'
           >
           <div class='error'>{{ $errors->first('cover') }}</div>
        </div>

        <div class='form-group'>
           <label>URL to purchase this book:</label>
           <input
               type='text'
               id='purchase_link'
               name='purchase_link'
               value='{{ old('purchase_link','http://www.barnesandnoble.com/w/green-eggs-and-ham-dr-seuss/1100170349?ean=9780394800165') }}'
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
                        {{ $tag_name }}
                    >
                    {{$tag_name}}
                    </label>
                @endforeach
            </fieldset>
        </div>

        <div class='form-instructions'>
            All fields are required
        </div>

        <button type="submit" class="btn btn-primary">Add book</button>

        {{--
        <ul class=''>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        --}}

        <div class='error'>
            @if(count($errors) > 0)
                Please correct the errors above and try again.
            @endif
        </div>
    </form>
@stop