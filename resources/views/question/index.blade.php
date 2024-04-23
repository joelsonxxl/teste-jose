<x-app-layout>
    <x-slot name="header">

        <x-header>

            {{ __('My Questions') }}
        </x-header>

    </x-slot>

    <x-container>
        <x-form post :action="route('question.store')">
            <div class="mb-4">
                <x-textarea name="question" label="Question" placeholder="Ask me anything...">

                </x-textarea>
            </div>
            <x-btn.primary type="submit">
                Save
            </x-btn.primary>
            <x-btn.secondary type="reset">
                Cancel
            </x-btn.secondary>

        </x-form>

        <hr class="my-4 border-gray-700 border-dashed">

        <div class="dark:text-gray-400 uppercase font-bold mb-1">
            Drafts
        </div>

        <div class="dark:text-gray-400 space-y-4">
            <x-table>
                <x-table.thead>
                    <tr>
                        <x-table.th>
                            Question
                        </x-table.th>
                        <x-table.th>
                            Actions
                        </x-table.th>
                    </tr>
                </x-table.thead>
                <tbody>
                @foreach($questions->where('draft', true) as $item)
                    <x-table.tr>
                        <x-table.td>
                            {{ $item->question }}
                        </x-table.td>

                        <x-table.td>
                            <div class="flex items-center justify-start">
                                <x-form delete :action="route('question.destroy', $item)"
                                        onsubmit="return confirm('Tem certeza?')">
                                    <x-btn.danger type="submit">
                                        <x-icons.trash class="h-5 w-5"/>
                                    </x-btn.danger>
                                </x-form>
                                <x-form put :action="route('question.publish', $item)">
                                    <x-btn.primary type="submit">
                                        <x-icons.send class="h-5 w-5"/>
                                    </x-btn.primary>
                                </x-form>

                                <a href="{{ route('question.edit', $item) }}"
                                   class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                    <x-icons.pencil class="h-5 w-5"/>
                                </a>
                            </div>
                        </x-table.td>

                    </x-table.tr>
                @endforeach

                </tbody>
            </x-table>

        </div>

        <hr class="my-4 border-gray-700 border-dashed">

        <div class="dark:text-gray-400 uppercase font-bold mb-1">
            My Questions
        </div>

        <div class="dark:text-gray-400 space-y-4">
            <x-table>
                <x-table.thead>
                    <tr>
                        <x-table.th>
                            Question
                        </x-table.th>
                        <x-table.th>
                            Actions
                        </x-table.th>
                    </tr>
                </x-table.thead>
                <tbody>
                @foreach($questions->where('draft', false) as $item)
                    <x-table.tr>
                        <x-table.td>
                            {{ $item->question }}
                        </x-table.td>

                        <x-table.td>
                            <div class="flex items-center justify-start">
                                <x-form delete :action="route('question.destroy', $item)"
                                        onsubmit="return confirm('Tem certeza?')">
                                    <x-btn.danger type="submit">
                                        <x-icons.trash class="h-5 w-5"/>
                                    </x-btn.danger>
                                </x-form>
                                <x-form patch :action="route('question.archive', $item)">
                                    <x-btn.primary type="submit">
                                        <x-icons.archive class="h-5 w-5"/>
                                    </x-btn.primary>
                                </x-form>
                            </div>

                        </x-table.td>

                    </x-table.tr>
                @endforeach

                </tbody>
            </x-table>

        </div>

        <hr class="my-4 border-gray-700 border-dashed">

        <div class="dark:text-gray-400 uppercase font-bold mb-1">
            Archived Questions
        </div>

        <div class="dark:text-gray-400 space-y-4">
            <x-table>
                <x-table.thead>
                    <tr>
                        <x-table.th>
                            Question
                        </x-table.th>
                        <x-table.th>
                            Actions
                        </x-table.th>
                    </tr>
                </x-table.thead>
                <tbody>
                @foreach($archivedQuestions as $item)
                    <x-table.tr>
                        <x-table.td>
                            {{ $item->question }}
                        </x-table.td>

                        <x-table.td>
                            <div class="flex items-center justify-start">
                                <x-form patch :action="route('question.restore', $item)">
                                    <x-btn.danger type="submit">
                                        <x-icons.undo class="h-5 w-5"/>
                                    </x-btn.danger>
                                </x-form>
                            </div>

                        </x-table.td>

                    </x-table.tr>
                @endforeach

                </tbody>
            </x-table>

        </div>


    </x-container>

</x-app-layout>
