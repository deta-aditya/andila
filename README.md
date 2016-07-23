# Aplikasi Integrasi Distribusi LPG Pertamina (Andila).

Andila is a web application project written in PHP and developed using Laravel 5.2. It also uses MySQL as its database driver (ver. >= 5.6.24). It is mainly developed for participating in [Lomba Karya Kreatif Inovatif Universitas Pertamina](http://pertamina.com/lkkiup2016) and also as my personal portfolio.

## Requirements

Andila has the same requirements with [Laravel 5.2 server requirements](https://laravel.com/docs/5.2).

## Installation

Andila uses two MySQL databases: ```indonesia``` and ```db_andila```.

### indonesia

The ```indonesia``` database is used for storing administrative information of Indonesia. It is required for geocoding over the map and storing address data.

To install the database, simply import it to the MySQL server. The .sql can be found in the ```/work/database``` directory.

[Source](https://github.com/edwardsamuel/Wilayah-Administratif-Indonesia).

### db_andila

This is the core database of Andila. Unlike the previous database, you need to migrate and seed in order to install it.
If you don't know how to migrate and seed database in Laravel, follow these instructions:

1. Create an empty database in your MySQL server. Name it ```db_andila```.
2. Open your terminal and enter the ```work``` directory in the cloned Andila repository.
3. Run the command below:
```
php artisan migrate --seed
```
When it has done, you may check whether the migration process run properly by checking availability of these tables in ```db_andila```:
```
activities
addresses
agents
attachments
developers
messages
migrations
notifications
orders
reports
report_retailer
retailers
schedules
stations
subagents
subschedules
users
```

## Start

You can start using Andila by running ```php artisan serve``` command in the ```work``` directory, then access it on ```localhost:8000``` via your browser.

Andila has prepared an administrator user, 5 stations, 5 agents, and 5 subagents data after the migration process. You may access all available users (attached with agents and subagents data) using their e-mail listed in the User Management panel, with the same password: ```secret```.

Use this credential to access the Administrator Panel:
```
admin@andila.dist
secret
```
