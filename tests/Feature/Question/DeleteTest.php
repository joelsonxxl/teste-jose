<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing, delete};

it('should be able to delete a question', function () {
    $user = User::factory()->create();
    actingAs($user);
    $question = Question::factory()->create(['draft' => true, 'created_by' => $user->id]);

    delete(route('question.destroy', $question))->assertRedirect();

    assertDatabaseMissing('questions', ['id' => $question->id]);
});

it('should make sure that only user the person who created the question can destroy it', function () {

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

    delete(route('question.destroy', $question))->assertForbidden();
    assertDatabaseHas('questions', ['id' => $question->id]);

    actingAs($rigthUser);

    delete(route('question.destroy', $question))->assertRedirect();
    assertDatabaseMissing('questions', ['id' => $question->id]);

});
