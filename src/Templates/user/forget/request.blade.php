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

$form->setAttributes('labelWidth', 'col-md-12')
    ->setAttributes('fieldWidth', 'col-md-12');
?>

@extends($warder->noauthExtends)

@section('content')
    <div class="container warder-page forget-request-page">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 mx-md-auto" style="margin-top: 50px">
            @section('login-content')
                <form id="user-form" class="form-horizontal" action="{{ $router->route('forget_request') }}"
                      method="POST" enctype="multipart/form-data">

                        @yield('forget-request-desc')

                        {!! $form->renderFields() !!}

                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <p class="reset-button-group">
                                    <button class="request-button btn btn-primary">
                                        @translate($warder->langPrefix . 'forget.request.submit.button')
                                    </button>
                                </p>
                            </div>
                        </div>

                    <div class="hidden-inputs">
                        @formToken()
                    </div>
                </form>
            @show
            </div>
        </div>
    </div>
@stop
