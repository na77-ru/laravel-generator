@extends('generator_views::layouts.package')

@section('content')

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5>ignored_tables</h5>
                        @foreach(config('alex-claimer-generator.config.ignored_tables') as $ignored_tables )
                            {{$ignored_tables}} &nbsp; &nbsp;
                        @endforeach
                        <br><br>


                        @php
                            $namespace_postfix = config('alex-claimer-generator.config.namespace_postfix')
                        @endphp
                        <h5>namespace postfix - {{$namespace_postfix}}</h5>
                        <h6>model namespace -
                            @foreach(config('alex-claimer-generator.config.model') as $model )
                                {{$model . '\\' . $namespace_postfix}}<br>
                            @endforeach</h6>
                        <h6>controller namespace -
                            @foreach(config('alex-claimer-generator.config.controller') as $controller )
                                {{$controller . '\\' . $namespace_postfix}}<br>
                            @endforeach</h6>
                        <h6>repository namespace -
                            @foreach(config('alex-claimer-generator.config.repository') as $repository )
                                {{$repository . '\\' . $namespace_postfix}}<br>
                            @endforeach</h6>
                        <h6>observer namespace -
                            @foreach(config('alex-claimer-generator.config.observer') as $observer )
                                {{$observer . '\\' . $namespace_postfix}}<br>
                            @endforeach</h6>
                        <h6>request namespace -
                            @foreach(config('alex-claimer-generator.config.request') as $request )
                                {{$request . '\\' . $namespace_postfix}}<br>
                            @endforeach</h6>
                        <h6>view namespace -
                            @foreach(config('alex-claimer-generator.config.view') as $view )
                                {{$view . '\\' . $namespace_postfix}}<br>
                            @endforeach</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @php $only_this_table = config('alex-claimer-generator.config.only_this_table'); @endphp
                        <h5>only this table
                            @if(count($only_this_table))</h5>
                        <h6>
                            @foreach($only_this_table as $table )
                                {{ $table }}<br>
                            @endforeach
                        </h6>
                        @else
                            - empty array
                        @endif

                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6>generate_models -
                            {{config('alex-claimer-generator.config.generate_models') ? 'true': 'false'}}<br>
                        </h6>
                        <h6>generate_controllers -
                            {{config('alex-claimer-generator.config.generate_controllers') ? 'true': 'false'}}<br>
                        </h6>
                        <h6>generate_observers -
                            {{config('alex-claimer-generator.config.generate_observers') ? 'true': 'false'}}<br>
                        </h6>
                        <h6>generate_repositories -
                            {{config('alex-claimer-generator.config.generate_repositories') ? 'true': 'false'}}<br>
                        </h6>
                        <h6>generate_requests -
                            {{config('alex-claimer-generator.config.generate_requests') ? 'true': 'false'}}<br>
                        </h6>
                        <h6>generate_views -
                            {{config('alex-claimer-generator.config.generate_views') ? 'true': 'false'}}<br>
                        </h6>
                        <br>
                        <h6>only_table_with_prefix -
                            {{config('alex-claimer-generator.config.only_table_with_prefix') ? 'true': 'false'}}<br>
                        </h6>
                        <h6>table_prefix -
                            {{config('alex-claimer-generator.config.table_prefix')}}<br>
                        </h6>

                    </div>
                </div>
            </div>
        </div>

        @include('generator_views::inc.generate_form')

    </div>
@endsection

