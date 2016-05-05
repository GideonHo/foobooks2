<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Book;
use App\Author;

class PracticeController extends Controller {

    public function getEx25() {
        # Instantiate a new object from the library we're using
        $client = new \Google_Client();
        # Put the name of your application here
        $client->setApplicationName("Foobooks");
        # Paste in your API Key
        $client->setDeveloperKey(\Config::get('apis.google_api_key'));
        //$client->setDeveloperKey('AIzaSyCOTr6as0kEYPRaf44BxEp_LR7EXEN3hRc');
        # This library can work with multiple different Google APIs, so here we're specifying we're using the Books API
        $service = new \Google_Service_Books($client);
        # Create an array of params for the query; these are the same params we used in the basic example above
        $optParams = [
            'q' => 'author:Maya Angelou',
            'maxResults' => 5,
        ];
        # Each API provides resources and methods, usually in a chain. These can be accessed from the service object in the form $service->resource->method(args)
        $books = $service->volumes->listVolumes('', $optParams);
        # Output our results to test and make sure it worked
        dump($books);
        foreach($books['items'] as $book) {
            echo $book['volumeInfo']['title'].'<br>';
        }
    }

    public function getEx24() {
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=author:maya%20angelou&langRestrict=en&maxResults=5&key=AIzaSyCOTr6as0kEYPRaf44BxEp_LR7EXEN3hRc";
        $jsonStringResults = file_get_contents($apiUrl);
        $data = json_decode($jsonStringResults, true);
        # Show all the data
        dump($data);
        # Loop through the data printing just the title for each book
        foreach($data['items'] as $book) {
            echo $book['volumeInfo']['title'].'<br>';
        }
    }

    public function getEx23(){
        # Get the current logged in user
        $user = \Auth::user();

        # If user is not logged in, make them log in
        if(!$user) return redirect()->guest('login');

        # Grab any book, just to use as an example
        $book = \App\Book::findOrFail(1);

        # Create an array of data, which will be passed/available in the view
        $data = array(
            'user' => $user,
            'book' => $book,
        );

        \Mail::send('emails.book-available', $data, function($message) use ($user,$book) {

            $recipient_email = $user->email;
            $recipient_name  = $user->name;
            $subject  = 'The book '.$book->title.' is now available on Foobooks';

            $message->to($recipient_email, $recipient_name)->subject($subject);

        });

        echo 'HTML email sent.';
    }

    public function getEx22() {
        \Mail::send([], [], function ($message) {
          $message->to('gideon_ho@hotmail.com')
            ->subject('Hello World')
            ->setBody('This is a test message; Testing 123.');
        });
        return 'Basic, plain text email sent.';
    }

    public function getEx21() {
        $books = \App\Book::with('tags')->get();
        foreach($books as $book) {
            echo $book->title.'<br>';
            foreach($book->tags as $tag) {
                echo $tag->name.'<br>';
            }
            echo '<br>';
        }
    }

    public function getEx20(){
        $book = \App\Book::where('title','=','The Great Gatsby')->first();

        dump($book->tags);

        foreach($book->tags as $tag){
            echo $tag->name.'<br>';
        }
    }



    public function getEx19() {
        # Create an author we can associate a book with...
        $author = new \App\Author;
        $author->first_name = 'J.K';
        $author->last_name = 'Rowling';
        $author->bio_url = 'https://en.wikipedia.org/wiki/J._K._Rowling';
        $author->birth_year = '1965';
        $author->save();
        dump($author->toArray());

        # Create a new books
        $book = new \App\Book;
        $book->title = "Harry Potter and the Philosopher's Stone";
        $book->published = 1997;
        $book->cover = 'http://prodimage.images-bn.com/pimages/9781582348254_p0_v1_s118x184.jpg';
        $book->purchase_link = 'http://www.barnesandnoble.com/w/harrius-potter-et-philosophi-lapis-j-k-rowling/1102662272?ean=9781582348254';
        $book->author()->associate($author); # <--- Associate the author with this book
        #$book->author_id = $author->id; # <--- Associate the author with this book
        $book->save();
        dump($book->toArray());
    }

    public function getEx18() {
        # Eager loading
        $books = \App\Book::with('author')->get();
        foreach($books as $book) {
            echo $book->author->first_name.'<br>';
        }
    }

    public function getEx17() {
        $books = \App\Book::get();
        # Because "author" was not eagerly loaded, it will be dynamically fetched
        # for each iteration in this loop.
        foreach($books as $book) {
            echo $book->author->first_name.'<br>';
        }
    }

    public function getEx16(){
        $books = \App\Book::orderBy('id','desc')->get();
        dump($books->toArray());

        $book = $books->first();
        $book_last = $books->last();
        echo $book['title'].'<br>';
        echo $book_last->title;
    }

    public function getEx15(){
        $books = \App\Book::all();
        $this->printBooks($books);

        foreach($books as $book){
            echo $book['title'].'<br>';
        }

        foreach($books as $book){
            echo $book->title.'<br>';
        }
    }

    private function printBooks($books) {
        foreach($books as $book) {
            echo 'id:'.$book->id.' title: '.$book->title.'<br>';
        }
    }

    // Remove any books by the author “J.K. Rowling”.
    public function getEx14(){
        $books = \App\Book::where('title','LIKE','%Great Gatsby%');   
        $books->delete();
        return 'Books titled like Great Gatsby has been deleted.';
    }


    // Find any books by the author Bell Hooks and update the author name to be bell hooks (lowercase).
    public function getEx13(){
        $book = \App\Book::where('author','=','sylvia plath')->first();
        if($book){
            $book->author = 'sylvia plath';
            $book->save();
            return 'Author is now: '.$book->author.'<br>';
        }
        else{
            return 'No match is found.';
        }
    }


    // Retrieve all the books in descending order according to published date.
    public function getEx12(){
        $books = \App\Book::orderby('published','desc')->get();
        foreach($books as $book){
            echo $book->title.'<br>';
        }
    }


    // Retrieve all the books in alphabetical order by title.
    public function getEx11(){
        $books = \App\Book::orderby('title','asc')->get();
        foreach($books as $book){
            echo $book->title.'<br>';
        }
    }

    // Retrieve all the books published after 1950.
    public function getEx10(){
        $books = \App\Book::where('published','>',1950)->get();
        foreach($books as $book){
            echo $book->title.'<br>';
        }
    }

    // Show the last 5 books that were added to the books table.
    public function getEx9(){
        $books = \App\Book::orderby('published','desc')->get()->take(5);
        foreach($books as $book){
            echo $book->title.'<br>';
        }
    }

    /* Comparing get() and all() */
    public function getEx8() {
        # Get all the books
        $books = \App\Book::all();
        echo 'All books retrieved using the all() method.<br>';
        foreach($books as $book){
            echo $book->title.'<br>';
        }

        # get() without any query constraints is the equivalent of all()
        echo '<br>All books retrieved using the get() method.<br>';
        $books = \App\Book::get();
        foreach($books as $book){
            echo $book->title.'<br>';
        }
    }

    // Demonstrate deleting with the Book Model //
    public function getEx7(){
        $book = \App\Book::where('title','LIKE','%The%')->first();

        if($book){
            $book->delete();
            return 'Book found and deleted.';
        }
        else{
            return 'No book found!';
        }

        // Delete all books with Title similar to Harry
        //$books = \App\Book::where('title','LIKE','%Harry%')->get();
        //foreach($books as $book){
        //    $book->delete();
        //}
    }

    /* Demonstrate updating with the Book Model */
    public function getEx6() {
        # First get a book to update
        $book = \App\Book::where('author', 'LIKE', '%Scott%')->first();
        # If we found the book, update it
        if($book) {
            # Give it a different title
            $book->title = 'The Really Great Gatsby';
            # Save the changes
            $book->save();
            echo "Update complete; check the database to see if your update worked...";
        }
        else {
            echo "Book not found, can't update.";
        }
    }

    // Demonstrate reading with the Book Model //
    public function getEx5() {
        $books = \App\Book::all();
        if(!$books->IsEmpty()){
            foreach($books as $book){
                echo $book->title.'<br>';
            }
        }
        else{
            echo 'No books found';
        }
    }

    /* Demonstrate creation with the Book Model */
    public function getEx4() {
        # Instantiate a new Book Model object
        $book = new \App\Book();
        # Set the parameters
        # Note how each parameter corresponds to a field in the table
        $book->title = 'Harry Potter';
        $book->author = 'J.K. Rowling';
        $book->published = 1997;
        $book->cover = 'http://prodimage.images-bn.com/pimages/9780590353427_p0_v1_s484x700.jpg';
        $book->purchase_link = 'http://www.barnesandnoble.com/w/harry-potter-and-the-sorcerers-stone-j-k-rowling/1100036321?ean=9780590353427';
        # Invoke the Eloquent save() method
        # This will generate a new row in the `books` table, with the above data
        $book->save();
        return 'Added: '.$book->title;
    }

    public function getEx3() {
        # Use the QueryBuilder to get all the books where author is like "%Scott%"
        $books = \DB::table('books')->where('author', 'LIKE', '%Scott%')->get();
        # Output the results
        foreach($books as $book) {
            echo $book->title.'<br>';
        }
    }

    public function getEx2() {
        // Use the QueryBuilder to insert a new row into the books table
        // i.e. create a new book
        \DB::table('books')->insert([
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'published' => 1925,
            'cover' => 'http://img2.imagesbn.com/p/9780743273565_p0_v4_s114x166.JPG',
            'purchase_link' => 'http://www.barnesandnoble.com/w/the-great-gatsby-francis-scott-fitzgerald/1116668135?ean=9780743273565',
        ]);
        return 'Added book.';
    }

    public function getEx1() {
        return 'Added book.';
        // Use the QueryBuilder to get all the books
        //$books = \DB::table('books')->get(); // Remember to put the '/' in front of DB

        // Output the results
        //foreach ($books as $book) {
        //    echo $book->title.'<br>';
        //}
    }

}