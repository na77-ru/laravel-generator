@extends('generator_views::layouts.package')
@php
    $checkboxes = [
        'id',
        'slug',
        'title',
        'description',
        'active',
        'is_published',
        'published_at',
        'timestamps',
        'softDeletes',
    ];
@endphp
@section('content')
    @include('generator_views::inc.errors')
    @include('generator_views::inc.msg')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Menu package generator</h3>
                    </div>
                </div>
            </div>
        </div>
        @include('generator_views::package/inc.package_generate')


    </div>
@endsection

