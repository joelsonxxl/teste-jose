<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\{RedirectResponse};
use Symfony\Component\HttpFoundation\Response;

class PublishController extends Controller
{
    public function __invoke(Question $question): RedirectResponse
    {
        user()->can('publish', $question)
            ? $question->update(['draft' => false])
            : abort(Response::HTTP_FORBIDDEN, '403 Forbidden');

        $question->update(['draft' => false]);

        return back();
    }
}
