# MdlCMS v1.6.2

## Компоненты
* Автогенерируемая админка.
* Каталог с произвольной привязкой предметов (бесконечная вложенность).
* Новости, инфо-страницы.
* Управление меню и метатегами.
* Обратная связь.
* Корзина и заказы.
* Файловый менеджер.
* Слайдер.
* Управление банами и защита от перебора.

## Особенности
* MVC-архитектура с развитыми моделями.
* Автогенерация базы по моделям, миграции
* Поиск по всем моделям и полям.
* Множество полезных функций.
* Разделяемые окружения (```local, prod```).
* Markdown-разметка via [GitHub API](https://developer.github.com/v3/markdown/).
* Интернационализация интерфейса.
* Реферальные ссылки.
* Bootstrap 4 Beta.
* Восстановление паролей

## Как начать
1. Создать папку ```data/sessions``` с правами ```755```
2. Создать базу.
3. Прописать настройки базы в ```env-*.php```.
4. Прописать директории проекта в ```env-*.php```.
5. Изменить настройки в ```config.php```.
6. Создать строку пользователя в таблице ```users``` с ```roles = 'admin'```.
7. Войти в систему через страницу ```/users/login``` с логином и паролем админа.
8. В ```Модели > Меню``` создать пункты для ```namespace``` main и admin.
9. Изменять сущности в models. Админка строится по сущностям и их свойствам.
10. Писать логику в ```controllers```.
11. Маршруты в ```routes.php```.
12. Если возникает ошибка с записью сессии, попробуйте
```
sudo chown -R www-data:www-data /var/www/html/mdlcms/data/sessions/
```
12. Если не создаются записи, на пример, не проходит регистрация, выставьте ```DEFAULT VALUE``` полям соответствующей таблицы.
13. С вопросами обращаться [ко мне](https://seibelstan.github.io).