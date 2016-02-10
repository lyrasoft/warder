{{-- Part of Front project. --}}

<?php
$extends = '_global.html';
?>

@extends($extends)

@section('content')
    <div class="container reset-complete-page">
        <div class="container">
            <div class="row">

                @section('login-content')
                    <form id="user-form" class="form-horizontal" action="{{ $router->html('forget_confirm') }}" method="POST" enctype="multipart/form-data">
                        <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">
                            <p class="text-center">
                                Your password has been reset.
                            </p>

                            <div class="row">
                                <div class="text-center">
                                    <p class="login-button-group">
                                        <a class="login-button btn btn-primary" href="{{ $router->html('login') }}">Go to Login</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                @show

            </div>
        </div>
    </div>
@stop