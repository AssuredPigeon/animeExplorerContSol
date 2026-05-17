<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /** List authenticated user's favorites */
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('favorites.index', compact('favorites'));
    }

    /** Toggle favorite (add if not exists, remove if exists) — called via AJAX */
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'mal_id'    => ['required', 'integer'],
            'title'     => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'string'],
            'score'     => ['nullable', 'numeric'],
            'type'      => ['nullable', 'string', 'max:20'],
            'year'      => ['nullable', 'integer'],
        ]);

        $existing = Favorite::where('user_id', Auth::id())
            ->where('mal_id', $data['mal_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        Favorite::create([...$data, 'user_id' => Auth::id()]);

        return response()->json(['status' => 'added']);
    }

    /** Remove a specific favorite */
    public function destroy(int $malId)
    {
        Favorite::where('user_id', Auth::id())
            ->where('mal_id', $malId)
            ->delete();

        return redirect()->route('favorites.index')
            ->with('success', 'Eliminado de favoritos.');
    }
}
