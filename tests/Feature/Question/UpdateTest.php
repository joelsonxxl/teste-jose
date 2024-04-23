<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, put};

it('should be able to update a question', function () {
    $user = User::factory()->create();
    actingAs($user);
    $question = Question::factory()->create(['created_by' => $user->id, 'draft' => true]);

    put(
        route('question.update', $question->id),
        [
            'question' => 'This is a new question?',
        ]
    )->assertRedirect(route('question.index'));

    $question->refresh();

    expect($question->question)->toBe('This is a new question?');

});

it('should make sure that only the status Draft can be updated', function () {

    $user             = User::factory()->create();
    $questionNotDraft = Question::factory()->create(['created_by' => $user->id, 'draft' => false]);
    $draftQuestion    = Question::factory()->create(['created_by' => $user->id, 'draft' => true]);

    actingAs($user);

    put(route('question.update', $questionNotDraft->id))->assertForbidden();
    put(
        route('question.update', $draftQuestion->id),
        [
            'question' => 'This is a new question?',
        ]
    )->assertRedirect();
});

it('should make sure that only user the person who created the question can update it', function () {

    $rightUser = User::factory()->create();
    actingAs($rightUser);
    $question  = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);
    $wrongUser = User::factory()->create();

    actingAs($wrongUser);
    put(
        route('question.update', $question),
        [
            'question' => 'This is a error question?',
        ]
    )->assertForbidden();

    actingAs($rightUser);
    put(
        route('question.update', $question->id),
        [
            'question' => 'This is a new question?',
        ]
    )->assertRedirect();

});

it('should be able to update a question bigger than 255 characters', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create(['created_by' => $user->id, 'draft' => true]);

    actingAs($user);

    $request = put(route('question.update', $question), [
        'question' => str_repeat('a', 260) . '?',
    ])->assertRedirect();

    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', [
        'question' => str_repeat('a', 260) . '?',
    ]);
});

it('should check if ends with a question mark', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['created_by' => $user->id, 'draft' => true]);

    actingAs($user);

    $request = put(route('question.update', $question), [
        'question' => str_repeat('a', 10),
    ]);

    // Assert : Expect an exception
    $request->assertSessionHasErrors(
        [
            'question' => __('validation.ends_with', ['attribute' => 'question', 'values' => '?']),
        ]
    );
    assertDatabaseHas('questions', [
        'question' => $question->question,
    ]);
    assertDatabaseCount('questions', 1);
});

it('should have at least 10 characters', function () {

    $user     = User::factory()->create();
    $question = Question::factory()->create(['created_by' => $user->id, 'draft' => true]);

    actingAs($user);

    $request = put(route('question.update', $question), [
        'question' => 'a?',
    ]);

    $request->assertSessionHasErrors(
        [
            'question' => __('validation.min.string', ['attribute' => 'question', 'min' => 10]),
        ]
    );
    assertDatabaseHas('questions', [
        'question' => $question->question,
    ]);
    assertDatabaseCount('questions', 1);
});
