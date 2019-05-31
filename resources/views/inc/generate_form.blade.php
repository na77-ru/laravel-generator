<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <form method="POST" action="{{ route('alex-claimer-generate-patch') }}">
                @method('PATCH')
                @csrf
                <div class="card-body">
                    <button type="submit"
                            class="btn btn-success">{{__('Generate classes')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
