@props(['question'])

<div
    class="rounded dark:bg-gray-800 p-4 dark:text-gray-200 shadow-md shadow-blue-500/50 flex justify-between items-center">
    <span>
        {{ $question->question }}
    </span>
    <div>
        <x-form post :action="route('question.like', $question)">
            <button type="submit" class="flex items-center space-x-2 text-green-500 cursor-pointer">
                <x-icons.thumbs-up class=" w-5 h-5 hover:text-green-300 " id="thumbs-up"/>
                <span>
                    {{ $question->votes_sum_like ?: 0 }}
                </span>

            </button>
        </x-form>
        <x-form :action="route('question.unlike', $question)">
            <button type="submit" class="flex items-center space-x-2 text-red-500 cursor-pointer">
                <x-icons.thumbs-down class="w-5 h-5 hover:text-red-300 " id="thumbs-down"/>
                <span>
                {{ $question->votes_sum_unlike ?: 0}}
            </span>
            </button>
        </x-form>
    </div>
</div>
