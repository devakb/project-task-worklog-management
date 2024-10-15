<div>
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-slate-700">Worklogs</h1>
    </div>

    {{ $this->form }}

    <section class="mt-5 fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-section-content-ctn">
            <div class="fi-section-content p-6">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-5 sticky bg-gray-50 font-semibold text-sm left-0">
                                User
                                </th>
                                <th scope="col" class="px-6 py-5 sticky bg-gray-50 font-semibold text-sm left-[298px] whitespace-nowrap text-center" style="filter: drop-shadow(3px 0 3px #rgba(0, 0, 0, 0.1));">
                                Total Hours
                                </th>
                                @foreach ($date_wise_columns as $cols)
                                    <th scope="col" id="table-header-date-{{$cols}}" class="px-6 py-5 whitespace-nowrap font-semibold text-sm text-center @if($cols == now()->format("Y-m-d")) border-2 border-indigo-800 bg-indigo-800 text-white @endif">
                                        {{ $cols }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                @php
                                    $record = $records[$user->id][0] ?? null;
                                @endphp
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 group">
                                    <th scope="row" class="px-6 py-5 sticky left-0 bg-white text-sm w-[250px]">
                                        <div class="flex py-4 first:pt-0 last:pb-0  w-[250px]">
                                            <img class="h-10 w-10 rounded-full" src="{{ $user->avatar }}" alt="" />
                                            <div class="ml-3 overflow-hidden">
                                                <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                                                <p class="text-sm text-slate-500 font-normal truncate">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="row" class="px-6 py-5 sticky left-[298px] bg-white text-black text-sm whitespace-nowrap text-center" style="filter: drop-shadow(3px 0 3px rgba(0, 0, 0, 0.1));">
                                        {{ App\Models\WorkLog::convertMinutesToFormat($record->total_logged ?? 0) }}
                                    </th>
                                    @php
                                        $islastRow = $loop->last;
                                    @endphp
                                    @foreach ($date_wise_columns as $cols)
                                        <td class="px-6 py-5 whitespace-nowrap text-center text-sm @if($cols == now()->format("Y-m-d")) border-l-2 border-r-2 @if($islastRow) border-b-2 @endif border-indigo-800 @endif">
                                            @if(!empty($record) && $record->$cols > 0)
                                                <span class="text-blue-800 font-bold">{{ App\Models\WorkLog::convertMinutesToFormat($record->$cols) }}</span>
                                            @else
                                                <span class="text-slate-300">0 h</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%">
                                        <div class="text-center pt-14 pb-8">No Records Found</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
