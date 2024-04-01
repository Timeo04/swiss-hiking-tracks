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

    /**
     * Display the user's settings form.
     *
     * @param Request $request
     * @return void
     */
    public function settings(Request $request)
    {
        // Get Tracks from User
        $tracks = $request->user()->tracks;
        // Initialize empty images array
        $images = [];
        // Loop through each track and get the images
        foreach ($tracks as $track) {
            $media = $track->getMedia('images');
            foreach ($media as $image) {
                array_push($images, ['id' => $image->id, 'url' => $image->getUrl(), 'track_id' => $track->id]);
            }
        }
        // Return the Inertia view with the images
        return Inertia::render('Profile/Settings', [
            'images' => $images,
        ]);
    }

    /**
     * Set the user's hiking speed.
     *
     * @param Request $request
     * @return void
     */
    public function setHikingSpeed(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'hiking_speed' => ['required', 'numeric', 'min:0'],
        ]);

        // Set the user's hiking speed
        $request->user()->hiking_speed = $request->input('hiking_speed');
        $request->user()->save();

        // Return redirect to settings route
        return to_route('settings');
    }
}
