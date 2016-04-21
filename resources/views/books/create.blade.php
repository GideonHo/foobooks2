@extends('layouts.master')

@section('title')
    Add a new book
@stop

@section('content')

  <h1>Add a new book</h1>

  <form method='POST' action='/books/create'>
      {{ csrf_field() }}
      Title: <input type='text' name='title'><br>
        @if($errors->get('title'))
            <ul>
                @foreach($errors->get('title') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
      Name: <input type='text' name='name'><br>
      Username: <input type='text' name='username'><br>
      Password: <input type='text' name='password'><br>
      <input type='submit' value='Submit'>
  </form>

  @if(count($errors) > 0)
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
  @endif

@stop