<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    public function index(): View
    {
        $questions         = user()->questions;
        $archivedQuestions = user()->questions()->onlyTrashed()->get();

        return view('question.index', compact('questions', 'archivedQuestions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $request->validate(
            [
                'question' => ['required', 'string', 'min:10', 'ends_with:?', 'unique:questions,question'],
            ]
        );
        user()->questions()->create([
            'question' => $attributes['question'],
            'draft'    => true,

        ]);

        return back();
    }

    public function edit(Question $question): View
    {
        $this->authorize('update', $question);

        return view('question.edit', ['question' => $question]);
    }

    public function update(Question $question): RedirectResponse
    {
        $this->authorize('update', $question);

        $attributes = request()->validate(
            [
                'question' => ['required', 'string', 'min:10', 'ends_with:?'],
            ]
        );
        $question->update([
            'question' => $attributes['question'],
        ]);

        return redirect()->route('question.index');
    }

    public function archive(Question $question): RedirectResponse
    {
        $this->authorize('archive', $question);

        $question->delete();

        return back();
    }

    public function restore(int $id): RedirectResponse
    {
        $question = Question::withTrashed()->findOrFail($id);
        $this->authorize('archive', $question);

        $question->restore();

        return back();
    }

    public function destroy(Question $question): RedirectResponse
    {
        user()->can('delete', $question)
            ? $question->forceDelete()
            : abort(Response::HTTP_FORBIDDEN);

        return back();
    }

}
