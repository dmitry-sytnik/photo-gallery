# photo-gallery
Учебный проект photo-gallery. Курс по ООП.

Вход на сайт происходит по пути photo-gallery/public/ или photo-gallery/public/index.php
При установке на свой сервер понадобится исправить путь SITE_ROOT в файле includes/initialize.php

Вход для администратора: photo-gallery/public/admin/login.php (логин kskoglund, пароль secretpwd).
В целях упрощения пароль не хэшировался.

Проект всесторонне не тестировался на наличие ошибок. Применялся параметр
error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT
в php.ini на сервере с PHP 5.6.

Для реализации отправки e-mail при отправке комментария следует в файле \includes\comment.php исправить получателя письма в строке 45 
$to = "Admin Photo-Gallery <recipient@mail.ru>";
и отправителя письма в строке 66
$from = "Free Man <sender@mail.ru>";
Сам сервер при этом должен быть настроен на возможность отправки писем.
