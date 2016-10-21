# SourceBans - Material Design
![Образец](http://s09.radikal.ru/i182/1610/5f/e56ed82e77f8t.jpg)
#### Основан на [SourceBans++ 1.5.4.7](https://sbpp.github.io/)
#### Последняя актуальная версия: **1.1.2** *(от 17.10.2016)*
Официальная тема рефорка на *[HLmod.ru](http://hlmod.ru/threads/alpha-material-admin-refork-na-osnove-sb-1-5-4-7-bootstrap-3.36382/)* и на *[MyArena.ru](forum.myarena.ru/index.php?/topic/35781-alpha-material-admin-refork-sb-1547/)*

Авторы: [AS.^TRO](http://hlmod.ru/members/79776/), [SAZONISCHE](http://hlmod.ru/members/57554/), [XaH JoB](http://hlmod.ru/members/81268/), [Vampir](http://hlmod.ru/members/17369/), [gibs](http://hlmod.ru/members/46233/), [CrazyHackGUT](http://hlmod.ru/members/72654/)

[Скриншоты](http://imgur.com/a/5PMoj)

*Примеры:*
* [SourceBans :: MATERIAL](http://mmcs.pro/sourcebans/)
* [[ G-44.RU ] :: Bans](http://bans.g-44.ru/)
* [gmode.ru - SourceBans](https://gmode.ru/sourcebans/)
* [Игровой проект :: L4D-Zone.RU - Комплекс игровых серверов](https://l4d-zone.ru/)
* [SourceBans Final Night](http://final-night.ru/bans/)

*Установка:*
+ Скачать архив последней версии [отсюда](https://drive.google.com/open?id=0Bx2BMgLluKoQUjhlcWk2VWpxWTQ).
+ Загрузить все файлы из папки web_upload на веб-сервер по любому удобному протоколу (например, **FTP**).
+ Переименовать файл на веб-сервере *config.php.temple* в *config.php*
+ Установить права **777** на папки */demos/*, */images/maps/*, */images/games/*, */themes_c/* и на файл */config.php*
+ Перейти на установщик SourceBans на веб-сервере (*http://ваш_домен/install/* или *http://ваш_домен/путь_до_sb/install/*)
+ Установить систему, следуя инструкциям на экране
+ После установки, удалить папку **/install/** с веб-сервера и **незамедлительно** перейти на обновлятор SourceBans (*http://ваш_домен/updater/* или *http://ваш_домен/путь_до_sb/updater/*).
+ После успешного обновления, удалить папку **/updater/** с веб-сервера.

*Обновление:*
- Скачать архив последней версии [отсюда](https://drive.google.com/open?id=0Bx2BMgLluKoQUjhlcWk2VWpxWTQ).
- Удалить все файлы с веб-сервера, кроме *config.php* и папок *images*, *demos*
- Закинуть **ВСЕ ПАПКИ и ФАЙЛЫ** в корень СБ с заменой, кроме папки *install*.
- Открыть SourceBans, вас перекинет на страницу с обновлением.
- Прочитать, что ваша СБ обновляется. Подождать, если потребуется.
- После обновления, следовать тексту на странице обновлятора, внизу.
- Если потребуется, выдать права **777** на файлы и папки (те же самые, которые обычно нужно изменить при установке) в корне сб.
- Если обновляетесь с версии меньше 1.1.1, то запишите в файл config.php перед ?> вот это:
`define('AVATAR_LIFETIME', 86400); // Avatar lifetime in cache (seconds). (Default: 86400)`
