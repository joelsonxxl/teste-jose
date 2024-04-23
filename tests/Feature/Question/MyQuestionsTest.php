<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, get};

it('should be able to list all questions created by me', function () {
    $wrongUser      = User::factory()->create();
    $user           = User::factory()->create();
    $questions      = Question::factory()->count(10)->create(['created_by' => $user->id]);
    $wrongQuestions = Question::factory()->count(10)->create(['created_by' => $wrongUser->id]);

    actingAs($user);
    $response = get(route('question.index'));

    foreach ($questions as $item) {
        $response->assertSee($item->question);
    }

    foreach ($wrongQuestions as $item) {
        $response->assertDontSee($item->question);
    }

});
