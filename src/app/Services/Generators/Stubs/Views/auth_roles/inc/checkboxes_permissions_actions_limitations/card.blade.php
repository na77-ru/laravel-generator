@php /**@var App\Models\Admin\Auth\AuthRole $item */ @endphp

<div class="card">
    <div class="card-header">{{__('try')}}</div>
    <div class="card-body">
        <div class="tab-content" id="myTabContent">
            <div class="form-group">

                @include('admin.auth.auth_roles.inc.checkboxes_permissions_actions_limitations.checkbox')

            </div>
        </div>
    </div>
</div>

