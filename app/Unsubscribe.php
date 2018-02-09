<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Unsubscribe
 * @package App
 */
class Unsubscribe extends Model
{
    protected $table = 'unsubscribers';

    protected $fillable = [
        'email',
        'integration',
        'hook'
    ];
}