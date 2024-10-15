<x-layouts.app>

    <section class="mt-5 fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-section-content-ctn">
            <div class="fi-section-content p-6">
                <b>Welcome! </b> <span>{{ auth()->user()->name }}</span>
            </div>
        </div>
    </section>

    <section class="mt-5 fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-section-content-ctn">
            <div class="fi-section-content p-6">
                <h4 class="font-semibold text-lg mb-10">My Projects</h4>

                <div class="flex flex-wrap justify-start gap-10">
                    @foreach (App\Models\Project::whereIn('id', App\Models\ProjectMember::where('user_id', auth()->id())->where('is_allocated', true)->pluck('project_id'))->get() as $project)
                        <div class="w-[200px]">
                            <div class="text-center flex flex-col items-center">
                                <img src="https://api.dicebear.com/9.x/identicon/svg?scale=80&seed={{ urlencode($project->name) }}" class="w-[50px] h-[50px] mb-4" alt="">

                                <div class="content">
                                    <p class="text-sm text-gray-600">{{ $project->code }}</p>
                                    <h5 class="text-lg font-semibold text-gray-600 mb-2">{{ $project->name }}</h5>
                                    <a href="{{ route('projects.details', $project) }}" class="text-sm text-blue-600 hover:text-blue-700">View Project</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
