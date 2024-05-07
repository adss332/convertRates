<h1>Инструкция по запуску приложения</h1>

<h2>Предварительные требования</h2>
<ul>
    <li>Docker и Docker Compose должны быть установлены на вашем компьютере.</li>
    <li>Убедитесь, что все файлы конфигурации и Dockerfile настроены и находятся в соответствующих директориях вашего проекта.</li>
</ul>

<h2>Запуск приложения</h2>
<p>Для запуска приложения выполните следующие шаги:</p>
<ol>
    <li><strong>Запуск Docker Compose:</strong>
        <pre>docker-compose up -d</pre>
        Эта команда запустит все необходимые сервисы, включая PostgreSQL и ваше приложение.
    </li>
    <li><strong>Убедитесь, что контейнеры запущены:</strong>
        <pre>docker-compose ps</pre>
    </li>
    <li><strong>Установите пакеты</strong>
        <pre>docker exec -it app composer install</pre>
    </li>
</ol>

<h2>Использование приложения</h2>
<p>Ваше приложение поддерживает следующие команды Symfony:</p>

<h3>Импорт курсов валют</h3>
<pre>docker exec -it app php bin/console rates:import</pre>

<h3>Конвертация валют</h3>
<pre>docker exec -it app php bin/console currency:convert [сумма] [из валюты] [в валюту]</pre>
<p>Например, для конвертации 100 USD в EUR:</p>
<pre>docker exec -it app php bin/console currency:convert 100 USD EUR</pre>

<h2>Остановка и очистка ресурсов</h2>
<p>Чтобы остановить запущенные сервисы и очистить ресурсы Docker, используйте следующую команду:</p>
<pre>docker-compose down</pre>
<p>Если необходимо также удалить тома данных:</p>
<pre>docker-compose down -v</pre>
