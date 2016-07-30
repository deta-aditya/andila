# Aplikasi Integrasi Distribusi LPG Pertamina (Andila).

Andila is a web application project written in PHP and developed using Laravel 5.2. It also uses MySQL as its database driver (ver. >= 5.6.24). It is mainly developed for participating in [Lomba Karya Kreatif Inovatif Universitas Pertamina](http://pertamina.com/lkkiup2016) and for my own personal portfolio.

## Requirements

Andila has the same requirements with [Laravel 5.2 server requirements](https://laravel.com/docs/5.2) and requires MySQL RDBMS to run.

## Quick Installation

Follow these steps to install Andila on your own system:

1. Clone or download the repository.
2. Navigate to the cloned Andila repository and run ```composer install```.
3. Configure the environment variables of the repository. Here's how it's done:
  1. Open the example file in ```.env.example.local```. If you want to experience Andila in production mode you may use  ```.env.example.production``` instead, but it is not recommended.
  2. Change the ```DB_HOST```, ```DB_USERNAME``` and ```DB_PASSWORD``` as needed. Otherwise leave it as it is.
  3. Save the file you have just edited then rename it to ```.env```.
  4. Run ```php artisan key:generate```
4. Create and migrate the databases. Follow these substeps:
  1. In your MySQL server create two empty databases named ```indonesia``` and ```db_andila```.
  2. Use ```indonesia``` database and import the data from ```database/indonesia.sql```.
  3. Run ```php artisan migrate --seed```.
5. Configure the client JavaScript REST API Key. Follow these instructions:
  1. In ```db_andila```, select the ```developers``` table and notice a record with ```super``` username. Copy the ```token_api``` of that record.
  2. Open ```public/js/andila.js```. You will find a variable named ```andila.devApiToken```. Assign it with the value you just copied then save the file.
6. Start Andila by running ```php artisan serve```. Access it on ```localhost:8000```.

Congratulation! You've just successfully installed a copy of Andila.

## Credentials

Andila has prepared an administrator user, 5 stations, 5 agents, and 5 subagents data after the migration process. You may access all available users (attached with agents and subagents data) using their e-mail listed in the User Management panel, with the same password: ```secret```.

Use this credential to access the Administrator Panel:
```
admin@andila.dist
secret
```

## License

[MIT](https://github.com/purplebubblegum/andila/blob/master/license.md)
