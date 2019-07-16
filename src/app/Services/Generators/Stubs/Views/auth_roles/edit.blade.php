@extends('admin.app')

@section('content')
@php /**@var App\Models\Admin\AuthAuthRole $item */ @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Update auth_roles</h3>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{route('admin_auth_roles.update', $item->id) }}">
            @method('PATCH')
            {{--    @method('PUT')--}}
            @csrf


            @include('inc.errors')
            @include('inc.msg')

            <div class="row justify-content-center">
                <div class="col-md-8">
                    @include('admin.auth.auth_roles.inc.columns_for_edit')

                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <button type="submit" class="btn btn-success">{{__('Save')}}</button>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    @include('admin.auth.auth_roles.inc.edit_add_col')
                </div>
            </div>
        </form>

        @if($item->exists)
            <div class="row justify-content-center">
                @if(!$item->deleted_at)
                    <div class="col-md-12">
                        <div class="card">
                            <form method="POST" action="{{route('admin_auth_roles.destroy', $item->id) }}">
                                @method('DELETE')
                                @csrf
                                <div class="card-body">
                                    <button type="submit"
                                            class="btn btn-danger">{{__('Delete')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                @if($item->deleted_at)
                    <div class="col-md-12">
                        <div class="card">
                            <form method="POST" action="{{route('admin_auth_roles.restore', $item->id) }}">
                                @method('PATCH')
                                @csrf
                                <div class="card-body">
                                    <button type="submit"
                                            class="btn btn-success">{{__('Restore')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection

