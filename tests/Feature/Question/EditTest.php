<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, get};

it('should be able to open a question to edit', function () {

    $user = User::factory()->create();
    actingAs($user);
    $question = Question::factory()->create(['created_by' => $user->id, 'draft' => true]);

    get(route('question.edit', $question->id))->assertSuccessful();
});

it('should return a view', function () {

    $user = User::factory()->create();
    actingAs($user);
    $question = Question::factory()->create(['created_by' => $user->id, 'draft' => true]);

    get(route('question.edit', $question->id))->assertViewIs('question.edit');
});

it('should make sure that only the status Draft can be edited', function () {

    $user             = User::factory()->create();
    $questionNotDraft = Question::factory()->create(['created_by' => $user->id, 'draft' => false]);
    $draftQuestion    = Question::factory()->create(['created_by' => $user->id, 'draft' => true]);

    actingAs($user);

    get(route('question.edit', $questionNotDraft->id))->assertForbidden();
    get(route('question.edit', $draftQuestion->id))->assertSuccessful();
});

it('should make sure that only user the person who created the question can edit it', function () {

    $rightUser = User::factory()->create();
    actingAs($rightUser);
    $question  = Question::factory()->create(['draft' => true, 'created_by' => $rightUser->id]);
    $wrongUser = User::factory()->create();

    actingAs($wrongUser);
    get(route('question.edit', $question))->assertForbidden();

    actingAs($rightUser);
    get(route('question.edit', $question))->assertSuccessful();

});
