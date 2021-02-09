<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserActionLog extends Model
{
    use Notifiable;

    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action',
        'book_id',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Get the user that did the action.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'foreign_key');
    }

    /**
     * Get the book in the action.
     */
    public function book()
    {
        return $this->belongsTo('App\Book', 'foreign_key');
    }
}
