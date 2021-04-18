## Лабораторная работа по дисциплине "Технологии WEB-приложений"

### Вариант 1. Разработка сайта для ведения текстового блога

### Задание:

На всех страницах в шапке сайта для неавторизованных пользователей выводить ссылки на авторизацию и регистрацию, для
авторизованных — приветствие, кнопку “Выход” и имя пользователя.

На главной странице выводятся посты, отсортированные по дате добавления. У каждого поста выводится название (ссылка на
страницу поста), дата добавления, аннотация (краткое описание содержимого или первая часть поста) и количество
комментариев к посту.

На странице поста выводится информация о посте:

- название
- дата добавления
- текст описания
- количество просмотров поста
- список комментариев, отсортированных по дате добавления
- форма “Оставить комментарий” (текстовое поле и кнопка “Отправить”)

На странице добавления поста располагается форма с полями:

- название
- аннотация
- текст поста

Просмотром новости должно считаться посещение детальной страницы поста, если текущий пользователь - не автор поста.

Все пользователи (в том числе неавторизованные) могут просматривать посты и комментарии к ним.<br>
Авторизованные пользователи могут оставлять комментарии под постами.<br>
Пользователь с правами администратора должен иметь возможность добавления, удаления и редактирования постов, а также
удаления комментариев.

### Реализация проекта

Данный проект написан на языке PHP `7.4.16` с использованием фреймворка Symfony, а именно с использованием Symfony
CLI `v4.23.5`.

Развернуть данный проект можно двумя способами:

1) Без использования Docker
2) С использованием Docker

#### Способ без использования Docker

1) Скачать данный репозиторий к себе на компьютер. Это можно сделать с помощью следующей команды:

```bash
git ...
```

2) Теперь необходимо установить все зависимости проекта, для этого необходимо воспользоваться следующей командой:

```bash
composer install
```

3) Отредактировать переменные окружения (файл .env), а именно — указать строку для подключения к БД

4) В случае отсутствия базы данных, указанной в предыдущем пункте, создать её с помощью команды:

```bash
symfony console doctrine:database:create
```

5) Применить миграции указанные в папке `migrations`, для этого выполните команду:

```bash
symfony console doctrine:migrations:migrate
```   

6) Добавить в базу данных подготовленные записи, для этого выполните команду:

```bash
symfony console doctrine:fixtures:load
```

7) Запустите сервер, для этого воспользуйтесь командой:

```bash
symfony serve
```

#### Способ с использованием Docker

1) Скачать данный репозиторий к себе на компьютер. Это можно сделать с помощью следующей команды:

```bash
git ...
```

2) Отредактировать переменные окружения (файл .env), а именно — указать строку для подключения к БД. В данном случае
   необходимо использовать имя контейнера указанное в файле docker-compose.yaml


3) Перейти в папку `docker` и выполнить следующую команду:

```bash
docker-compose up -d
```

4) Зайти в контейнер с Symfony проектом и выполнить шаги `5-6` в инструкции по разворачиванию проекта без использования
   Docker

### Данные пользователей после добавления фикстур в БД

#### Администратор:

Логин: admin@admin.ru

Пароль: hard_admin_passw0rd!

#### Пользователь:

Логин: example@mail.ru

Пароль: Qwerty123!