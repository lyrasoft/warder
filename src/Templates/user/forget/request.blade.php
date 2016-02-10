{{-- Part of Front project. --}}

<?php
$extends = '_global.html';
?>

@extends($extends)

@section('content')
    <div class="container forget-request-page">
        <div class="container">
            <div class="row">

                @section('login-content')
                    <form id="user-form" class="form-horizontal" action="{{ $router->html('forget_request') }}" method="POST" enctype="multipart/form-data">
                        <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
                            {!! $form->renderFields() !!}

                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <p class="login-button-group">
                                        <button class="login-button btn btn-primary">Send</button>
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
    </div>
@stop
