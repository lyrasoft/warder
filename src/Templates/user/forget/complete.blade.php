{{-- Part of Front project. --}}

@extends($parentTemplate)

@section('content')
    <div class="container warder-page reset-complete-page">
        <div class="row">

            @section('login-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->html('forget_confirm') }}" method="POST" enctype="multipart/form-data">
                    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
                        <p class="text-center">
                            @yield('forget-complete-desc', $translator->translate($langPrefix . 'forget.complete.desc'))
                        </p>

                        @yield('forget-complete-custom')

                        <div class="row">
                            <div class="text-center">
                                <p class="login-button-group">
                                    <a class="login-button btn btn-primary" href="{{ $router->html('login') }}">
                                        @translate($langPrefix . 'forget.complete.go.login.button')
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