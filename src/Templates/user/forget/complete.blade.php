{{-- Part of Front project. --}}

@extends($warderExtends)

@section('content')
    <div class="container warder-page reset-complete-page">
        <div class="row">

            @section('login-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('forget_confirm') }}" method="POST" enctype="multipart/form-data">
                    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
                        <p class="text-center">
                            @yield('forget-complete-desc', $translator->translate($warderPrefix . 'forget.complete.desc'))
                        </p>

                        @yield('forget-complete-custom')

                        <div class="row">
                            <div class="text-center">
                                <p class="go-login-button-group">
                                    <a class="go-login-button btn btn-primary" href="{{ $router->route('login') }}">
                                        @translate($warderPrefix . 'forget.complete.go.login.button')
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            @show

        </div>
    </div>
@stop