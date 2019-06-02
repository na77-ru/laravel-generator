<div class="row justify-content-center">
    <div class="col-md-12">

        <form method="POST" action="{{ route('generator_store_migration') }}">
            @method('PATCH')
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="prefix">{{ __('tables prefix') }}</label>
                        <input name="prefix" value="{{ old('prefix') }}"
                               id="prefix"
                               type="text"
                               class="form-control"
                               minlength="3"
                        >
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

                    <div class="card-title"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#table_1"
                               role="tab">{{ __('First table') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#table_2"
                               role="tab">{{ __('Second table') }}</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">


                        <div class="tab-pane fade show  active" id="table_1" role="tabpanel"
                             aria-labelledby="home-tab">

                            @include('generator_views::inc.migration_generate_form', ['id' => '_1'])

                        </div>
                        <div class="tab-pane fade" id="table_2" role="tabpanel" aria-labelledby="profile-tab">

                            @include('generator_views::inc.migration_generate_form', ['id' => '_2'])

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="hidden" name="pivot" value="0">
                                        <input type="checkbox" value="1"
                                               name="pivot" checked="checked">
                                        {{ __('add pivot table') }}
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="hidden" name="only_pivot" value="0">
                                        <input type="checkbox" value="1"
                                               name="only_pivot"
                                           @if(old('only_pivot'))
                                               checked="checked"
                                            @endif
                                        >
                                        {{ __('only pivot table') }}
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <button type="submit"
                                    class="btn btn-success">{{__('Generate migration')}}</button>
                        </div>
                    </div>


                </div>
            </div>
        </form>

    </div>
</div>

