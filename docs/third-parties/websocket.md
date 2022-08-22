# Laravel Websockets

## Overview

In order for this project to properly manage and assign incoming calls to online least occupied customer agents, it utilizes [Laravel Websockets](https://beyondco.de/docs/laravel-websockets/getting-started/introduction) library to enable the server realtime communication with customer agents, thus recognizing which agents are online within each organization besides, notifying them about their assigned calls once they come in.

## Laravel Websockets Integration

### 1. Setting Required `.env` variables.

Please ensure the existence and setting of the following environment variables in your project's `.env` file.

```dotenv
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=<app_id>
PUSHER_APP_KEY=<app_key>
PUSHER_APP_SECRET=<app_secret>
PUSHER_APP_CLUSTER=<app_cluster>
```
Replacing all values with any string of your preference however, special characters should be avoided to prevent any potential errors or malfunctions.

### 2. Setting up Broadcasting configuration

In your project's `config/broadcasting.php` file, please replace the `pusher` entry with following block:

```php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'encrypted' => true,
        'host' => '127.0.0.1',
        'port' => env('LARAVEL_WEBSOCKETS_PORT', 6001),
        'scheme' => 'http'
    ],
]
```

This configuration is necessary to enable handled HTTP requests query the websocket server about current customer agents status when required.

### 3. Starting up Required Background Services

For Laravel Websockets and Notifications System to function a couple of background services must be launched on the server hosting this project or, on your pc if your trying this project locally.

First make sure that the `QUEUE_CONNECTION` key in your project's `.env` file is set to `database` or any value other than `sync` if you're not using the database for handling Laravel's background jobs, then in terminal at your project's directory please write the following:

```shell
$ nohup php artisan queue:work > storage/logs/queues.log &
$ nohup php artisan websockets:serve > storage/logs/websocket.log &
```

These couple of lines will run the required services mentioned and log their output to the files specified after the `>` operator so, in case of problems you can always check those files for debugging.

`nohup` command is used here to ensure that the services keep running even after you close the terminal so, these services will keep running until you either restart the server - or pc - or, an error occurs in one of them.

## Continue the Setup.

That's it, you've successfully setup Laravel Websockets for the project, you'll need to follow the remaining steps to finalize the setup of the project so you can now:

- [Continue Bare Project setup](../../getting-started/bare-project/#5-web-server-configuration).
- [Continue Integrated Project setup](../../getting-started/integrated-project/#5-web-server-configuration).
