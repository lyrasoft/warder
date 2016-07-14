{{-- Part of Front project. --}}

@extends($warder->extends)

@section('content')
<div class="container warder-page login-page">
    <div class="row">

        @section('login-content')
            <form id="user-form" class="form-horizontal" action="{{ $router->route('login') }}" method="POST" enctype="multipart/form-data">
                <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">

                    @section('message')
                        @messages()
                    @show

                    @yield('login-desc')

                    {!! $form->renderFields() !!}

                    @section('login-buttons')
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <p class="login-button-group">
                                <button class="login-button btn btn-primary">
                                    @translate($warder->langPrefix . 'login.submit.button')
                                </button>
                            </p>
                        </div>
                    </div>
                    @show
                </div>

                <div class="hidden-inputs">
                    @formToken()
                </div>
            </form>
        @show

    </div>
</div>
@stop
