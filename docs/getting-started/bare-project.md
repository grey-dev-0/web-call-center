# Bare Project

This guide will lead you to install the project as a stand-alone web application, most of the configuration settings will be kept intact and, default web views will be used directly.

## 1. Creating a Laravel project.

First you'll need a fresh installation of the Laravel framework by going through its [official documentation here](https://laravel.com/docs), or you can follow the following summarized steps.

### 1.a. Create project

In terminal at your web root please run the following command:

```shell
$ composer create-project laravel/laravel <your_project_name> --prefer-dist
$ cd <your_project_name>
```

Replacing `<your_project_name>` with project name of your preference.

### 1.b. Setting up the Database.

Then you'll have to create the database for the project so, you'll need to login to your database server in terminal.

```shell
$ mysql -u <username> -p
```

Replacing `<username>` with your database server's username then, enter the password when prompted and, finally in the open `mysql` terminal you may write:

```sql
create database <database_name>;
exit;
```

Replacing `<database_name>` with the name you'd like to give to your new database.

Then in the `.env` file found in the root directory of your project, please set the values of all variables starting with `DB_` accordingly.

## 2. Installing Web Call Center

This project is created as a package to be integrated with a Laravel application, that's why we had to create fresh project first in the previous step.

### 2.a. Requiring the Package

Now to install the project, in the terminal at your project's root directory, please write the following.

```shell
$ composer require grey-dev-0/web-call-center:@dev
```

### 2.b. Publishing Assets

Web call center supports customizations to its views and configurations thus, you should publish them to your project in case if you need to customize any of the views (UI) or configurations (settings).

```shell
$ php artisan vendor:publish --force --provider='GreyZero\WebCallCenter\Providers\AppServiceProvider'
```

### 2.c. Populating the Database

Well we've created the database for our project, but we haven't started using it yet, so in the same terminal please write the following:

```shell
$ php artisan queue:table
$ php artisan migrate
```

The last couple of commands have published Laravel's background queue jobs tables that will handle processing operations done in background and, it also creates the database tables required by the project to run in general.

## 3. Integrating Agora

{% include-markdown "../../common/agora.md" %}

## 4. Integrating Laravel Websockets

{% include-markdown "../../common/websocket.md" %}

## 5. Web Server Configuration

{% include-markdown "../../common/server.md" %}

## 6. We're Done. What's Next!

If you have followed all the steps above you're having a functional call center web application running but, there are no organizations, agents nor, customers in the system to try out so, you have two options:

1. Connect to the project's database and create them yourself.
2. Or run `php artisan db:seed --class TestSeeder`.

If you run the seeder, a couple of dummy organizations will be created for you in the database including some agents for each and, some customers, by accessing `https://<your_domain>/login` - replacing `<your_domain>` with your actual project's domain - then entering the username `agent00` or `client20` and password `test123` you'll be logged in to the corresponding dashboard.

You can check the rest of the accounts created in the `wcc_users` table in the project's database.
