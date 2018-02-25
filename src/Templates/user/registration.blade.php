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

\Phoenix\Script\PhoenixScript::formValidation('#user-form');

$form->setAttributes('labelWidth', 'col-md-12')
    ->setAttributes('fieldWidth', 'col-md-12');

$basicFieldset = array_shift($fieldsets);
?>

@extends($warder->noauthExtends)

@section('content')
    <div class="container warder-page registration-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 mx-md-auto" style="margin-top: 50px">
            @section('registration-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('registration') }}" method="POST"
                      enctype="multipart/form-data">

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

                    <div class="hidden-inputs">
                        @formToken()
                    </div>
                </form>
            @show
            </div>
        </div>
    </div>
@stop
