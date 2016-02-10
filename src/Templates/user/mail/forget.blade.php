{{-- Part of eng4tw project. --}}

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
