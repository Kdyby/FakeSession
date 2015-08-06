# Quickstart

This extension wraps `Http\Session` and allows you to completely disable it without breaking your application, that might be dependent on the session.

This is great for dealing with crons, apis or cli scripts producing tons of session entries, which might either create a performance problem, or storage problem.


## Installation

You can install the extension using this command

```sh
$ composer require kdyby/fake-session
```

and enable the extension using your neon config.

```yml
extensions:
	fakeSession: Kdyby\FakeSession\DI\FakeSessionExtension
```


## Usage

You don't have to configure anything at all, it just works.
Once you register the extension, it takes place of the session and wraps it. Always.

If you wanna disable the session, just call

```php
$fakeSession->disableNative();
```

which you have to manage before the session starts, otherwise it has no point.
If you change your mind or you wanna simply enable it back, you can call

```php
$fakeSession->enableNative();
```

When the native session is disabled, the object returns only fake session and fake session section instances.
This means you don't have to worry about http headers and cookies that PHP produces and all the data stays only in memory,
nothing will be persistent.

The extension has only one option `enabled` and it defaults to `TRUE` if the application is running in cli.
If it's `TRUE`, then the method `disableNative` is called when service is initialized.
