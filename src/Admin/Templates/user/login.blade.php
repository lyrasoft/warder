{{-- Part of Front project. --}}

@extends($warderExtends)

@section('content')
<div class="container warder-page login-page">
    <div class="row">

        @section('login-content')
            <form id="user-form" class="form-horizontal" action="{{ $router->html('login') }}" method="POST" enctype="multipart/form-data">
                <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">

                    @section('message')
                        {!! \Windwalker\Core\Widget\WidgetHelper::render('windwalker.message.default', array('flashes' => $flashes)) !!}
                    @show

                    @yield('login-desc')

                    {!! $form->renderFields() !!}

                    @section('login-buttons')
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <p class="login-button-group">
                                <button class="login-button btn btn-primary">
                                    @translate($warderPrefix . 'login.submit.button')
                                </button>
                            </p>
                        </div>
                    </div>
                    @show
                </div>

                <div class="hidden-inputs">
                    {!! \Windwalker\Core\Security\CsrfProtection::input() !!}
                </div>
            </form>
        @show

    </div>
</div>
@stop
