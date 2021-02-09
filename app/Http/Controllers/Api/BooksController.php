<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Http\Requests\BookRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class BooksController extends BaseController
{

  /**
   * List all the books
   * 
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $books = Book::paginate(5);
    return response($books, 200);
  }

  /**
   * List specific book by it's id
   * 
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $book = Book::find($id);
    if (!empty($book)) {
      return response()->json($book, 200);
    } else {
      return response()->json([
        "message" => "Book not found."
      ], 404);
    }
  }

  /**
   * Creating a new book
   * 
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(BookRequest $request)
  {
    return response()->json(Book::create($request->validated()), 201);
  }

  /**
   * Editing the book
   * 
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(BookRequest $request, $id)
  {
    if (Book::where('id', $id)->exists()) {

      $book = Book::find($id);
      $book->title = $request->title;
      $book->isbn = $request->isbn;
      $book->publication_date = $request->publication_date;
      $book->status = $request->status;
      $book->save();

      return response()->json([
        "message" => "Book updated successfully!"
      ], 200);

    }

    return response()->json([
      "message" => "Book not found."
    ], 404);
    
  }

  /**
   * Deleting the book
   * 
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if (Book::where('id', $id)->exists()) {
      $book = Book::find($id);
      $book->delete();

      return response()->json([
        "message" => "Book deleted successfully!"
      ], 200);
    } else {
      return response()->json([
        "message" => "Book not found."
      ], 404);
    }
  }

}
