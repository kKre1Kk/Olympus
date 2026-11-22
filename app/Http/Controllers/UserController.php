<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('gods')
            ->orderBy('username')
            ->get();

        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $query = $user->gods()->latest();

        if (auth()->user()->is_admin) {
            $query->withTrashed();
        }

        $gods = $query->get();

        $friends = $user->friends()->orderBy('name')->get();
        $isFriend = auth()->user()->friends()
            ->where('users.id', $user->getKey())
            ->exists();

        $outgoingRequest = auth()->user()->sentFriendRequests()
            ->where('receiver_id', $user->id)
            ->first();

        $incomingRequest = auth()->user()->receivedFriendRequests()
            ->where('sender_id', $user->id)
            ->first();

        return view('users.show', compact(
            'user', 'gods', 'friends', 'isFriend',
            'outgoingRequest', 'incomingRequest'
        ));
    }
}
