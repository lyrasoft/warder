{{-- Part of Front project. --}}

@extends($warderExtends)

@section('content')
    <div class="container warder-page forget-confirm-page">
        <div class="row">

            @section('login-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->html('forget_confirm') }}" method="POST" enctype="multipart/form-data">
                    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">

                        @yield('forget-confirm-desc')

                        {!! $form->renderFields() !!}

                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <p class="confirm-button-group">
                                    <button class="confirm-button btn btn-primary">
                                        @translate($warderPrefix . 'forget.confirm.submit.button')
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="hidden-inputs">
                        {!! \Windwalker\Core\Security\CsrfProtection::input() !!}
                    </div>
                </form>
            @show

        </div>
    </div>
@stop