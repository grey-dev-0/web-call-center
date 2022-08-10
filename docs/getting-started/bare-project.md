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

{% include-markdown "../../agora-notice.md" %}
