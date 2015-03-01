<h1>=========== SecureAccount v1.1 ===========</h1><br>
<h2>Описание</h2>:<br>
Данный плагин помогает защитить аккаунты серверапо IP. Если<br>
какой-либо добавлен в список, то за этот аккаунт можно зайти<br>
только с того IP, который установлен на аккаунт в списке.<br>
<br>
<br>
<br>
<h2>Установка</h2>:<br>
1. Закинуть .phar файл (это и есть сам плагин) в папку<br>
/plugins, находящаяся в корне вашего сервера<br>
2. Выключить сервер<br>
3. Включить сервер<br>
<br>
<br>
<br>
<h2>Команды</h2>:<br>
<br>
/secure <никнейм> <ip_адрес> - добавить аккаунт в список<br>
аккаунтов.<br>
<b>--Пример</b>:<br>
<code>/secure TheBestAdmin 192.168.1.1</code><br>
<br>
Если у владельца аккаунта IP динамический (постоянно меняется),<br>
то можно установить не IP, а подсеть.<br>
<b>--Пример</b>:<br>
<code>/secure TheBestAdmin 192.168.X.X</code><br>
<br>
То есть, где * - любое число<br>
<br>
<br>
/unsecure <никнейм> - удалить аккаунт из списка.<br>
<b>--Пример</b>:<br>
<code>/unsecure TheBestAdmin</code><br>