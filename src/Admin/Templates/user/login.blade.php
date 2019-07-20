{{-- Part of Front project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \Lyrasoft\Warder\WarderPackage              Package object.
 * @var $view     \Windwalker\Data\Data                       Some information of this view.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime \DateTime                                   PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\PackageRouter       Router object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 *
 * View variables
 * --------------------------------------------------------------
 * @var $state    \Windwalker\Structure\Structure
 * @var $form     \Windwalker\Form\Form
 */

\Phoenix\Script\BootstrapScript::checkbox(\Phoenix\Script\BootstrapScript::FONTAWESOME);
\Phoenix\Script\PhoenixScript::validation('#user-form');
\Phoenix\Script\PhoenixScript::disableWhenSubmit('#user-form');

$form->setAttributes('labelWidth', 'col-md-12', 'login')
    ->setAttributes('fieldWidth', 'col-md-12', 'login');
?>

@extends($warder->noauthExtends)

@section('content')
    <div class="container warder-page login-page">
        <div class="row">

            @section('login-content')
                <div class="col-md-6 col-md-offset-3 mx-md-auto" style="margin-top: 50px">
                    <form id="user-form" class="form-horizontal" action="{{ $router->route('login') }}" method="POST"
                          enctype="multipart/form-data">

                        @section('message')
                            @messages()
                        @show

                        @yield('login-desc')

                        {!! $form->renderFields('login') !!}

                        <div id="input-user-remember-control" class="checkbox-field" style="margin-bottom: 20px">
                            <div class="form-check checkbox checkbox-primary">
                                <input name="user[remember]" class="form-check-input" type="checkbox" id="input-user-remember" value="on">
                                <label class="form-check-label" for="input-user-remember">
                                    @translate($warder->langPrefix . 'user.field.remember')
                                </label>
                            </div>
                        </div>

                        @section('login-buttons')
                            <p class="login-button-group">
                                <button class="login-button btn btn-primary btn-block disable-on-submit">
                                    @translate($warder->langPrefix . 'login.submit.button')
                                </button>
                            </p>
                        @show

                        <div class="hidden-inputs">
                            @formToken
                        </div>
                    </form>
                </div>
            @show

        </div>
    </div>
@stop
