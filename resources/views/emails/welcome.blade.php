<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="utf-8" />
</head>
<body>
<table style="max-width:600px;min-width:400px;font-family:Arial,Helvetica,sans-serif;font-size:16px;padding:50px;"
       align="center">
    <tbody>
    <tr>
        <td colspan="3" style="text-align:center">
            <h1>Добро пожаловать на платформу «{{ $title }}»! Вы успешно зарегистрировались!</h1>
            <p>Ваши данные для входа на платформу:</p>
            <p>Логин: {{ $email }}</p>
            <p>Пароль: {{ $password }}</p>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center">
            <hr>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center">
            <p>Войти на платформу: <a href="{{ $login_url }}">{{ $login_url }}</a></p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
