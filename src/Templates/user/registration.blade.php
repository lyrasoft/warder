{{-- Part of Front project. --}}

<?php
\Phoenix\Script\PhoenixScript::formValidation('#user-form');

$basicFieldset = array_shift($fieldsets);
?>

@extends($warder->noauthExtends)

@section('content')
    <div class="container warder-page registration-page">
        <div class="row">

            @section('registration-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('registration') }}" method="POST"
                      enctype="multipart/form-data">
                    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">

                        @yield('registration-desc')

                        <fieldset>
                            {!! $form->renderFields($basicFieldset) !!}
                        </fieldset>

                        @foreach ($fieldsets as $fieldset)
                            <fieldset>
                                <legend>{{ ucfirst($fieldset) }}</legend>

                                {!! $form->renderFields($fieldset) !!}
                            </fieldset>
                        @endforeach

                        @yield('registration-custom')

                        @section('registration-buttons')
                            <div class="registration-actions">
                                <button type="submit" class="login-button btn btn-primary btn-block">
                                    @translate($warder->langPrefix . 'registration.submit.button')
                                </button>
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
