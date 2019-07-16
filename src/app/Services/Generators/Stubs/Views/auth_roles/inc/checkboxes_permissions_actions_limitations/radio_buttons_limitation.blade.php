@php /**@var App\Models\Admin\Auth\AuthAction $action */ @endphp
@php /**@var App\Models\Admin\Auth\AuthLimitation $limitation */ @endphp
@php /**@var App\Models\Admin\Auth\AuthPermission $permission */ @endphp
@php /**@var \Illuminate\Database\Eloquent\Collection $permissions */ @endphp
@php /**@var \Illuminate\Database\Eloquent\Collection $item */ @endphp
@php
    $per = $permissions
            ->where('role_id', $item->id)
            ->where('permission_id', $permission->id)
            ->where('action_id', $action->id)
            ->first();
$checkboxName = 'checkboxes['.$permission->id.']['.$action->id.']';
$checkboxId = $permission->id.'-'.$action->id;
@endphp
<div class="checkbox">
    <label>
        <input type="hidden" name="{{ $checkboxName }}"
               value="0">
        <input type="checkbox" value="{{ $action->id }}"
               class="check-click"
               id="{{ $checkboxId }}"
               name="{{ $checkboxName }}"
               @if(isset($per))
               checked="checked"
            @endif
        >
        {{ $action->name }}


    </label>
</div>
@foreach($auth_limitationsList as $key => $limitation)
    @php
        $per = $permissions
                ->where('role_id', $item->id)
                ->where('permission_id', $permission->id)
                ->where('action_id', $action->id)
                ->where('limitation_id', $limitation->id)
                ->first();
    $radioName = 'permissions['.$permission->id.']['. $action->id.']';
    @endphp
    {{--                        <label>{{__('try')}}</label>--}}
    <div class="form-{{ $item->id }}-{{ $permission->id }}-{{ $action->id }}-group">
        <label class="label-{{ $item->id }}-{{ $permission->id }}-{{ $action->id }}">

            <input type="radio" value="{{ $limitation->id }}"
                   class="radio-{{ $item->id }}-{{ $permission->id }}-{{ $action->id }}-input radio-click"
                   name="{{ $radioName }}"
                   id="{{ $checkboxId }}-radio-{{ $key }}"
                   @if(isset($per))
                   checked="checked"
                @endif
            >
            {{ $limitation->name }}


        </label>
    </div>
@endforeach

