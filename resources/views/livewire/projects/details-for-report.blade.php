<div class="mt-3">

    <div class="mb-3">
        {{ $this->form }}
    </div>


    <div class="w-full">
        @livewire(\App\Livewire\Projects\WorklogsChart::class, compact('project') + ["page_filters" => $this->page_filters], key(str()->random(10)))
    </div>

    <div class="flex justify-between mt-3">

        <div class="lg:w-6/12 pl-1">
            @livewire(\App\Livewire\Projects\MembersRoleBasedChart::class, compact('project') + ["page_filters" => $this->page_filters], key(str()->random(10)))
        </div>

        <div class="lg:w-6/12 pl-1">
            @livewire(\App\Livewire\Projects\TasksTypesChart::class, compact('project') + ["page_filters" => $this->page_filters], key(str()->random(10)))
        </div>


    </div>



</div>
