@if(session('messages'))
    @php $alertType = session('alert-type') ?? 'alert-success'; @endphp
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="alert {{$alertType}}" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    @if(is_string(session()->get('messages')))
                        {!!session()->get('messages') !!}
                    @elseif(is_array(session()->get('messages')))
                        @foreach(session()->get('messages') as $msg)
                            {!! $msg !!}
                            <br>
                        @endforeach
                    @endif

                </div>
                {{--            {{ Form::bsAlertSuccess(session()->get('success')) }}--}}
            </div>
        </div>
    </div>
@endif

{{--<script src="{{ asset('vendor/backpack/pnotify/pnotify.custom.min.js') }}"></script>--}}

{{-- {{-- Bootstrap Notifications using Prologue Alerts -}} --}}
{{--<script type="text/javascript">--}}
{{--    jQuery(document).ready(function($) {--}}

{{--        PNotify.prototype.options.styling = "bootstrap3";--}}
{{--        PNotify.prototype.options.styling = "fontawesome";--}}

{{--        @foreach (Alert::getMessages() as $type => $messages)--}}
{{--        @foreach ($messages as $message)--}}

{{--        $(function(){--}}
{{--            new PNotify({--}}
{{--                // title: 'Regular Notice',--}}
{{--                text: "{!! str_replace('"', "'", $message) !!}",--}}
{{--                type: "{{ $type }}",--}}
{{--                icon: false--}}
{{--            });--}}
{{--        });--}}

{{--        @endforeach--}}
{{--        @endforeach--}}
{{--    });--}}
{{--</script>--}}
