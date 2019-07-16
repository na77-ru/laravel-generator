@extends('admin.app')
{{-- !!!! to look package Former !!!!! --}}
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Create auth_roles</h3>
                    </div>
                </div>
            </div>
        </div>
        <br>

        @php /**@var App\Models\Admin\AuthAuthRole $item */ @endphp
        <form method="POST" action="{{route('admin_auth_roles.store', $item->id) }}">
            @csrf

            @include('inc.errors')
            @include('inc.msg')

            <div class="row justify-content-center">
                <div class="col-md-8">
                    @include('admin.auth.auth_roles.inc.columns_for_edit')
                </div>
                <div class="col-md-4">
                    @include('admin.auth.auth_roles.inc.edit_add_col')
                </div>
            </div>

        </form>
    </div>
@endsection
