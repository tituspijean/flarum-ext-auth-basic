# Basic authentication for Flarum

This extension enables users to seamlessly log into [Flarum](https://github.com/flarum/core) with HTTP basic authentication.

Note that PHP variables $_SERVER['HTTP_EMAIL'], $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] need to be set for the extension to work.
The extension itself will *not* actively try to have them populated. You need to have a SSO portal interfacing on your server, like [SSOwat](https://github.com/YunoHost/SSOwat).

Users will be automatically registered and activated, if missing from Flarum.

## How to install

`composer require tituspijean/flarum-ext-auth-basic` and activate it in Flarum's administration panel.

## Configuration

None needed.
