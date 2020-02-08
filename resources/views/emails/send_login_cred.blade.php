Dear {{ $user->name }},<br><br>

Your login credentials for {{LAConfigs::getByKey('sitename')}} are as below:<br><br>

Username: {{ $user->email }}<br>

To set password please follow this <a href="{{ request()->getSchemeAndHttpHost() }}/verify/{{ $user->email }}/token/{{ $user->verifytoken }}">link</a> or copy paste below link.

<a href="{{ request()->getSchemeAndHttpHost() }}/verify/{{ $user->email }}/token/{{ $user->verifytoken }}">
    {{ request()->getSchemeAndHttpHost() }}/verify/{{ $user->email }}/token/{{ $user->verifytoken }}
</a>

Best Regards,