@php /**@var App\Models\Admin\Auth\AuthAction $action */ @endphp

<table class="table">
    <thead>
    <tr>
        @foreach($auth_actionsList as $action)
            <th>{{__($action->name)}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($auth_actionsList as $action)

            <td>
{{--                @include('admin.auth.auth_roles.inc.checkboxes_permissions_actions_limitations.checkboxes_limitation',--}}
{{--           ['permission' => $permission, 'action' => $action,])--}}
                @include('admin.auth.auth_roles.inc.checkboxes_permissions_actions_limitations.radio_buttons_limitation',
           ['permission' => $permission, 'action' => $action,])
            </td>

        @endforeach
    </tr>
    </tbody>
</table>
