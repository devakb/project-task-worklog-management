<div>

    {{ $this->detailsPanel() }}

    <div class="mt-5">
        @livewire("projects.project-members", compact('project'))
    </div>
</div>
