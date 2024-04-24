<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, get};

it('should be able to search a test by text', function () {
    $user = User::factory()->create();
    Question::factory()->create(['question' => 'Something else?']);
    Question::factory()->create(['question' => 'This is a test question']);

    actingAs($user);

    $response = get(route('dashboard', ['search' => 'question']));

    $response->assertSee('This is a test question');
    $response->assertDontSee('Something else?');
});
