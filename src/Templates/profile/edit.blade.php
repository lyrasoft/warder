{{-- Part of Front project. --}}

<?php
\Phoenix\Script\PhoenixScript::formValidation('#user-form');

$basicFieldset = array_shift($fieldsets);
?>

@extends($warderExtends)

@section('content')
    <div class="container warder-page profile-page">
        <div class="row">

            @section('profile-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('profile_edit') }}" method="POST" enctype="multipart/form-data">
                    <div class="col-md-6 col-md-offset-3" style="margin-top: 50px">

                        @yield('profile-desc')

                        <fieldset>
                            {!! $form->renderFields($basicFieldset) !!}
                        </fieldset>

                        @foreach ($fieldsets as $fieldset)
                            <fieldset>
                                <legend>{{ ucfirst($fieldset) }}</legend>

                                {!! $form->renderFields($fieldset) !!}
                            </fieldset>
                        @endforeach

                        @yield('profile-custom')

                        @section('profile-buttons')
                            <div class="row">
                                <div class="col-md-offset-4 col-md-8">
                                    <button type="submit" class="login-button btn btn-primary">
                                        @translate($warderPrefix . 'profile.submit.button')
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