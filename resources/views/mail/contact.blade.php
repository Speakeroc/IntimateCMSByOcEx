<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Обратная связь | {{ $form_site_name }}</title>
</head>
<body>
<div>
    <p>Поступило новое обращение из обратной связи на сайте: {{ $form_site_name }}</p>
    <p></p>
    <p>Тема обращения: {{ $form_theme }}</p>
    <p>Имя отправителя: {{ $form_name }}</p>
    <p>E-mail отправителя: {{ $form_email }}</p>
    <br>
    <p>Текст обращения:</p>
    <p>{{ $form_message }}</p>
</div>
</body>
</html>
