@extends('admin.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>auth_roles</h3>
                    </div>
                </div>
            </div>
        </div>
        <br>
                @include('inc.msg')
                @include('inc.errors')

        <div class="row justify-content-center">
            <div class="col-md-12">

                @if($paginator->total() > $paginator->count())

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    {{ $paginator->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="card">
                                <div class="card-body">
                                    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                                        <a class="btn btn-success" href="{{route('admin_auth_roles.create')}}">{{ __('auth_roles').__(' create') }}</a>
                                    </nav>
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                        <a class="btn btn-success" href="{{route('admin_auth_roles.create')}}">{{ __('auth_roles').__(' create') }}</a>
                    </nav>
                @endif
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
								<th>#</th>
								<th class='col'>{{ __('name') }}</th>
{{--								<th class='rel'>{{ __('permissions') }}</th>--}}
{{--								<th class='rel'>{{ __('actions') }}</th>--}}
{{--								<th class='rel'>{{ __('limitations') }}</th>--}}
								<th class='rel'>{{ __('users') }}</th>
								<th class='col'>{{ __('created_at') }}</th>
								<th class='col'>{{ __('updated_at') }}</th>
								<th class='col'>{{ __('deleted_at') }}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paginator as $item)
                                @php /**@var App\Models\Admin\AuthAuthRole $item */ @endphp

			<tr @if(!empty($item->deleted_at) || !empty($item->parent->deleted_at))
                style="color:red;"
            @endif>
			<td><a href="{{route('admin_auth_roles.edit', $item->id)}}">
                                            {{ $item->id }}
                </a>
           </td>
			<td>{{ $item->name }}</td>
{{--								<td>--}}
{{--									@include('inc.form.relations', ['relations' => $item->permissions]) --}}
{{--								</td>--}}
{{--								<td>--}}
{{--									@include('inc.form.relations', ['relations' => $item->actions]) --}}
{{--								</td>--}}
{{--								<td>--}}
{{--									@include('inc.form.relations', ['relations' => $item->limitations]) --}}
{{--								</td>--}}
								<td>
									@include('inc.form.relations', ['relations' => $item->users]) 
								</td>
			<td>{{ $item->created_at }}</td>
			<td>{{ $item->updated_at }}</td>
			<td>{{ $item->deleted_at }}</td>
</tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($paginator->total() > $paginator->count())
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            {{ $paginator->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

