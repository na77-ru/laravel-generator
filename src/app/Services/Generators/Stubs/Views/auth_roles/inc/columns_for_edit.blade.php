@php /**@var App\Models\Admin\Auth\AuthRole $item */ @endphp

@include('admin.auth.auth_roles.inc.checkboxes_permissions_actions_limitations.main')



            <div class="card">
{{--                <div class="card-header"></div>--}}
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <input type="hidden" name="id" value="{{ $item->id }}">


                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" value="{{ old('name', $item->name) }}"
                                   id="name"
                                   type="text"
                                   class="form-control"
                                   minlength="3"
                                   required>
                        </div>

                        @include('inc.form.select_relations',
                     [
                     'relationsList' => $auth_usersList,
                        'relationName' => 'users',
                        'columnName' => 'name'
                      ]
                      )

                    </div>
                </div>
            </div>


