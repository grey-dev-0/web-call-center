# Integrated Project

## Overview

If you already have an existing Laravel project, you can easily integrate Web Call Center into it, you have the option to use all the database tables and models provided by the package or, customize it so that you can pick which of your own models represent any of the following entities:

- **Organization** which represents the establishment that has one or more customer service agents.
- **Agent** which represents the customer service agent.
- **Customer** which represents the customer.

So basically a **customer** would be calling an **organization** of their choice then, their call will automatically be assigned to the **organization**'s least occupied **agent** who's currently online, the incoming **calls** will be queued to the assigned **agents** accordingly, so they'd pick up and answer them as soon as possible in FIFO - first in first served - fashion.

The **Call** entity is the only fixed entity that it's not allowed to be customized or replaced at the moment however, it will automatically be associated with the other entities regardless whether you replace any of them with your own models, more on how to do that customization is explained below, anyway please follow the steps stated in this page to avoid any potential errors.

## 1. Installing Web Call Center

This project is created as a package to be integrated with a Laravel application so, let's set it up by following the next steps.

### 1.a. Requiring the Package

In the terminal at your project's root directory, please write the following.

```shell
$ composer require grey-dev-0/web-call-center:@dev
```

### 1.b. Publishing Assets

This step is necessary for publishing the configuration files that you'd update to customize the integration of Web Call Center to your Laravel project, besides it will publish Web Call Center's blade views, so that you can customize them to match your application's stying and UI and, finally required frontend assets are also published for UI to work.

```shell
$ php artisan vendor:publish --force --provider='GreyZero\WebCallCenter\Providers\AppServiceProvider'
```

### 1.c Configuring Web Call Center

After publishing the assets you'll find the configuration file of Web Call Center located in your default configuration directory i.e. `config/web-call-center.php`, despite the clarity of comments written before each configuration setting let's have a quick look into the most ambiguous ones.

- `middleware`: sets the default middleware to be used by the package and, **implicitly** sets the default authentication's users provider to the package's `User` model so, if you plan to authenticate your own users into Web Call Center please change it to `web` instead.
- `morph_authenticatable`: If set to `true` then the authentication entity must have a polymorphic relationship with **Agent** and **Customer** entities, otherwise it means that each **Agent** and **Customer** entities are authenticatable models on their own, **note** that this only to be changed if you set your custom `middleware` setting and, you'd authenticate agents and customers by their separate entities.
- `customer_model`, `agent_model` and, `organization_model` are the full model class names - including the namespace - that represent each entity, you may change any of them to the model class existing in your own Laravel application which represents that corresponding entity.
- `organization_foreign_key`: The foreign key that relates the **Agent** entity's table to the **Organization** entity's table, you'd need to change this accordingly if you customize either or both of **Organization** and **Agent** model classes mentioned in the previous point.
- `incremental_primary_key`: each table of the main three tables will have an incremental primary key by default, if you'd like to set the primary key manually for any created record of those tables, you can simply set the flag of that table to `false`, the purpose of setting it to `false` would be using the primary key as a foreign key that refers to another table or entity in your own Laravel application.

### 1.d. Database Setup

In this step we'll run the database migration command to create the tables required by Web Call Center, provided that you've set the configuration settings that suit your project in the previous step, **please note** that this step is necessary even if you've designated your own models to all of the customizable models, as the `calls` table is required regardless and, needs to be created for the call center to function.

If you don't have Laravel queues feature enabled in your project, please enable it by setting `QUEUE_CONNECTION` variable to `database` in the `.env` file then, in terminal at your project's root directory you'll need to write:

```shell
$ php artisan queue:table
```

!!! question "What if queues are already enabled but on a connection other than `database` e.g. `redis`?"

    Skip the queues related part in this case but, you'll still need to proceed to the `migrate` command mentioned afterwards.

Then finally you'll need to run the following terminal command to run all required database migrations.

```shell
$ php artisan migrate
```
## 2. Custom Eloquent Models

If you have set your own model class for any of the major three entities **Organization**, **Agent** or, **Customer** please check the sections that correspond to your customized entities in this step.

Also if you have set custom `middleware` value, please check the [User Model section](#2-d-user-model) as well.

### 2.a. Organization Model

If you have changed `organization_model` configuration setting in the `config/web-call-center.php` file to an existing model in your project that represents the **Organization** entity e.g. `\App\Models\SomeAgency::class` then, you'll need to use the `HasAgents` trait in that class so, the class would look like the following:

```php
namespace App\Models;

use GreyZero\WebCallCenter\Traits\HasAgents;
use Illuminate\Database\Eloquent\Model;

class SomeAgency extends Model{
    use HasAgents;
    
    // ...
}
```

### 2.b. Agent Model

If you have changed `agent_model` configuration setting in the `config/web-call-center.php` file to an existing model in your project that represents the **Agent** entity e.g. `\App\Models\SomeAgent::class` then, you'll need to use the `ReceivesCalls` trait in that class so, the class would look like the following:

```php
namespace App\Models;

use GreyZero\WebCallCenter\Traits\ReceivesCalls;
use Illuminate\Database\Eloquent\Model;

class SomeAgent extends Model{
    use ReceivesCalls;
    
    // ...
}
```

### 2.c. Customer Model

If you have changed `customer_model` configuration setting in the `config/web-call-center.php` file to an existing model in your project that represents the **Customer** entity e.g. `\App\Models\SomeCustomer::class` then, you'll need to use the `MakesCalls` trait in that class so, the class would look like the following:

```php
namespace App\Models;

use GreyZero\WebCallCenter\Traits\MakesCalls;
use Illuminate\Database\Eloquent\Model;

class SomeCustomer extends Model{
    use MakesCalls;
    
    // ...
}
```

### 2.d. User Model

If you have changed the `middleware` configuration setting in the `config/web-call-center.php` file to `web` or any other middleware than the preset default, you'll need to use the `UsesCallCenter` trait in your authenticatable model(s), in case if you have one authentication model i.e. `morph_authenticatable` is set to `true` then, you'll use that trait in that model class, otherwise you'll need to use that trait with each authenticatable model; the one that represents the **Agent** and the one that represents the **Customer**.

=== "Single Authentication Model"

    `morph_authenticatable` is `true` in this case i.e. you're authenticating your users using the default `User` model provided by Laravel which is modified by you or, you might be using another model of your own that relates to **Agent** and **Customer** via a polymorphic relationship.

    ```php
    namespace App\Models;
    
    use GreyZero\WebCallCenter\Traits\UsesCallCenter;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens;
    
    class User extends Authenticatable{
        use HasApiTokens, HasFactory, Notifiable, UsesCallCenter;
        
        // ...
    }
    ```

=== "Multiple Authentication Models"

    `morph_authenticatable` is `false` in this case i.e. you're authenticating your users using several models, so we need to define which of them are going to use Web Call Center besides, which of them will represent the **Agent** and which will represent the **Customer** so, the model that represents the **Agent** in your project should  look like the following:

    ```php
    namespace App\Models;
    
    use GreyZero\WebCallCenter\Traits\ReceivesCalls;
    use GreyZero\WebCallCenter\Traits\UsesCallCenter;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Laravel\Sanctum\HasApiTokens;
    
    class SomeAgent extends Authenticatable{
        use HasApiTokens, HasFactory, ReceivesCalls, UsesCallCenter;
        
        // ...
    }
    ```

    And the model that represents the **Customer** in your project should look like this:
    
    ```php
    namespace App\Models;

    use GreyZero\WebCallCenter\Traits\MakesCalls;
    use GreyZero\WebCallCenter\Traits\UsesCallCenter;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Laravel\Sanctum\HasApiTokens;
    
    class SomeCustomer extends Authenticatable{
        use HasApiTokens, HasFactory, MakesCalls, Notifiable, UsesCallCenter;
        
        // ...
    }
    ```
## 3. Integrating Agora

{% include-markdown "../../common/agora.md" %}

## 4. Integrating Laravel Websockets

{% include-markdown "../../common/websocket.md" %}

## 5. Web Server Configuration

As seen in the previous step this project relies on Websockets to manage online customer service agents so, you'll need to ensure that websocket related settings are properly set in your web server configuration file.

{% include-markdown "../../common/server.md" %}

## 6. We're Done. What's Next!

Based on your `prefix` setting in the `config/web-call-center.php` config file, if it's not set `null` then you can access the **customer** and the **agent** dashboards on `https://<your_domain>/<prefix>/customer` and, `https://<your_domain>/<prefix>/agent` respectively, replacing `<your_domain`> with the domain name that's configured to access your project and, `<prefix>` with the value of the `prefix` setting in the mentioned configuration file. If it's set to `null` simply omit the `<prefix>/` part from the said URLs.

!!! note "Notice"

    Please note that authentication middleware will be applied so, you'll be prompted to login using a **customer** or an **agent** account before you can proceed to the requested dashboard.
