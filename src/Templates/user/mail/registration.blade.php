{{-- Part of phoenix project. --}}


<p>
    Dear {{ $user->name }}
</p>

<p>
    You register a new user account at {{ \Phoenix\Html\HtmlHeader::getSiteName() }}.
</p>

<p>
    Please verify your email and go to this URL: <a href="{{ $link }}">{{ $link }}</a>
</p>

<p>
    Thank you.
</p>
