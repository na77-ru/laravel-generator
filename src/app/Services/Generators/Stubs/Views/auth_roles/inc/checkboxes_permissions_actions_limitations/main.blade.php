
@foreach($auth_permissionsList as $permission)
    <div class="card">
        <div class="card-header">{{__($permission->name)}}</div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="form-group">

                    @include('admin.auth.auth_roles.inc.checkboxes_permissions_actions_limitations.checkboxes_action',
                                           ['permission' => $permission])

                </div>
            </div>
        </div>
    </div>
@endforeach
