<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Unsubscribe
 * @package App
 */
class Subscriber extends Model
{
    protected $table = 'unsubscribers';

    protected $fillable = [
        'user_id',
        'email',
        'integration',
        'hook'
    ];

    /**
     * Get the user record associated with the unsibscriber.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }
}