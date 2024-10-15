<x-layouts.app>

    <div class="mb-5 mt-3 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">
            <li class="me-2">
                <a href="javascript:void(0)" class="inline-block p-4 border-b-2 rounded-t-lg text-blue-700 hover:text-blue-800 border-blue-800 hover:border-blue-800">Task Details</a>
            </li>
        </ul>
    </div>

    <div class="flex flex-wrap ">
        <div class="w-full lg:w-9/12">
            @livewire('tasks.task-details', ['task' => $task])
        </div>

        <div class="w-full lg:w-3/12">
            <div class="p-10 bg-white lg:ml-2 mt-2 lg:mt-0 rounded-lg h-full">
                @livewire('tasks.deatils-update-form', ['task' => $task])
            </div>
        </div>
    </div>



    <div class="mt-5" x-data="{openedtab: 'comments'}">

        <div class="mb-5 text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px">
                <li class="me-2">
                    <a href="javascript:void(0)" @click="openedtab = 'comments'" :class="openedtab == 'comments' ? 'text-blue-700 hover:text-blue-800 border-blue-800 hover:border-blue-800' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'" class="inline-block p-4 border-b-2 rounded-t-lg">Comments</a>
                </li>
                <li class="me-2">
                    <a href="javascript:void(0)" @click="openedtab = 'worklogs'" :class="openedtab == 'worklogs' ? 'text-blue-700 hover:text-blue-800 border-blue-800 hover:border-blue-800' : 'border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'" class="inline-block p-4 border-b-2  rounded-t-lg">Work Logs</a>
                </li>
            </ul>
        </div>

        <div>

            <div x-show="openedtab == 'comments'">
                @livewire('tasks.comments', ["task" => $task])
            </div>

            <div x-show="openedtab == 'worklogs'">
                @livewire('work-logs.task-work-logs', ["task" => $task])
            </div>

        </div>

    </div>



</x-layouts.app>
