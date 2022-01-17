Демо-проект на symfony

* Регистрация пользователя
* Авторизация
* Восстановление и смена пароля
<br/><br/>

Run:
1. docker-compose build
2. docker-compose up -d
3. docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --no-interaction