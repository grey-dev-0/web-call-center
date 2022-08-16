# Agora

## Overview

[Agora](https://agora.io) is a WebRTC service that provides Video / Voice / Text chat implementation that can be integrated and added to any application, it's used by this project to enable customers to contact customer support / service agents on web voice calls, which can be initiated from a web browser or a mobile application that uses Agora SDK.

## Enabling Agora Integration

To start using this project it's required that you use your Agora account's integration credentials so, to achieve this please follow the upcoming steps:

1. Signup on Agora [here](https://sso.agora.io/en/signup), it's free and, no credit / debit card entry required.
2. Create your first Agora project on their dashboard by following their on-boarding wizard.
3. In Agora's main dashboard page, click the `"Config"` button found within your project's section.
4. Click the copy icon in the `"App ID"` field then paste it to the end of your Laravel project's `.env` file with the key `AGORA_APP_ID` so, the line added is `AGORA_APP_ID=<your_app_id>` replacing `<your_app_id>` with the code you've copied.
5. Click the copy icon in the `"App Certificate"` field then paste it to the end of the `.env` file like the previous one but with the key `AGORA_CERTIFICATE`.

## Continue the Setup.

That's it, you've successfully integrated Agora service to the project, you'll need to follow the remaining steps to finalize the setup of the project so you can now:

- [Continue Bare Project setup](../../getting-started/bare-project/#4-integrating-laravel-websockets).
- [Continue Integrated Project setup](../../getting-started/integrated-project/#4-integrating-laravel-websockets).
