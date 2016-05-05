# /resources/views/welcome/index.blade.php
@extends('layouts.master')

@section('title')
    Welcome to Foobooks
@stop

@section('content')
    <p>
        Welcome to Foobooks, a personal book organizer.
        To get started <a href='/login'>log in</a> or <a href='/register'>register</a>.
    </p>
@stop