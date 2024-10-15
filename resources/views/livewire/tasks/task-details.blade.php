<div class="p-10 bg-white rounded-lg h-full">
    <div class="mb-5">
        <div class="flex items-center gap-1">
            <img class='inline-block mr-2' src="{{ asset($task::PRIORITIES_ICON_FOLDER . "/" . $task->task_priority . $task::PRIORITIES_ICON_EXTN) }}" />

            <span>{{ $task::SELECT_PRIORITIES[$task->task_priority] }}</span>

            <span class="ml-4 mr-2">
                {!! $task::SELECT_TAST_TYPE_ICONS[$task->task_type] !!}
            </span>

            <span>
                {{$task->task_code}}
            </span>
        </div>
        <h1 class="text-2xl font-bold text-slate-700">{{ $task->title }}</h1>
    </div>

    <div class="content">

        <div class="prose  max-w-none !border-none py-1.5 text-base text-gray-950 dark:prose-invert focus-visible:outline-none dark:text-white sm:text-sm sm:leading-6">
            {!! $task->description !!}
        </div>

    </div>
</div>

@script
<script>
    window.addEventListener('reloadlivewire', event => {
       $wire.$refresh();
   })
</script>
@endscript
