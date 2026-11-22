<?php

namespace App\Http\Controllers;

use App\Models\God;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function index(Request $request): View
    {
        $friendIds = $request->user()->friends()->pluck('users.id');

        $gods = God::query()
            ->with('user')
            ->whereIn('user_id', $friendIds)
            ->latest('created_at')
            ->latest('id')
            ->get();

        return view('feed.index', compact('gods'));
    }
}
