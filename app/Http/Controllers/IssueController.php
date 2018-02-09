<?php

namespace App\Http\Controllers;

/**
 * Class IssueController
 * @package App\Http\Controllers
 */
class IssueController extends Controller
{
    public function created()
    {
        return Response()->json(['ok' => true, 'IssueController::created called.']);
    }

    public function updated()
    {
        return Response()->json(['ok' => true, 'IssueController::updated called.']);
    }

    public function deleted()
    {
        return Response()->json(['ok' => true, 'IssueController::deleted called.']);
    }

    public function worklogChanged()
    {
        return Response()->json(['ok' => true, 'IssueController::worklogChanged called.']);
    }
}