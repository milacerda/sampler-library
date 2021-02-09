<?php

use App\Models\Book;
use App\Models\UserActionLog;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(Book::class, 30)->create()->each(function ($book) {
            if ($book->status === 'CHECKED_OUT') {
                factory(UserActionLog::class)->create([
                    'book_id' => $book->id,
                    'action' => 'CHECKOUT'
                ]);
            } else {
                factory(UserActionLog::class)->create([
                    'book_id' => $book->id,
                    'action' => 'CHECKIN'
                ]);

            }
        });
        
    }
}
