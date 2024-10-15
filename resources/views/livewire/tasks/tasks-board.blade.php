<div>

    <div class="mb-5">
        <h1 class="text-2xl font-bold text-slate-700">Ongoing Tasks Board</h1>
        <p class="text-md text-slate-700">Monitor tasks in real-time, streamline workflows, and stay on top of your team's progress.</p>
    </div>

    <div class="overflow-x-auto">

        <div class="flex gap-2 p-2 rounded min-h-[85vh]">

            <div class="w-[20%] rounded bg-gray-200 py-2 px-5 h-100 board-card relative overflow-hidden">
                <div class="hidden absolute top-0 left-0 w-full h-full bg-blue-400 opacity-20 board-card-dropable pointer-events-none" wire:ignore data-board-status="to-do"></div>

                <div class="uppercase font-bold text-gray-600 mb-3 board-card-heading">
                    To Do ({{ count($boardItems['to-do']) }})
                </div>

                <div class="board-card-items flex flex-row flex-wrap gap-2 ">

                    @foreach ($boardItems['to-do'] as $task)
                        @include('livewire.tasks.partials.taskboard-card')
                    @endforeach

                </div>
            </div>

            <div class="w-[20%] rounded bg-gray-200 py-2 px-5 h-100 board-card relative overflow-hidden">
                <div class="hidden absolute top-0 left-0 w-full h-full bg-blue-400 opacity-20 board-card-dropable pointer-events-none" wire:ignore data-board-status="in-progress"></div>
                <div class="uppercase font-bold text-gray-600 mb-3 board-card-heading">
                    In Progress ({{ count($boardItems['in-progress']) }})
                </div>

                <div class="board-card-items flex flex-row flex-wrap gap-2">
                    @foreach ($boardItems['in-progress'] as $task)
                        @include('livewire.tasks.partials.taskboard-card')
                    @endforeach

                </div>
            </div>
            <div class="w-[20%] rounded bg-gray-200 py-2 px-5 h-100 board-card relative overflow-hidden">
                <div class="hidden absolute top-0 left-0 w-full h-full bg-blue-400 opacity-20 board-card-dropable pointer-events-none" wire:ignore data-board-status="in-qa-testing"></div>
                <div class="uppercase font-bold text-gray-600 mb-3 board-card-heading">
                    In QA Testing ({{ count($boardItems['in-qa-testing']) }})
                </div>

                <div class="board-card-items flex flex-row flex-wrap gap-2">
                    @foreach ($boardItems['in-qa-testing'] as $task)
                        @include('livewire.tasks.partials.taskboard-card')
                    @endforeach

                </div>
            </div>
            <div class="w-[20%] rounded bg-gray-200 py-2 px-5 h-100 board-card relative overflow-hidden" >
                <div class="hidden absolute top-0 left-0 w-full h-full bg-blue-400 opacity-20 board-card-dropable pointer-events-none" wire:ignore data-board-status="reopened"></div>
                <div class="uppercase font-bold text-gray-600 mb-3 board-card-heading">
                    Reopened ({{ count($boardItems['reopened']) }})
                </div>

                <div class="board-card-items flex flex-row flex-wrap gap-2">
                    @foreach ($boardItems['reopened'] as $task)
                        @include('livewire.tasks.partials.taskboard-card')
                    @endforeach

                </div>
            </div>
            <div class="w-[20%] rounded bg-gray-200 py-2 px-5 h-100 board-card relative overflow-hidden">
                <div class="hidden absolute top-0 left-0 w-full h-full bg-blue-400 opacity-20 board-card-dropable pointer-events-none" wire:ignore data-board-status="done"></div>
                <div class="uppercase font-bold text-gray-600 mb-3 board-card-heading">
                    Done ({{ count($boardItems['done']) }})
                </div>

                <div class="board-card-items flex flex-row flex-wrap gap-2">
                    @foreach ($boardItems['done'] as $task)
                        @include('livewire.tasks.partials.taskboard-card')
                    @endforeach

                </div>
            </div>

        </div>

    </div>

    <x-slot:scripts>
        <script>         /* let draggables = document.querySelectorAll(".tasks-card-draggable"); */ </script>
        @script
            <script>


                let dropables = document.querySelectorAll(".board-card-dropable");

                document.body.addEventListener('dragstart', (e) => {

                    if(e.target.classList.contains("tasks-card-draggable")){

                        document.querySelectorAll('.board-card-dropable').forEach(el => {
                            el.classList.remove('hidden')
                            setTimeout(() => {
                                el.classList.remove('pointer-events-none')
                            }, 50);
                        });
                        e.target.setAttribute('task-dragging', 'true');

                    }
                });

                document.body.addEventListener('dragend', (e) => {
                    document.querySelectorAll('.board-card-dropable').forEach(el => {
                        el.classList.add('hidden');
                        setTimeout(() => {
                            el.classList.add('pointer-events-none')
                        }, 50);
                    });
                    e.target.removeAttribute('task-dragging');
                    e.preventDefault();
                });


                dropables.forEach(el => {

                    el.addEventListener('drop', (e) => {



                        let zone = e.target.getAttribute('data-board-status');
                        let taskcard = document.querySelector('.tasks-card-draggable[task-dragging=true]');


                        if(taskcard != undefined && taskcard != null && zone != null && zone != undefined){

                            let taskid = taskcard.getAttribute('data-task-id');

                            $wire.changeTaskStatus(zone, taskid);

                        }



                        e.preventDefault();

                    });

                    el.addEventListener('dragenter', (e) => {
                        e.preventDefault();
                    });

                    el.addEventListener('dragover', (e) => {
                        e.preventDefault();
                    });

                });

            </script>
        @endscript
    </x-slot:scripts>

</div>
