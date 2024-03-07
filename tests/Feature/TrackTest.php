<?php

namespace Tests\Feature;

use App\Models\Track;
use App\Models\User;
use Faker\Core\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrackTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the track overview is displayed.
     * @return void
     */
    public function test_track_overview_is_displayed(): void
    {
        $user = User::factory()->create();

        $tracks = Track::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('tracks.index'));

        // $response = $this->get(route('tracks.index'));

        $response->assertInertia(
            fn ($assert) => $assert
                ->component('Tracks/Index')
                ->has('tracks', sizeof($tracks))
        );
    }

    /**
     * Test the track is displayed
     * @return void
     */
    public function test_track_is_displayed(): void
    {
        $user = User::factory()->create();

        $track = Track::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('tracks.show', $track));

        $response->assertInertia(
            fn ($assert) => $assert
                ->component('Tracks/Show')
                ->has('track')
        );
    }

    /**
     * Test the track creation form is displayed.
     * @return void
     */
    public function test_track_creation_form_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('tracks.create'));

        $response->assertInertia(
            fn ($assert) => $assert
                ->component('Tracks/Create')
        );
    }

    /**
     * Test a track can be created.
     * @return void
     */
    public function test_track_can_be_created(): void
    {
        Storage::fake();

        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('test.gpx');

        $response = $this
            ->actingAs($user)
            ->post(route('tracks.store'), [
                'title' => 'Test Track',
                'starting_location' => 'Test City',
                'destination_location' => 'Test City',
                'gpx_file' => $file,
            ]);

        $response
            ->assertSessionHasNoErrors()->assertRedirect(route('tracks.show', 1));

        // Storage::disk()->assertExists($file->hashName());

        $this->assertDatabaseHas('tracks', [
            'title' => 'Test Track',
            'starting_location' => 'Test City',
            'destination_location' => 'Test City',
            // 'gpx_file' => 'test.gpx',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test a track can be deleted.
     * @return void
     */
    public function test_track_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $track = Track::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('tracks.destroy', $track));

        $response->assertRedirect(route('tracks.index'));

        $this->assertDatabaseMissing('tracks', [
            'id' => $track->id,
        ]);
    }

    /**
     * Test a track can be updated.
     * @return void
     */
    public function test_track_can_be_updated(): void
    {
        $user = User::factory()->create();

        $track = Track::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('tracks.update', $track), [
                'title' => 'Updated Track',
                'starting_location' => 'Updated City',
                'destination_location' => 'Updated City',
            ]);

        $response->assertRedirect(route('tracks.show', $track));

        $this->assertDatabaseHas('tracks', [
            'id' => $track->id,
            'title' => 'Updated Track',
            'starting_location' => 'Updated City',
            'destination_location' => 'Updated City',
        ]);
    }
}
