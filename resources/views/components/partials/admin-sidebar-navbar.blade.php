<div class="relative">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200 relative">
        <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden">

        </div>
        <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="shadow-lg fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-white lg:translate-x-0 lg:static lg:inset-0 ">
            <div class="flex items-center justify-center mt-8">
                <div class="flex items-center">
                    <img class="max-w-40 md:mr-2" src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}">
                </div>
            </div>

            <nav class="mt-10">

                @php
                    $sidemenues = [
                        "Dashboard" => [
                            "url" => route('dashboard'),
                            "icon" => "la la-chart-pie",
                            "active" => request()->routeIs("dashboard"),
                            "visible" => true,
                        ],
                        "All Users" => [
                            "url" => route('users.listing'),
                            "icon" => "la la-user-friends",
                            "active" => request()->routeIs("users.*"),
                            "visible" => auth()->user()->is_senior_member
                        ],
                        "All Projects" => [
                            "url" => route('projects.listing'),
                            "icon" => "la la-project-diagram",
                            "active" => request()->routeIs("projects.*"),
                            "visible" => auth()->user()->is_senior_member
                        ],
                    ];
                @endphp
                @foreach ($sidemenues as $name => $menu)
                    @if($menu['visible'])
                        <a class="flex items-center px-6 py-4 bg-opacity-75 hover:bg-opacity-75 @if($menu['active']) font-semibold text-blue-800 bg-slate-100 @else text-gray-500  hover:text-blue-800 hover:bg-slate-100 @endif" href="{{ $menu['url'] }}">
                            <i class="{{ $menu['icon'] }} text-2xl"></i>

                            <span class="mx-3 text-xl">{{ $name }}</span>
                        </a>
                    @endif
                @endforeach

            </nav>
        </div>
        <div class="flex flex-col flex-1 overflow-hidden">
            <nav class="bg-white">
                <div class="mx-auto px-2 sm:px-6 lg:px-8 border-blue-700 border-b-2">
                  <div class="relative flex h-20 items-center justify-between">
                    <div class="flex flex-1 h-full items-center sm:items-stretch">
                      <div  @click="sidebarOpen = !sidebarOpen" class="cursor-pointer md:hidden p-2 w-10 h-10 shadow rounded-lg  outline-none inline-flex items-center justify-center mr-4">
                        <i class="fa fa-bars"></i>
                      </div>
                      <div class="flex flex-shrink-0 items-center w-full gap-x-3">
                            {{-- <img class="max-w-40 md:mr-2" src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}"> --}}

                            @php
                                if(request()->route()->parameter('project')){
                                    request()->merge(['project' => request()->route()->parameter('project')->id]);
                                }
                            @endphp

                            @if((request()->is('projects/*') || request()->is('tasks*')) && request()->filled('project'))

                                <div class="hidden w-auto relative mx-3 h-full md:inline-flex items-center">
                                    <button class="cursor-pointer text-left w-full h-[60px] pl-5 pr-7 pb-1 border-2 border-indigo-300 rounded-lg z-10  bg-indigo-600 bg-opacity-10 pointer-events-none">
                                        <div class="flex items-center">
                                            <div class="mr-3">
                                                <i class="fa-solid fa-sheet-plastic text-indigo-600 text-2xl pt-2"></i>
                                            </div>
                                            <div>
                                                <span class="text-xs text-indigo-600">Selected Project</span>

                                                <p class=" text-indigo-600 text-sm font-medium whitespace-nowrap">{{ \App\Models\Project::find(request()->get('project'))->name }}</p>

                                            </div>
                                        </div>

                                        {{-- <i class="absolute right-3 top-[50%] translate-y-[-50%] la la-chevron-down"></i> --}}
                                    </button>
                                </div>

                                <a href="{{ route('projects.details', ['project' => request()->get('project')]) }}" class="rounded-md px-3 py-2 text-md @if(request()->routeIs("projects.details")) font-medium bg-gray-100 text-indigo-600 @else text-slate-700 hover:bg-gray-100 hover:text-indigo-900 @endif"> <i class="fa-solid fa-circle-info"></i> Project Details</a>

                                @if(auth()->user()->is_senior_member)
                                    <a href="{{ route('projects.details.reports', ['project' => request()->get('project')]) }}" class="rounded-md px-3 py-2 text-md @if(request()->routeIs("projects.details.reports")) font-medium bg-gray-100 text-indigo-600 @else text-slate-700 hover:bg-gray-100 hover:text-indigo-900 @endif"><i class="fa-solid fa-file-invoice"></i> Reports</a>
                                @endif

                                <a href="{{ route('tasks.listing', ['project' => request()->get('project')]) }}" class="rounded-md px-3 py-2 text-md @if(request()->routeIs("tasks.listing")) font-medium bg-gray-100 text-indigo-600 @else text-slate-700 hover:bg-gray-100 hover:text-indigo-900 @endif"><i class="fa-solid fa-list-check"></i> Tasks</a>
                                <a href="{{ route('tasks-board', ['project' => request()->get('project')]) }}" class="rounded-md px-3 py-2 text-md @if(request()->routeIs("tasks-board")) font-medium  bg-gray-100 text-indigo-600 @else text-slate-700 hover:bg-gray-100 hover:text-indigo-900 @endif"><i class="fa-solid fa-border-all"></i> Tasks Board</a>

                                @if(auth()->user()->is_senior_member)
                                    <a href="{{ route('projects.worklogs.listing', ['project' => request()->get('project')]) }}" class="rounded-md px-3 py-2 text-md @if(request()->routeIs("projects.worklogs.listing")) font-medium  bg-gray-100 text-indigo-600 @else text-slate-700 hover:bg-gray-100 hover:text-indigo-900 @endif"><i class="fa-solid fa-briefcase"></i> Worklogs</a>
                                @endif

                                {{-- <div class="hidden w-[220px] relative mx-3 h-full md:inline-flex items-center">
                                    <select class="cursor-pointer absolute outline-none h-[60px] focus:outline-none focus:ring-0 w-full border-1 border-gray-200 rounded-lg z-10 change-project bg-white" onchange="window.location = `{{route('tasks.listing')}}?project=${this.value}`">
                                        <option selected hidden> Change Project</option>
                                        @foreach (\App\Models\Project::pluck('name', 'id') as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="cursor-pointer absolute  text-left w-full border h-[60px] px-4 py-2 border-gray-200 rounded-lg z-10 change-project bg-white pointer-events-none">
                                        <span class="text-xs">Selected Project</span>
                                        <p class="text-orange-600 text-sm font-medium whitespace-nowrap">{{ \App\Models\Project::find(request()->get('project'))->name }}</p>

                                        <i class="absolute right-3 top-[50%] translate-y-[-50%] la la-chevron-down"></i>
                                    </button>
                                </div> --}}
                            @endif
                      </div>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

                      <!-- Profile dropdown -->
                      <div class="relative ml-3" x-data="{ dropdownOpen: false }">
                        <div>
                          <button type="button" @click="dropdownOpen = ! dropdownOpen" class="relative flex rounded-full bg-white items-center text-sm" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full" src="{{ auth()->user()->avatar }}" alt="">
                            <span class="ml-3">{{ auth()->user()->name }}</span>
                          </button>
                        </div>

                        <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-screen w-screen z-10"
                        style="display: none;"></div>

                        <div x-show="dropdownOpen" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                          <!-- Active: "bg-gray-100", Not Active: "" -->
                            {{-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profile</a> --}}
                            <a href="{{route('logout')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Logout</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </nav>


            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container px-6 py-8 mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>

    </div>
</div>
