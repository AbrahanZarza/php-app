This project is intended as a basic framework that serves as the basis for simple and modular web applications.

For a good development experience, this project provides the developer with tools that allow him to focus on building his project, without going into some tedious implementation details.

From the beginning developer to the most experienced, this project can grow with you. Experiment and exploit the full potential of a new application starting from a good base, and even dare to expand this base with new implementations that bring more functionality to the project.

# Getting Started

To start, you must have a version of `php` 7 or higher installed. Next, you can download or clone this project from this url.
```
https://github.com/AbrahanZarza/php-app.git
```

## Installation

Once the project is downloaded, we will install the dependencies via Composer.
```
composer install
```

The next thing will be to configure our environment file. To do this, the first thing is to create a copy of the example environment file, renaming this copy as `.env`.
```
cp .env.example .env
```
> This file will specify the variables of each environment in which the application is hosted, in this case its local environment.

Finally, we start a test server with which we can access our web application and start developing.
```
php -S localhost:8080 public/index.php
```

## Directory Structure
The default application structure is intended to provide a starting point for a small and fast web applications. There are some restrictions but you are free to organize your web application however you need.

### Root directory

#### 📂 `app` directory
This directory contains the core code of your application. Almost all of the classes in your application will be in this directory.

#### 📂 `bootstrap` directory
This directory contains the helper.php file witch gives useful methods for the application. If needed, you can add more helper files here but remember to add that into `autoload.files` section of `composer.json` file.

#### 📂 `config` directory
This directory should contains all of your application's configuration files. By the moment, the main application's routes file is here.

#### 📂 `public` directory
This directory contains the entry point file of the web application, which configures autoloading files. This directory should also store assets files such as images, Javascript and CSS.

### The `app` directory
This directory contains the majority code of your application. By default, this directory is namespaced under App, using the PSR-4 standard in `composer.json` file.

By default, this directory contains additional directories such as Controllers and Classes, that provide application functionality.

#### 📂 `Classes` directory
This directory contains files that give basic logic to your web application. Normally, you don't need to edit or create new files inside this directory.

#### 📂 `Controllers` directory
This directory contains the main actions that would be executed when any route in your application will be requested.

#### 📂 `Exceptions` directory
This directory contains types of errors that your application, at any point, can throw. It was meant to identify and debug any application errors.

#### 📂 `Middlewares` directory
This directory contains mechanisms that check actions to execute, or not, other subsequent actions in your application, for example authentication actions.


# Basics

## Routing
The base project provides tools to create entry points to the application in a simple and clean way.

At the moment, the methods it accepts are: `GET`,` POST`, `PUT` and` DELETE`. These are the most used and basic for the creation of any type of web application.

### Basic routing
A basic example can be the one shown below.
```
Router::route(Request::METHOD_GET, '/', function (Request $request) {
    response('Hello app!');
});
```

As you can see, the project listens for a `GET` request to the` / `path, if this request arrives, it will return the text string shown on the screen as a response.

### Route to controller
Another even cleaner way to implement routes in your application is to directly call a controller action.

So far we have seen how to execute a function when a certain route is requested, but the project also offers the option of directly calling a method of a controller.

To do this, we need to create a controller in the `app / controllers` directory. Then we will specify the path as shown below.
```
Router::route(Request::METHOD_GET, '/', 'IndexController@someMethod');
```
> In this call we are requesting the `/` path for `GET` to invoke the` someMethod` method of the `IndexController` controller.

## Middlewares
Middleware is a mechanism that the project uses to execute code snippets as a check prior to an action.

If the result of this check is successful, the main action that precedes it will be executed.

A very clear example of middleware is user authentication. For the example we can see the middleware in the path `app / Middlewares` called` Auth.php`.

As you can see, all the middleware that we create will have the `__invoke` method. Within this is where the middleware functionality will run.

In this example we are specifying that the `X-Api-Key` header should reach us by the request.

Finally, to apply the middleware to our application routes we will specify it as the fourth parameter, indicating the name of the class of our middleware as follows.

```
Router::route(Request::METHOD_GET, '/', function (Request $request) {
	response('Hello app!');
}, Auth::class);
```

## Controllers
They are the files that execute the actions of the web application and are defined in the path `app / Controllers`.

These are normally called by routes, so that they receive an input, process the information and return an output with the processed information.

We can create as many controllers as we need, feel free to implement as many as you need.

## Requests
All the requests made to our web application have an object of the class `Request` associated with them.

This object provides us with useful information about the request, information such as the method, the parameters and all kinds of additional information.

For example, to be able to read a parameter, this would be an implementation of that action.

```
public function doSomething(Request $request) {
    echo $request->get('param_name');
}
```
> The `$request` object will obtain the requested parameter both by inspecting the query string and the request body.

## Responses
In response to a request in the application, we have a function in the `bootstrap / helper.php` file called` response`.

This function is in charge of treating the data it receives to give a coded response to the client.

Allowed parameters:

- `$data` - required - It will be the answer that will reach the customer.
- `$status` - optional - It is the HTTP standard code for the response.
- `$headers` - optional - They are the headers that will have that answer associated with it.

### `response` method Examples

#### Basic example with defaults
```
response(['foo' => 'bar']);
```

#### Example with status code
```
response('User not authorized!', \App\Classes\Response::HTTP_UNAUTHORIZED);
```

#### Full example
```
response(['foo' => 'bar'], \App\Classes\Response::HTTP_SUCCESS, ['Content-Type' => 'application/json']);
```

## Debugging
As a debugging utility we have, in the `bootstrap / helper.php` file, we have the` debug` and `dddebug` methods.

### `debug` method
This method is used to print, in detail, the content of a variable. This will be extremely useful when developing our web application.

### `dddebug` method
This method works the same as `debug`, but unlike the previous one, by invoking this method we will be stopping the execution of the code when printing the content of the variable to be debugged.


# Deploy in production
To deploy our code in production, a couple of criteria must be considered:

- Have a version of `php` 7 or higher installed.
- Have a web server installed, among the most common: `nginx` or` apache`.

Regardless of the choice, we must indicate to our web server that the entry point of our application will be from the `public` directory, with the` index.php` file.

## `nginx` deploy example file
Here is an example of what a basic deployment file would look like for `nginx` web servers.
```
server {
    listen 80;

    index index.php index.html;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php-fpm:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```
> Being `/ var / www / html` the default path that nginx will have configured in sites-enabled.

## `apache` deploy example file
Here is an example of a basic deployment file with `apache` and` virtual hosts`.
```
<VirtualHost *:80>
    DocumentRoot "/var/www/html/public"
    ServerName mydomain.com

    # Other directives here ...
</VirtualHost>
```
> Being `/ var / www / html` the default path that nginx will have configured in sites-enabled.