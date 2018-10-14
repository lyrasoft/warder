{{-- Part of Front project. --}}

<?php
\Phoenix\Script\PhoenixScript::formValidation('#user-form');

$basicFieldset = array_shift($fieldsets);
?>

@extends($warder->extends)

@section('content')
    <div class="container warder-page profile-page">
        <div class="row">

            <div class="col-md-6 col-md-offset-3 mx-auto" style="margin-top: 50px">
            @section('profile-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('profile_edit') }}" method="POST"
                      enctype="multipart/form-data">


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
                            <div class="profile-edit-actions">
                                <button type="submit" class="login-button btn btn-primary btn-block">
                                    @translate($warder->langPrefix . 'profile.submit.button')
                                </button>
                            </div>
                        @show
                    </div>

                    <div class="hidden-inputs">
                        @formToken
                    </div>
                </form>
            @show

        </div>
    </div>
@stop
