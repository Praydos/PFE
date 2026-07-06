<?php

use App\Models\Vacation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->delegue = User::factory()->delegue()->create();
});

it('prevents guests from accessing vacations', function () {
    $this->get(route('vacations.index'))->assertRedirect(route('login'));
});

it('prevents delegue from accessing vacations', function () {
    $this->actingAs($this->delegue)
        ->get(route('vacations.index'))
        ->assertStatus(403);
});

it('allows admin to view vacations', function () {
    Vacation::create([
        'name' => 'Summer Holiday',
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(10)->toDateString(),
    ]);

    $this->actingAs($this->admin)
        ->get(route('vacations.index'))
        ->assertStatus(200)
        ->assertSee('Summer Holiday');
});

it('allows admin to create vacation', function () {
    $start = now()->toDateString();
    $end = now()->addDays(5)->toDateString();
    $response = $this->actingAs($this->admin)
        ->post(route('vacations.store'), [
            'name' => 'Winter Break',
            'start_date' => $start,
            'end_date' => $end,
        ]);

    $response->assertRedirect(route('vacations.index'));
    $this->assertDatabaseHas('vacations', [
        'name' => 'Winter Break',
    ]);
});

it('allows admin to delete vacation', function () {
    $vacation = Vacation::create([
        'name' => 'Spring Break',
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(3)->toDateString(),
    ]);

    $response = $this->actingAs($this->admin)
        ->delete(route('vacations.destroy', $vacation));

    $response->assertRedirect(route('vacations.index'));
    $this->assertDatabaseMissing('vacations', ['id' => $vacation->id]);
});
