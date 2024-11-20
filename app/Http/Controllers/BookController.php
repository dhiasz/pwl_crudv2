<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Bookshelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function edit(string $id){
        $data['bookshelves'] = Bookshelf::get();
        $data['book'] = Book::findOrFail($id);
        return view('books.edit', $data);
    }

    public function store(Request $request){
        // Validate incoming request
        $validation = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'year' => 'required|max:255',
            'publisher' => 'required|max:255',
            'city' => 'required|max:255',
            'cover' => 'required|image|max:2048', // Ensure it's an image
            'bookshelf_id' => 'required',
        ]);

        // Handle cover file upload
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->storeAs(
                'public/cover_buku',
                'cover_buku_' . time() . '.' . $request->file('cover')->extension()
            );
            $validation['cover'] = basename($path); // Save the filename
        }

        // Create the book
        Book::create($validation);

        // Redirect with success notification
        $notification = array( 
            'message' => 'Data buku berhasil ditambahkan', 
            'alert-type' => 'success' 
        );
        return redirect()->route('book')->with($notification);
    }

    public function update(Request $request, string $id){

        $book = Book::findOrFail($id);

        // Validate incoming request
        $validation = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'year' => 'required|max:255',
            'publisher' => 'required|max:255',
            'city' => 'required|max:255',
            'cover' => 'nullable|image|max:2048', // Make cover optional for update
            'bookshelf_id' => 'required',
        ]);

        if ($request->hasFile('cover')) {
            if ($book->cover != null) {
                Storage::delete('public/cover_buku/' . $book->cover);
            }

            $path = $request->file('cover')->storeAs(
                'public/cover_buku',
                'cover_buku_' . time() . '.' . $request->file('cover')->extension()
            );
            $validation['cover'] = basename($path);
        }
        $book->update($validation);

        $notification = array( 
            'message' => 'Data buku berhasil diperbarui', 
            'alert-type' => 'success' 
        );
        return redirect()->route('book')->with($notification);
    }

    public function destroy(Request $request, string $id){

        $book = Book::findOrFail($id);

        Storage::delete('public/cover_buku/' . $book->cover);
        $book->delete();
        
                $notification = array( 
                    'message' => 'Data buku berhasil diperbarui', 
                    'alert-type' => 'success' 
                );
                return redirect()->route('book')->with($notification);
        
    }
}



