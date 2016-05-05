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
    function getIndex() {
        //$books = \App\Book::getAllBooksWithAuthors();
        # Get all the books "owned" by the current logged in users
        # Sort in descending order by id
        $books = \App\Book::where('user_id','=',\Auth::id())->orderBy('id','DESC')->get();
        return view('books.index')->with('books', $books);
    }

    /**
     * Responds to requests to GET /books/show/{id}
     */
    public function getShow($title = null) {
        $book = \App\Book::find($title);
        if(is_null($book)) {
            \Session::flash('message','Book not found');
            return redirect('/books');
        }
        # Fetch similar books from the Goole Books API
        $googleBooks = new \App\Libraries\GoogleBooks();
        $author_name = $book->author->first_name.' '.$book->author->last_name;
        $otherBooksByThisAuthor = $googleBooks->getOtherBooksByAuthor($author_name);
        return view('books.show',[
            'book' => $book,
            'otherBooksByThisAuthor' => $otherBooksByThisAuthor,
            'title' => $title
        ]);
        //return 'Show book: '.$title;
        //return view('books.show')->with('title', $title);
    }

    /**
     * Responds to requests to GET /books/create
     */
    public function getCreate() {

        if(!\Auth::check()){
            \Session::flash('message','You have to logged in to create a new books.');
            return redirect('/');
        }

        $authors_for_dropdown = \App\Author::authorsForDropdown();
        $tags_for_checkboxes = \App\Tag::getTagsForCheckboxes();


        return view('books.create')->with([
            'authors_for_dropdown' => $authors_for_dropdown,
            'tags_for_checkboxes' => $tags_for_checkboxes,
        ]);

    }

    /**
     * Responds to requests to POST /books/create
     */
    public function postCreate(Request $request) {
        $messages = [
            'not_in' => 'You have to choose an author.',
        ];

        $this->validate($request,[
            'title' => 'required|min:3',
            'author_id' => 'not_in:0',
            'published' => 'required|min:4',
            'cover' => 'required|url',
            'purchase_link' => 'required|url'
        ],$messages);
        
        # Add the book (this was how we did it pre-mass assignment)
        // $book = new \App\Book();
        // $book->title = $request->title;
        // $book->author = $request->author;
        // $book->published = $request->published;
        // $book->cover = $request->cover;
        // $book->purchase_link = $request->purchase_link;
        // $book->save();
        
        # Mass Assignment
        $data = $request->only('title','author_id','published','cover','purchase_link');
        $data['user_id'] = \Auth::id();
        
        # One way to add the data
        #$book = new \App\Book($data);
        #$book->save();
        
        # An alternative way to add the data
        $book = \App\Book::create($data);
        
        # Save Tags
        //$tags = ($request->tags) ?: [];
        //$book->tags()->sync($tags);
        //$book->save();
        
        \Session::flash('message','Your book was added');
        return redirect('/books');
    }

    public function getEdit($id = null){
        $book = \App\Book::with('tags')->find($id);
        $authors_for_dropdown = \App\Author::authorsForDropdown();
        $tags_for_checkboxes = \App\Tag::getTagsForCheckboxes();

        $tags_for_this_book = [];
        foreach($book->tags as $tag) {
            $tags_for_this_book[] = $tag->name;
        }

        # Make sure $authors_for_dropdown is passed to the view
        return view('books.edit')->with([
            'book' => $book,
            'authors_for_dropdown' => $authors_for_dropdown,
            'tags_for_checkboxes' => $tags_for_checkboxes,
            'tags_for_this_book' => $tags_for_this_book,
        ]);
    }

    public function postEdit(Request $request){
        $book = \App\Book::find($request->id);

        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->cover = $request->cover;
        $book->published = $request->published;
        $book->purchase_link = $request->purchase_link;

    $book->save();

    # If there were tags selected...
    if($request->tags) {
        $tags = $request->tags;
    }
    # If there were no tags selected (i.e. no tags in the request)
    # default to an empty array of tags
    else {
        $tags = [];
    }

        return 'Book was saved';
        #\Session::flash('flash_message','Your changes were saved');
        #return redirect('/books/edit/'.$request->id);
    }

    public function getConfirmDelete($id) {

        $book = \App\Book::find($id);

        return view('books.delete')->with('book', $book);
    }

    public function getDoDelete($id) {

        # Get the book to be deleted
        $book = \App\Book::find($id);

        if(is_null($book)) {
            \Session::flash('message','Book not found.');
            return redirect('\books');
        }

        # First remove any tags associated with this book
        if($book->tags()) {
            $book->tags()->detach();
        }

        # Then delete the book
        $book->delete();

        # Done
        \Session::flash('message',$book->title.' was deleted.');
        return redirect('/books');

    }

}