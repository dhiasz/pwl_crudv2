<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Bookshelf;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(){
        $data['books'] = Book::with('bookshelf')->get();
        return view('books.index', $data);
    }

    public function create(){
        $data['bookshelves'] = Bookshelf::get();
        return view('books.create', $data);
    }

    public function store(Request $request){
        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'year' => $request->year,
            'publisher' => $request->publisher,
            'city' => $request->city,
            'cover' => $request->cover,
            'bookshelf_id' => $request->bookshelf_id
        ]);
        $notification = array( 
            'message' => 'Data buku berhasil dihapus', 
            'alert-type' => 'success' 
        ); 
        
        return redirect()->route('book')->with($notification); 
    }
}
