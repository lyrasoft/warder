{{-- Part of Front project. --}}

@extends($warder->noauthExtends)

@section('content')
    <div class="container warder-page forget-request-page">
        <div class="row">

            @section('login-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('forget_request') }}"
                      method="POST" enctype="multipart/form-data">
                    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">

                        @yield('forget-request-desc')

                        {!! $form->renderFields() !!}

                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <p class="reset-button-group">
                                    <button class="request-button btn btn-primary">
                                        @translate($warder->langPrefix . 'forget.request.submit.button')
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="hidden-inputs">
                        @formToken()
                    </div>
                </form>
            @show

        </div>
    </div>
@stop
