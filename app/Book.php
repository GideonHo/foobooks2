<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
	protected $fillable = ['title', 'author_id', 'user_id', 'published', 'cover', 'purchase_link'];

	public function author() {
        # Book belongs to Author
        # Define an inverse one-to-many relationship.
        return $this->belongsTo('\App\Author');
    }

    public function user() {
        return $this->belongsTo('\App\User');
    }

    public function tags(){
	    # With timetsamps() will ensure the pivot table has its created_at/updated_at fields automatically maintained
	    return $this->belongsToMany('\App\Tag')->withTimestamps();
	}

    public static function getAllBooksWithAuthors() {
        return \App\Book::with('author')->orderBy('id','desc')->get();
    }

}
