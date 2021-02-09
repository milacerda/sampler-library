<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = User::all();
    return response()->json($users, 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\UserRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(UserRequest $request)
  {
    $data = $request->except('_token');
    $data['password'] = Hash::make($data['password']);
    return response()->json(User::create($data), 201);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    if (User::where('id', $id)->exists()) {
      $user = User::where('id', $id)->get();
      return response($user, 200);
    } else {
      return response([
        "message" => "User not found."
      ], 404);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UserRequest  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UserRequest $request, $id)
  {
    if (User::where('id', $id)->exists()) {

      $user = User::find($id);
      $user->name = $request->name;
      $user->email = $request->email;
      $user->password = Hash::make($request->password);
      $user->date_of_birth = $request->date_of_birth;
      $user->save();

      return response([
        "message" => "User updated successfully!"
      ], 200);
    }

    return response([
      "message" => "User not found."
    ], 404);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if (User::where('id', $id)->exists()) {
      $user = User::find($id);
      $user->delete();

      return response([
        "message" => "User deleted successfully!"
      ], 200);
    } else {
      return response([
        "message" => "User not found."
      ], 404);
    }
  }
}
