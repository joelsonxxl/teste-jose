<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, put};

it('should be able to publish a question', function () {
    $user = User::factory()->create();
    actingAs($user);
    $question = Question::factory()->create(['draft' => true, 'created_by' => $user->id]);

    put(route('question.publish', $question))->assertRedirect();

    $question->refresh();
    expect($question->draft)->toBeFalse();
});

it('should make sure that only user the person who created the question can publish it', function () {
    $rigthUser = User::factory()->create();
    actingAs($rigthUser);
    $question = Question::factory()
        ->create(
            [
                'draft'      => true,
                'created_by' => $rigthUser->id,
            ]
        );

    $wrongUser = User::factory()->create();
    actingAs($wrongUser);

    put(route('question.publish', $question))->assertForbidden();

    $question->refresh();
    expect($question->draft)->toBeTrue();

    actingAs($rigthUser);
    put(route('question.publish', $question))->assertRedirect();

});
