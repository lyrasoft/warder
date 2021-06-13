{{-- Part of Front project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Legacy\Web\Application                 Global Application
 * @var $package  \Lyrasoft\Warder\WarderPackage              Package object.
 * @var $view     \Windwalker\Legacy\Data\Data                       Some information of this view.
 * @var $uri      \Windwalker\Legacy\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime \DateTime                                   PHP DateTime object of current time.
 * @var $helper   \Windwalker\Legacy\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Legacy\Core\Router\PackageRouter       Router object.
 * @var $asset    \Windwalker\Legacy\Core\Asset\AssetManager         The Asset manager.
 *
 * View variables
 * --------------------------------------------------------------
 * @var $state    \Windwalker\Legacy\Structure\Structure
 * @var $form     \Windwalker\Legacy\Form\Form
 */

$form->setAttributes('labelWidth', 'col-md-12')
    ->setAttributes('fieldWidth', 'col-md-12');
?>

@extends($warder->noauthExtends)

@section('content')
    <div class="container warder-page forget-reset-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 mx-md-auto" style="margin-top: 50px">
            @section('login-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('forget_reset') }}" method="POST"
                      enctype="multipart/form-data">

                    @yield('forget-reset-desc')

                    {!! $form->renderFields() !!}

                    <p class="reset-button-group">
                        <button class="reset-button btn btn-primary btn-block">
                            @translate($warder->langPrefix . 'forget.reset.submit.button')
                        </button>
                    </p>

                    <div class="hidden-inputs">
                        @formToken
                    </div>
                </form>
            @show
            </div>
        </div>
    </div>
@stop
