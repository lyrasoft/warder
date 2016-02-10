{{-- Part of Front project. --}}

<?php
\Phoenix\Script\PhoenixScript::formValidation('#user-form');

$extends = '_global.html';
?>

@extends($extends)

@section('content')
    <div class="container login-page">
        <div class="container">
            <div class="row">

                @section('login-content')
                    <form id="user-form" class="form-horizontal" action="{{ $router->html('registration') }}" method="POST" enctype="multipart/form-data">
                        <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">

                            @foreach ($fieldsets as $fieldset)
                                <fieldset>
                                    <legend>{{ ucfirst($fieldset) }}</legend>

                                    {!! $form->renderFields($fieldset) !!}
                                </fieldset>
                            @endforeach

                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="login-button btn btn-primary">Register</button>
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