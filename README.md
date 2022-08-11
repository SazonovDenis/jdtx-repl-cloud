# Настройка http почтового сервера, реализация php


Чтобы задать расположение рабочего каталога поменяйте содержимое:

~~~
php\api.04\content_root.php
~~~


Чтобы проверить доступность и работоспособность почтового сервера выполните команду:

~~~
jc repl-mail-check -url:http://you-sute-name.com/you-site-context/
~~~



Чтобы задать пароль на веб доступ укажите MD5 хэш пароля в файле:

~~~
site/web_pass/pass_md5.txt
~~~


Чтобы мониторить состояние репликации откройте адрес:

~~~
http://you-site-name.com/you-site-context/site/web_status_all.html
~~~
