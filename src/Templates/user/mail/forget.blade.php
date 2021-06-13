<?php

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app           \Windwalker\Legacy\Web\Application                 Global Application
 * @var $package       \Windwalker\Legacy\Core\Package\AbstractPackage    Package object.
 * @var $view          \Windwalker\Legacy\Data\Data                       Some information of this view.
 * @var $uri           \Windwalker\Legacy\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime      \DateTime                                   PHP DateTime object of current time.
 * @var $helper        \Windwalker\Legacy\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router        \Windwalker\Legacy\Core\Router\PackageRouter       Router object.
 * @var $asset         \Windwalker\Legacy\Core\Asset\AssetManager         The Asset manager.
 *
 * @var $message       \Windwalker\Legacy\Core\Mailer\MailMessage
 * @var $user          \Lyrasoft\Warder\Data\UserData
 * @var $link          string
 * @var $token         string
 */

?>

@extends('mail.mail-layout')

@section('content')
    <p>
        Hi {{ $user->name }}
    </p>

    <p>
        You send a password reset require at this site.
    </p>

    <p>
        Your token is: <code>{{ $token }}</code>
    </p>

    <p>
        Please go to: <a href="{{ $link }}">{{ $link }}</a> to reset your password.
    </p>
@stop
