@php /**@var App\Models\Admin\Auth\AuthAction $action */ @endphp
@php /**@var App\Models\Admin\Auth\AuthLimitation $limitation */ @endphp

@foreach($auth_limitationsList as $limitation)
    @php
        $per = $permissions
                ->where('role_id', $item->id)
                ->where('permission_id', $permission->id)
                ->where('action_id', $action->id)
                ->where('limitation_id', $limitation->id)
                ->first();
    @endphp
    {{--                        <label>{{__('try')}}</label>--}}
    <div class="checkbox">
        <label>
            <input type="hidden" name="permissions[{{$permission->id}}][{{ $action->id}}][{{ $limitation->name}}]"
                   value="0">
            <input type="checkbox" value="{{ $limitation->id }}"
                   name="permissions[{{$permission->id}}][{{ $action->id}}][{{ $limitation->name}}]"
                   @if(isset($per))
                        checked="checked"
                    @endif
            >
            {{ $limitation->name }}


        </label>
    </div>
@endforeach

