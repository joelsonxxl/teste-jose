<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs,
    assertDatabaseHas,
    assertNotSoftDeleted,
    assertSoftDeleted,
    patch
};

it('should be able to archive a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create(['draft' => true, 'created_by' => $user->id]);

    actingAs($user);

    patch(route('question.archive', $question))->assertRedirect();

    assertSoftDeleted('questions', ['id' => $question->id]);
    $question->refresh();
    expect($question->deleted_at)->not->toBeNull();
});

it('should make sure that only user the person who created the question archive it', function () {

    $rigthUser = User::factory()->create();
    $question  = Question::factory()
        ->create(
            [
                'draft'      => true,
                'created_by' => $rigthUser->id,
            ]
        );
    $wrongUser = User::factory()->create();

    actingAs($wrongUser);

    patch(route('question.archive', $question))->assertForbidden();
    assertDatabaseHas('questions', ['id' => $question->id, 'deleted_at' => null]);

    actingAs($rigthUser);

    patch(route('question.archive', $question))->assertRedirect();
    assertSoftDeleted('questions', ['id' => $question->id]);

});

it('should be able to restore an archived question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create(['draft' => true, 'created_by' => $user->id, 'deleted_at' => now()]);
    assertSoftDeleted('questions', ['id' => $question->id]);
    actingAs($user);

    patch(route('question.restore', $question))->assertRedirect();

    assertNotSoftDeleted('questions', ['id' => $question->id]);
    $question->refresh();
    expect($question->deleted_at)->toBeNull();
});
