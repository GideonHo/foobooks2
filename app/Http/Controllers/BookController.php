<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Book;

class BookController extends Controller {

    /**
    * Responds to requests to GET /books
    */
    public function getIndex() {
        $books = \App\Book::all();
        return view('books.index')->with('books', $books);
    }

    /**
     * Responds to requests to GET /books/show/{id}
     */
    public function getShow($title = null) {
        //return 'Show book: '.$title;
        return view('books.show')->with('title', $title);
    }

    /**
     * Responds to requests to GET /books/create
     */
    public function getCreate() {
        return view('books.create');
    }

    /**
     * Responds to requests to POST /books/create
     */
    public function postCreate(Request $request) {
        // Validate the request data
        $this->validate($request, [
            'author'        => 'required|min:5',
            'published'     => 'required|min:4',
            'cover'         => 'required|url',
            'purchase_link' => 'required|url',
        ]);

        # If the code makes it here, you can assume the validation passed
        //$book = new \App\Book();
        //$book->title = $request->title;
        //$book->author = $request->author;
        //$book->published = $request->published;
        //$book->cover = $request->cover;
        //$book->purchase_link = $request->purchase_link;
        //$book->save();

        # Mass Assignment
        $data = $request->only('title','author','published','cover','purchase_link');
        
        # One way to add the data
        #$book = new \App\Book($data);
        #$book->save();
        
        # An alternative way to add the data
        \App\Book::create($data);

        \Session::flash('flash_message','Your book has been added.');

        // Then you'd give the user some sort of confirmation:
        return redirect('/books');
    }

    public function getEdit($id){
        $book = \App\Book::find($id);
        return view('books.edit')->with('book',$book);
    }

    public function postEdit(Request $request){
        $book = \App\Book::find($request->id);

        $book->title = $request->title;
        $book->author = $request->author;
        $book->cover = $request->cover;
        $book->published = $request->published;
        $book->purchase_link = $request->purchase_link;

        $book->save();

        return 'Book was saved';
        #\Session::flash('flash_message','Your changes were saved');
        #return redirect('/books/edit/'.$request->id);
    }
}