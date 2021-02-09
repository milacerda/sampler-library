<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\UserActionLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserActionLogsRequest;

class UserActionLogsController extends Controller
{
   /**
   * List all logs
   * 
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $logs = UserActionLog::paginate(5);
    return response($logs, 200);
  }

  /**
   * List specific log by it's id
   * 
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $log = UserActionLog::find($id);
    if (!empty($log)) {
      return response()->json($log, 200);
    } else {
      return response()->json([
        "message" => "Log not found."
      ], 404);
    }
  }

  /**
   * Creating a new log
   * 
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(UserActionLogsRequest $request)
  {
    $data = $request->validated();

    $book = Book::where(['isbn' => $data['isbn']])->first();

    if ($data['action'] == 'CHECKOUT') {
      $status = 'CHECKED_OUT';

      if ($book->status != 'AVAILABLE') {
        return response()->json(['message' => 'This book is not available.'], 400);
      }

    }
  
    if ($data['action'] == 'CHECKIN') {
      $status = 'AVAILABLE';

      if ($book->status == 'AVAILABLE') {
        return response()->json(['message' => 'This book has already been returned.'], 400);
      }

      $log = UserActionLog::where([
          'book_id' => $book->id,
          'action' => 'CHECKOUT',
        ])
        ->orderBy('created_at','DESC')
        ->limit(1)
        ->first();

      if ($log->user_id !== $data['user_id']) {
        return response()->json(['message' => 'This book has not been checked out by you.'], 400);
      }
    }

    $newLog = UserActionLog::create([
      'book_id' => $book->id,
      'action' => $data['action'],
      'user_id' => $data['user_id']
    ]);

    $book->status = $status;
    $book->save();
        
    return response()->json($newLog, 201);
  }

  /**
   * Deleting the UserActionLog
   * 
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if (UserActionLog::where('id', $id)->exists()) {
      $Log = UserActionLog::find($id);
      $Log->delete();

      return response()->json([
        "message" => "Log deleted successfully!"
      ], 200);
    } else {
      return response()->json([
        "message" => "Log not found."
      ], 404);
    }
  }

}
