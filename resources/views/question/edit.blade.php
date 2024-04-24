<x-app-layout>
    <x-slot name="header">

        <x-header>

            {{ __('Edit Question') }} :: {{ $question->id }}
        </x-header>

    </x-slot>

    <x-container>
        <x-form put :action="route('question.update', $question)">
            <div class="mb-4">
                <x-textarea name="question" label="Question" placeholder="Ask me anything..."
                            :value="$question->question">

                </x-textarea>
            </div>
            <x-btn.primary type="submit">
                Save
            </x-btn.primary>
            <x-btn.secondary type="reset">
                Cancel
            </x-btn.secondary>

        </x-form>


    </x-container>

</x-app-layout>
