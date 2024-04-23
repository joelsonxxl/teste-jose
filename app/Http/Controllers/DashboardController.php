<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $questions = Question::withSum('votes', 'like')
            ->withSum('votes', 'unlike')
            ->when(request('search'), function ($query) {
                $query->where('question', 'like', '%' . request('search') . '%');
            })
            ->orderByRaw('
                    case when votes_sum_like is null then 0 else votes_sum_like end desc,
                    case when votes_sum_unlike is null then 0 else votes_sum_unlike end
                ')
            ->paginate(5);

        return view('dashboard', compact('questions'));
    }
}
