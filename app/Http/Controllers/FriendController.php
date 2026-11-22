<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        $sender = $request->user();

        abort_if($sender->is($user), 422, 'Нельзя отправить заявку самому себе.');

        if ($sender->friends()->where('users.id', $user->id)->exists()) {
            return redirect()->route('users.show', $user);
        }

        if (FriendRequest::query()
            ->where('sender_id', $user->id)
            ->where('receiver_id', $sender->id)
            ->exists()) {
            return redirect()->route('friend-requests.index');
        }

        $sender->sentFriendRequests()->firstOrCreate([
            'receiver_id' => $user->id,
        ]);

        return redirect()->route('users.show', $user);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        DB::transaction(function () use ($request, $user): void {
            Friendship::query()
                ->where('user_id', $request->user()->id)
                ->where('friend_id', $user->id)
                ->first()?->delete();
        });

        return redirect()->route('users.show', $user);
    }
}
