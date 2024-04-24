<x-app-layout>
    <x-slot name="header">

        <x-header>

            {{ __('Vote for a question') }}
        </x-header>

    </x-slot>

    <x-container>
        <div class="flex flex-col space-y-4">

            <x-form :action="route('dashboard')" get>
                <x-inputs.search autocomplete="off"/>
            </x-form>

            @if($questions->isEmpty())
                <div class="dark:text-gray-300 text-center flex flex-col justify-center">
                    <p class="text-2xl font-bold mb-8 mt-5">No questions found</p>
                    <div class="flex justify-center">
                        <x-draws.searching width="350"/>
                    </div>


                </div>
            @else

                @foreach ($questions as $item)
                    <x-question :question="$item"/>
                @endforeach

                {{$questions->withQueryString()->links()}}
            @endif
        </div>
    </x-container>

</x-app-layout>
