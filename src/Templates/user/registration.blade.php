{{-- Part of Front project. --}}

<?php
\Phoenix\Script\PhoenixScript::formValidation('#user-form');
?>

@extends($warderExtends)

@section('content')
    <div class="container warder-page registration-page">
        <div class="row">

            @section('registration-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('registration') }}" method="POST" enctype="multipart/form-data">
                    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">

                        @yield('registration-desc')

                        @foreach ($fieldsets as $fieldset)
                            <fieldset>
                                <legend>{{ ucfirst($fieldset) }}</legend>

                                {!! $form->renderFields($fieldset) !!}
                            </fieldset>
                        @endforeach

                        @yield('registration-custom')

                        @section('registration-buttons')
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="login-button btn btn-primary">
                                    @translate($warderPrefix . 'registration.submit.button')
                                </button>
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