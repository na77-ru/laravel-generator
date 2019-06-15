<div class="row justify-content-center">
    <div class="col-md-12">

        <form method="POST" action="{{ route('generator_store_packages') }}">
            @method('PATCH')
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="vendor-name">{{ __('vendor-name') }}</label>
                        <input name="vendor-name" value="{{ old('vendor-name' , 'alex-claimer')}}"
                               id="vendor-name"
                               type="text"
                               class="form-control"
                               minlength="3"
                        >
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="package-name">{{ __('package-name') }}</label>
                        <input name="package-name" value="{{ old('package-name', 'package-name') }}"
                               id="package-name"
                               type="text"
                               class="form-control"
                               minlength="3"
                        >
                    </div>
                </div>
            </div>

            <div class="card">

                <div class="card">
                    <div class="card-body">
                        <button type="submit"
                                class="btn btn-success">{{__('Generate package')}}</button>
                    </div>
                </div>


            </div>

        </form>

    </div>
</div>

