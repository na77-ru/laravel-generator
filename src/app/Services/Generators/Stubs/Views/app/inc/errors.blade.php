@php /**@var Illuminate\Support\ViewErrorBag $errors */ @endphp


@if($errors !== null && $errors->any())
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        <br>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endif
