<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, post};

it('should be able to create a new question bigger than 255 characters', function () {
    // Arrange : prepare the data

    $user = User::factory()->create();
    actingAs($user);

    // Act  : Create a new question with more than 255 characters

    $request = post(route('question.store'), [
        'question' => str_repeat('a', 256) . '?',
    ]);

    // Assert : Expect an exception
    $request->assertRedirect();
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', [
        'question' => str_repeat('a', 256) . '?',
    ]);
});

it('should check if ends with a question mark', function () {

    // Arrange : prepare the data
    $user = User::factory()->create();
    actingAs($user);

    // Act  : Create a new question without a question mark
    $request = post(route('question.store'), [
        'question' => str_repeat('a', 10),
    ]);

    // Assert : Expect an exception
    $request->assertSessionHasErrors(
        [
            'question' => __('validation.ends_with', ['attribute' => 'question', 'values' => '?']),
        ]
    );
});

it('should have at least 10 characters', function () {

    // Arrange : prepare the data
    $user = User::factory()->create();
    actingAs($user);

    // Act  : Create a new question with less than 10 characters
    $request = post(route('question.store'), [
        'question' => 'a?',
    ]);

    // Assert : Expect an exception

    $request->assertSessionHasErrors(
        [
            'question' => __('validation.min.string', ['attribute' => 'question', 'min' => 10]),
        ]
    );
});

it('should create a draft all the time', function () {
    // Arrange : prepare the data
    $user = User::factory()->create();
    actingAs($user);

    // Act  : Create a new question
    $request = post(route('question.store'), [
        'question' => str_repeat('a', 10) . '?',
    ]);

    // Assert : Expect an exception
    assertDatabaseHas('questions', [
        'question' => str_repeat('a', 10) . '?',
        'draft'    => true,
    ]);

});

it('only authenticated users can create a question', function () {
    post(route('question.store'), [
        'question' => str_repeat('a', 10) . '?',
    ])->assertRedirect(route('login'));
});

it('should have a unique question', function () {
    $user = User::factory()->create();
    actingAs($user);
    Question::factory()->create([
        'question' => str_repeat('a', 10) . '?',
    ]);

    $request = post(route('question.store'), [
        'question' => str_repeat('a', 10) . '?',
    ]);

    $request->assertSessionHasErrors(
        [
            'question' => __('validation.unique', ['attribute' => 'question']),
        ]
    );
});
