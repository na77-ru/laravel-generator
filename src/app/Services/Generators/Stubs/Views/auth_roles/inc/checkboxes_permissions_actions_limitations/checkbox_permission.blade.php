@php /**@var App\Models\Admin\Auth\AuthPermission $permission */ @endphp
{{--                        <label>{{__('try')}}</label>--}}
{{--                <div class="checkbox">--}}
{{--                    <label>--}}
{{--                        <input type="hidden" name="permissions[{{$permission->name}}]" value="0">--}}
{{--                        <input type="checkbox" value="{{ $permission->id }}"--}}
{{--                               name="permissions[{{$permission->name}}]"--}}
{{--                               checked="checked"--}}
{{--                        >--}}
{{--                        {{ $permission->name }}--}}
{{--                    </label>--}}
{{--                </div>--}}


<input type="hidden" name="permissions[{{$permission->name}}]" value="{{ $permission->id }}">
