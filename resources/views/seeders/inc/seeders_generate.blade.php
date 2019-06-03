<div class="row justify-content-center">
    <div class="col-md-12">

        <form method="POST" action="{{ route('generator_store_seeders') }}">
            @method('PATCH')
            @csrf
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="prefix">{{ __('tables prefix') }}</label>--}}
{{--                        <input name="prefix" value="{{ old('prefix') }}"--}}
{{--                               id="prefix"--}}
{{--                               type="text"--}}
{{--                               class="form-control"--}}
{{--                               minlength="3"--}}
{{--                        >--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


                    <div class="card">
                        <div class="card-body">
                            <button type="submit"
                                    class="btn btn-success">{{__('Generate seeders')}}</button>
                        </div>
                    </div>




        </form>

    </div>
</div>

