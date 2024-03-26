<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function settings(Request $request)
    {
        $tracks = $request->user()->tracks;
        $images = [];
        foreach ($tracks as $track) {
            $media = $track->getMedia('images');
            foreach ($media as $image) {
                array_push($images, ['id' => $image->id, 'url' => $image->getUrl(), 'track_id' => $track->id]);
            }
        }
        return Inertia::render('Profile/Settings', [
            'images' => $images,
        ]);
    }

    public function setHikingSpeed(Request $request)
    {
        $request->validate([
            'hiking_speed' => ['required', 'numeric', 'min:0'],
        ]);

        $request->user()->hiking_speed = $request->input('hiking_speed');
        $request->user()->save();

        return to_route('settings');
    }
}
