<?php

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app           \Windwalker\Web\Application                 Global Application
 * @var $package       \Windwalker\Core\Package\AbstractPackage    Package object.
 * @var $view          \Windwalker\Data\Data                       Some information of this view.
 * @var $uri           \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime      \DateTime                                   PHP DateTime object of current time.
 * @var $helper        \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router        \Windwalker\Core\Router\PackageRouter       Router object.
 * @var $asset         \Windwalker\Core\Asset\AssetManager         The Asset manager.
 *
 * @var $message       \Windwalker\Core\Mailer\MailMessage
 * @var $user          \Lyrasoft\Warder\Data\UserData
 * @var $link          string
 */

?>

@extends('mail.mail-layout')

@section('content')
    <p>
        Hi {{ $user->name }}
    </p>

    <p>
        You register a new user account at {{ \Phoenix\Html\HtmlHeader::getSiteName() }}.
        Please verify your email.
    </p>

    <p>
        Go to this URL: <a href="{{ $link }}">{{ $link }}</a>
    </p>

    <p>
        Or click this button:
    </p>

    <p>
        <a class="btn btn-primary" href="{{ $link }}">
            Verify my email
        </a>
    </p>

    <p>
        Thank you.
    </p>
@stop
