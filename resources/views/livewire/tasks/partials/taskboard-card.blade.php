<div class="item bg-white cursor-pointer p-4 w-full max-w-[300px] rounded-lg tasks-card-draggable"
    data-task-id="{{ $task->id }}"
    draggable="true"
    wire:key="{{ str()->random(10) }}"

    onclick="window.location = '{{ route('tasks.details', ['task' => $task, 'project' => $task->project->id]) }}'"

>
    <div class="content mb-4">
        <h6 class="text-gray-600">{{ str()->limit($task->title, 100, "...") }}</h6>
    </div>

    <div class="footer">
        <div class="flex items-center justify-between">
            <div class="item">
                {!! $task::SELECT_TAST_TYPE_ICONS[$task->task_type] !!}

                <span>
                    {{$task->task_code}}
                </span>
            </div>

            <div class="item flex gap-1 items-center">
                <img class='inline-block mr-2' src="{{ asset($task::PRIORITIES_ICON_FOLDER . "/" . $task->task_priority . $task::PRIORITIES_ICON_EXTN) }}" />

                <img class="h-6 w-6 rounded-full" src="{{$task->getUserAvatar($task->user) }}" alt="" />
            </div>
        </div>
    </div>
</div>
