{{-- Part of Front project. --}}

@extends($warder->noauthExtends)

@section('content')
<div class="container warder-page login-page">
    <div class="row">

        @section('login-content')
            <div class="col-md-6 col-md-offset-3 mx-md-auto" style="margin-top: 50px">
                <form id="user-form" class="form-horizontal" action="{{ $router->route('login') }}" method="POST" enctype="multipart/form-data">

                    @section('message')
                        @messages()
                    @show

                    @yield('login-desc')

                    {!! $form->renderFields() !!}

                    @section('login-buttons')
                    <p class="login-button-group">
                        <button class="login-button btn btn-primary btn-block">
                            @translate($warder->langPrefix . 'login.submit.button')
                        </button>
                    </p>
                    @show

                    <div class="hidden-inputs">
                        @formToken()
                    </div>
                </form>
            </div>
        @show

    </div>
</div>
@stop
