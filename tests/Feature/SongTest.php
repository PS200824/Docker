<?php

namespace Tests\Feature;

use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SongTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_all_songs()
    {
        $songs = Song::factory()->count(3)->create();

        $response = $this->get('/songs');

        $response->assertStatus(200);
        $response->assertSee($songs[0]->title);
        $response->assertSee($songs[1]->title);
        $response->assertSee($songs[2]->title);
    }

    /** @test */
    public function a_user_can_view_a_single_song()
    {
        $song = Song::factory()->create();

        $response = $this->get('/songs/' . $song->id);

        $response->assertStatus(200);
        $response->assertSee($song->title);
        $response->assertSee($song->artist);
    }

    /** @test */
    public function a_user_can_create_a_song()
    {
        $response = $this->post('/songs', [
            'title' => 'Test Song',
            'artist' => 'Test Artist',
            'album' => 'Test Album',
            'year' => 2021
        ]);

        $response->assertRedirect('/songs');
        $this->assertDatabaseHas('songs', ['title' => 'Test Song']);
    }

    /** @test */
    public function a_user_can_update_a_song()
    {
        $song = Song::factory()->create();

        $response = $this->put('/songs/' . $song->id, [
            'title' => 'Updated Song',
            'artist' => 'Updated Artist',
            'album' => 'Updated Album',
            'year' => 2022
        ]);

        $response->assertRedirect('/songs');
        $this->assertDatabaseHas('songs', ['title' => 'Updated Song']);
    }

    /** @test */
    public function a_user_can_delete_a_song()
    {
        $song = Song::factory()->create();

        $response = $this->delete('/songs/' . $song->id);

        $response->assertRedirect('/songs');
        $this->assertDatabaseMissing('songs', ['id' => $song->id]);
    }
}

