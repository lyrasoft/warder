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

use Phoenix\Script\BootstrapScript;

BootstrapScript::fontAwesome();
BootstrapScript::checkbox(BootstrapScript::FONTAWESOME);

$form->setAttributes('labelWidth', 'col-md-12', 'login')
    ->setAttributes('fieldWidth', 'col-md-12', 'login');
?>

@extends($warder->noauthExtends)

@section('content')
    <style>
        #social-login-buttons {
            text-align: right;
            margin-top: 40px;
        }

        #social-login-buttons button.btn {
            width: 300px;
        }
    </style>
    <div class="container warder-page login-page">

        @section('login-content')
            <div class="row">
                <div class="col-md-6 col-md-offset-3 mx-md-auto" style="margin-top: 50px">
                    <form id="user-form" class="form-horizontal" action="{{ $router->route('login') }}" method="POST"
                          enctype="multipart/form-data">

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
                            <div class="login-buttons">
                                <p class="login-button-wrap">
                                    <button class="login-button btn btn-primary btn-block">
                                        <span class="fa fa-sign-in"></span>
                                        @translate($warder->langPrefix . 'login.submit.button')
                                    </button>
                                </p>
                                <p class="register-button-wrap">
                                    <a class="go-register-button btn btn-success btn-block"
                                        href="{{ $router->route('registration') }}">
                                        <span class="fa fa-user-plus"></span>
                                        @translate($warder->langPrefix . 'login.register.button')
                                    </a>
                                </p>
                                <p class="login-action-wrap">
                                    <a class="forget-link" href="{{ $router->route('forget_request') }}">
                                        @translate($warder->langPrefix . 'login.forget.link')
                                    </a>
                                </p>
                            </div>
                        @show

                        @section('social-login')

                            <div class="social-login-buttons row" style="margin-top: 50px">
                                @if ($app->get('social_login.facebook.enabled'))
                                    <p class="col-md-4">
                                        <button class="social-login-facebook-button btn btn-primary btn-block"
                                                onclick="jQuery('#user-form').attr('action', '{{ $router->route('social_login', array('provider' => 'facebook')) }}')">
                                            <span class="fa fab fa-fw fa-facebook-square"></span>
                                            Facebook
                                        </button>
                                    </p>
                                @endif

                                @if ($app->get('social_login.twitter.enabled'))
                                    <p class="col-md-4">
                                        <button class="social-login-twitter-button btn btn-info btn-block"
                                                onclick="jQuery('#user-form').attr('action', '{{ $router->route('social_login', array('provider' => 'twitter')) }}')">
                                            <span class="fa fab fa-fw fa-twitter"></span>
                                            Twitter
                                        </button>
                                    </p>
                                @endif

                                @if ($app->get('social_login.google.enabled'))
                                    <p class="col-md-4">
                                        <button class="social-login-google-button btn btn-danger btn-block"
                                                onclick="jQuery('#user-form').attr('action', '{{ $router->route('social_login', array('provider' => 'google')) }}')">
                                            <span class="fa fab fa-fw fa-google"></span>
                                            Google
                                        </button>
                                    </p>
                                @endif

                                @if ($app->get('social_login.yahoo.enabled'))
                                    <p class="col-md-4">
                                        <button class="social-login-yahoo-button btn btn-success btn-block"
                                                style="background-color: #514099; border-color: #514099"
                                                onclick="jQuery('#user-form').attr('action', '{{ $router->route('social_login', array('provider' => 'yahoo')) }}')">
                                            <span class="fa fab fa-fw fa-yahoo"></span>
                                            Yahoo
                                        </button>
                                    </p>
                                @endif

                                @if ($app->get('social_login.github.enabled'))
                                    <p class="col-md-4">
                                        <button class="social-login-github-button btn btn-success btn-block"
                                                style="background-color: #111; border-color: #111"
                                                onclick="jQuery('#user-form').attr('action', '{{ $router->route('social_login', array('provider' => 'github')) }}')">
                                            <span class="fa fab fa-fw fa-github"></span>
                                            GitHub
                                        </button>
                                    </p>
                                @endif
                            </div>

                        @show

                        <div class="hidden-inputs">
                            @formToken
                        </div>
                    </form>
                </div>
            </div>
        @show

    </div>
@stop
