<div>

    <div class="flex gap-2 mb-7">
        <div class="fi-section rounded-xl py-4 px-5 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-60">
            <div class="text-md text-back font-normal">Total Projects</div>
            <div class="text-2xl text-blue-950 font-medium">{{ str()->padLeft(array_sum($projectStatusCounts), 2, 0) }}</div>
        </div>

        <div class="fi-section rounded-xl py-4 px-5 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-60">
            <div class="text-md text-back font-normal">Upcoming Projects</div>
            <div class="text-2xl text-blue-950 font-medium">{{ str()->padLeft($projectStatusCounts['upcoming'] ?? 0, 2, 0) }}</div>
        </div>

        <div class="fi-section rounded-xl py-4 px-5 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-60">
            <div class="text-md text-back font-normal">Active Projects</div>
            <div class="text-2xl text-blue-950 font-medium">{{ str()->padLeft($projectStatusCounts['active'] ?? 0, 2, 0) }}</div>
        </div>

        <div class="fi-section rounded-xl py-4 px-5 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-60">
            <div class="text-md text-back font-normal flex items-center">
                GSR Projects

                <div class="relative inline-block ml-2 group cursor-pointer">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4C51BF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>

                   <div id="tooltip-hover" role="tooltip" class="invisible group-hover:visible absolute bg-black text-white px-3 py-1 rounded text-center top-7 w-60">
                        General Services Retainer
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <div class="text-2xl text-blue-950 font-medium">{{ str()->padLeft($projectStatusCounts['gsr'] ?? 0, 2, 0) }}</div>


        </div>

        <div class="fi-section rounded-xl py-4 px-5 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-60">
            <div class="text-md text-back font-normal">Closed Projects</div>
            <div class="text-2xl text-blue-950 font-medium">{{ str()->padLeft($projectStatusCounts['closed'] ?? 0, 2, 0) }}</div>
        </div>
    </div>

    {{ $this->table }}
</div>
