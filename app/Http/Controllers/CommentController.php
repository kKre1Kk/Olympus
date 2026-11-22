<?php

namespace App\Http\Controllers;

use App\Models\God;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, God $god): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $god->comments()->create([
            'user_id' => $request->user()->getKey(),
            'body' => $data['body'],
        ]);

        return redirect()->route('gods.show', $god);
    }
}
