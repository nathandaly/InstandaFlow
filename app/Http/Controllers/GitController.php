<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class GitController
 * @package App\Http\Controllers
 */
class GitController extends Controller
{
    /**
     * @return void
     */
    public function push()
    {
        echo 'Code pushed.'; exit;
    }
}