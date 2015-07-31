<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <p>
        Добро пожаловать в {{ link_to('','Amway') }}.
        @if(!$verified_email)
            Активируйте свой аккаунт, перейдя
            по {{ link_to('activation?u='.$account->id.'&c='.$account->temporary_code,'ссылке') }}.
            Не откладывайте, ссылка действует 72 часа.
        @endif
        Для входа используйте:<br>
        Логин: {{ $account->email }}<br>
        Пароль: {{ $password }}
    </p>
</div>
</body>
</html>