<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\UserActionLog;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserActionLogTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCheckout()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $book = factory(Book::class)->create(['status' => 'AVAILABLE']);

        $response = $this
        ->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->post('/api/logs', [
            'user_id' => $user->id,
            'isbn' => $book->isbn,
            'action' => 'CHECKOUT'
        ]);

        $response->assertStatus(201);
        
        $bookUpdated = Book::find($book->id);
        $this->assertEquals('CHECKED_OUT', $bookUpdated->status);
    }

    public function testCheckin()
    {
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        $book = factory(Book::class)->create(['status' => 'CHECKED_OUT']);
        factory(UserActionLog::class)->create([
            'action' => 'CHECKOUT',
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);

        $response = $this
        ->withHeaders(['Authorization' => 'Bearer ' . $token])
        ->post('/api/logs', [
            'user_id' => $user->id,
            'isbn' => $book->isbn,
            'action' => 'CHECKIN'
        ]);
        
        $response->assertStatus(201);

        $bookUpdated = Book::find($book->id);
        $this->assertEquals('AVAILABLE', $bookUpdated->status);
    }
}
