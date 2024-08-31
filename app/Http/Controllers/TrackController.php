<?php

namespace App\Http\Controllers;

use App\Models\Week;
use App\Models\Track;
use App\Players\Player;
use App\Rules\PlayerUrl;
use App\Jobs\FetchTrackInfo;
use App\Services\UserService;
use App\Exceptions\PlayerException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Show given track.
     */
    public function show(Request $request, Week $week, Track $track): View
    {
        return view('app.tracks.show', [
            'week' => $week->loadCount('tracks'),
            'track' => $track->loadCount('likes'),
            'tracks_count' => $week->tracks_count,
            'position' => $week->getTrackPosition($track),
            'liked' => $request->user()->likes()->whereTrackId($track->id)->exists(),
            'embed' => app(Player::class)->trackEmbed($track->player, $track->player_track_id),
        ]);
    }

    /**
     * Show create track form.
     */
    public function create(UserService $user): View
    {
        return view('app.tracks.create', [
            'week' => Week::current(),
            'remaining_tracks_count' => $user->remainingTracksCount(),
        ]);
    }

    /**
     * Create a new track.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Track::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'artist' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', new PlayerUrl()],
        ]);

        DB::beginTransaction();

        $track = new Track($validated);
        $track->week()->associate(Week::current());
        $track->user()->associate($request->user());
        $track->save();

        try {
            dispatch(new FetchTrackInfo($track));
        } catch (PlayerException $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();

        return redirect()->route('app.tracks.show', [
            'week' => $track->week->uri,
            'track' => $track,
        ]);
    }

    /**
     * Toggle like.
     */
    public function like(Request $request, Week $week, Track $track): RedirectResponse
    {
        $user = $request->user();

        $track->likes()->toggle([$user->id => ['liked_at' => now()]]);

        return redirect()->route('app.tracks.show', [
            'week' => $week->uri,
            'track' => $track,
        ]);
    }
}