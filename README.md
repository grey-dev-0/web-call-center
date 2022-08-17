# Web Call Center

This project aims to provide a web call center service where organizations and institutes that provide customer service and support can use, it's a WebRTC based project using [Agora IO service](https://agora.io) to enable customers around the world contact your organization's customer support agents through web, it also supports receiving calls from Android and iOS applications that have [Agora Mobile SDK](https://docs.agora.io/en/All/downloads?platform=All%20Platforms) integrated.

This project can be used on its own within a fresh Laravel installation, or it can be integrated within existing Laravel applications.

## Requirements

This project requires the following:

- php `8.x` or later.
- Laravel `8.x` or later.
- Laravel compatible RDBMS e.g. MySQL `5.x`, MariaDB `10.x`, etc.. 

## Documentation

Detailed installation guide and documentation can be found [here](https://grey-dev-0.github.io/web-call-center).

**IMPORTANT:** This project is still in early stages of development thus, it only provides basic features as of now, the list of available features is listed in the next section.

## Features

- Agents dashboard for customer support personnel to receive incoming calls.
- Customers prototype dashboard that enables them to make calls to one of the enlisted organizations.
    - An organization can be any establishment that provides some kind of service or products to public e.g. hospitals, restaurants, agencies, or any other sort of business.
- Automatic management of incoming calls to a particular organization.
    - On heavily incoming calls, the project will distribute them simply on all available agents i.e. an incoming call will always be assigned to the least occupied agent who's currently online within the organization requested by the calling customer.
- The dashboards included are customizable within any Laravel application whether used alone as a sole project, or integrated to an existing project.
- Integration to existing Laravel project can use your own existing models - database tables - that represent the required entities for the project to function.
    - You'll need to have models that represent organizations, customer support agents and finally, customers.
    - Or you can use the included models and database tables which are already defined within the project.

## Attribution

The idea of this project wouldn't have come to life without the following amazing technologies and the great people behind their development:

- [Laravel](https://laravel.com)
- [Laravel Websockets](https://beyondco.de/docs/laravel-websockets/getting-started/introduction)
- [WebRTC by Google](https://webrtc.org)
- [Agora IO](https://agora.io)
- [Bootstrap](https://getbootstrap.com)
- [Vue](https://vuejs.org)

