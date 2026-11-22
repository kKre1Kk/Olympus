<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\Friendship;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FriendRequestController extends Controller
{
    public function index(Request $request): View
    {
        $incoming = $request->user()->receivedFriendRequests()
            ->with('sender')
            ->latest()
            ->get();

        $outgoing = $request->user()->sentFriendRequests()
            ->with('receiver')
            ->latest()
            ->get();

        return view('friends.requests', compact('incoming', 'outgoing'));
    }

    public function accept(Request $request, FriendRequest $friendRequest): RedirectResponse
    {
        abort_unless($friendRequest->receiver_id === $request->user()->id, 403);

        DB::transaction(function () use ($friendRequest): void {
            Friendship::firstOrCreate([
                'user_id' => $friendRequest->receiver_id,
                'friend_id' => $friendRequest->sender_id,
            ]);

            FriendRequest::query()
                ->where(function ($query) use ($friendRequest): void {
                    $query->where('sender_id', $friendRequest->sender_id)
                        ->where('receiver_id', $friendRequest->receiver_id);
                })
                ->orWhere(function ($query) use ($friendRequest): void {
                    $query->where('sender_id', $friendRequest->receiver_id)
                        ->where('receiver_id', $friendRequest->sender_id);
                })
                ->delete();
        });

        return redirect()->route('friend-requests.index');
    }

    public function destroy(Request $request, FriendRequest $friendRequest): RedirectResponse
    {
        abort_unless(
            $friendRequest->sender_id === $request->user()->id
                || $friendRequest->receiver_id === $request->user()->id,
            403
        );

        $friendRequest->delete();

        return redirect()->route('friend-requests.index');
    }
}
