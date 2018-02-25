{{-- Part of Front project. --}}

@extends($warder->noauthExtends)

@section('content')
    <div class="container warder-page reset-complete-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 mx-md-auto" style="margin-top: 50px">
            @section('login-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('forget_confirm') }}"
                      method="POST" enctype="multipart/form-data">
                    <p class="text-center lead">
                        @yield('forget-complete-desc', $translator->translate($warder->langPrefix . 'forget.complete.desc'))
                    </p>

                    @yield('forget-complete-custom')

                    <div class="text-center">
                        <p class="go-login-button-group">
                            <a class="go-login-button btn btn-primary btn-block" href="{{ $router->route('login') }}">
                                @translate($warder->langPrefix . 'forget.complete.go.login.button')
                            </a>
                        </p>
                    </div>
                </form>
            @show
            </div>
        </div>
    </div>
@stop
