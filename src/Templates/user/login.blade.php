{{-- Part of Front project. --}}

<?php
$extends = '_global.html';
?>

@extends($extends)

@section('content')
<div class="container login-page">
    <div class="container">
        <div class="row">

            @section('login-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->html('login') }}" method="POST" enctype="multipart/form-data">
                    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
                        {!! $form->renderFields() !!}

                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <p class="login-button-group">
                                    <button class="login-button btn btn-primary">Login</button>
                                    <a class="go-register-button btn btn-success" href="{{ $router->html('registration') }}">Registration</a>
                                </p>

                                <p class="login-action-group">
                                    <a class="forget-link" href="{{ $router->html('forget_request') }}">Forget password?</a>
                                </p>
                            </div>
                        </div>

                        <div class="form-group text-right forget-group">

                        </div>
                    </div>

                    <div class="hidden-inputs">
                        {!! \Windwalker\Core\Security\CsrfProtection::input() !!}
                    </div>
                </form>
            @show

        </div>
    </div>
</div>
@stop
