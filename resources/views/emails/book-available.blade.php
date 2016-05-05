<h1>Good news {{ $user->name }}!</h1>

<p>The following book is now available on Foobooks.</p>

<div>
    <h2>{{ $book->title }}</h2>
    <h3>By {{ $book->author->first_name }} {{ $book->author->last_name }}</h3>
    <a href='{{ Config::get('app.url').'/books/show/'.$book->id}}'>View now...</a>
</div>

<p>
From,<br>
Team Foobooks
</p>