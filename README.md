## Cервис автокомплита для поиска аэропортов по части названия

Источник данных https://github.com/NemoTravel/nemo.travel.geodata/blob/master/airports.json

- composer install

copy .env.example to .env

Set .env DB_ settings, then:

- php artisan migrate

Udpate Airport list

- php artisan app:update-airport

Run server
- php artisan key:generate
- php artisan serve

## Web form

<img src="https://raw.githubusercontent.com/relesssar/art/main/images/airport_from.png" width="400" alt="Web">

## Simple api

```
curl --location --request POST 'http://nemo.local/get-airport?q=al' 
```

## Заметки
Использован последний laravel 11 из коробки поэтому php 8.2.

тест производительности с дефолтным кешем, 

|количество запросов| default database cache, первый проход ,sec | default database cache, второй проход ,sec |
|-|--------------------------------------------|--------------------------------------------|
|1000 | 202                                        | 190                                        |

Конечно, целесобразно использовать redis пододные БД.

Можно уменить размера кеша, например 5 строк наименований, как это делается на сайтах поиска билетов, выводиться не весь список вариантов.
Для это нужно поставить лимит (limit) на выборку  

```
$result = Airport::select('iata','name_ru','name_en','country')
                        ->where('name_ru','LIKE',$query.'%')
                        ->orderBy('name_ru', 'ASC')
                        ->limit(5)
                        ->get()
                        ->toArray();
```
