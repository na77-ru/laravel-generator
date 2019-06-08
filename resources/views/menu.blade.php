@extends('generator_views::layouts.package')

@section('content')
    @include('generator_views::inc.errors')
    @include('generator_views::inc.msg')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Menu Generators</h3>
                    </div>
                </div>
            </div>
        </div>
        @include('generator_views::inc.generate_form')

    </div>
@endsection

