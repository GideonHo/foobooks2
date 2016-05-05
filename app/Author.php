<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public function books() {
        # Author has many Books
        # Define a one-to-many relationship.
        return $this->hasMany('\App\Book');
    }

    public static function authorsForDropdown() {
        # Get all authors
        $authors = \App\Author::orderBy('last_name','ASC')->get();
        $authors_for_dropdown = [];
        $authors_for_dropdown[0] = 'Choose an author...';
        foreach($authors as $author) {
            $authors_for_dropdown[$author->id] = $author->last_name.', '.$author->first_name;
        }
        return $authors_for_dropdown;
    }
}
