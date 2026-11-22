<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 422, 'Нельзя добавить себя в друзья.');

        DB::transaction(function () use ($request, $user): void {
            Friendship::firstOrCreate([
                'user_id' => $request->user()->getKey(),
                'friend_id' => $user->getKey(),
            ]);
        });

        return redirect()->route('users.show', $user);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        DB::transaction(function () use ($request, $user): void {
            Friendship::query()
                ->where('user_id', $request->user()->getKey())
                ->where('friend_id', $user->getKey())
                ->first()?->delete();
        });

        return redirect()->route('users.show', $user);
    }
}
