<div class="flex py-4 first:pt-0 last:pb-0">
    <img class="h-10 w-10 rounded-full" src="{{$getRecord()->getUserAvatar($getRecord()->user) }}" alt="" />

    @if($getRecord()->user)

        <div class="ml-3 overflow-hidden">
            <p class="text-sm font-medium text-slate-900">{{  $getRecord()->user->name }}</p>
            <p class="text-sm text-slate-500 truncate">{{  $getRecord()->user->email }}</p>
        </div>
    @else
        <div class="ml-3 overflow-hidden">
            <p class="text-sm font-normal text-slate-500">Unassigned</p>
            <p class="text-sm truncate">
                <a href="javascript:void(0)" wire:click="assignToMe('{{$getRecord()->id}}')" class="text-blue-600 font-medium">Assign to me</a>
            </p>
        </div>
    @endif
</div>
