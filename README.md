Este proyecto pretende ser un marco básico que sirva de base para aplicaciones web simples y modulares.

Para una buena experiencia de desarrollo, este proyecto proporciona al desarrollador herramientas que le permiten concentrarse en la construcción de su proyecto, sin entrar en algunos tediosos detalles de implementación.

Desde el desarrollador principiante hasta el más experimentado, este proyecto puede crecer contigo. Experimenta y explota todo el potencial de una nueva aplicación partiendo de una buena base, e incluso atrévete a ampliar esta base con nuevas implementaciones que aporten más funcionalidad al proyecto.

# Cómo empezar

Para comenzar, debe tener instalada una versión de `php` 7 o superior. A continuación, puede descargar o clonar este proyecto desde esta URL.
```
https://github.com/AbrahanZarza/php-app.git
```

## Instalación

Una vez descargado el proyecto, instalaremos las dependencias a través de Composer.
```
composer install
```

Lo siguiente será configurar nuestro archivo de entorno. Para hacer esto, lo primero es crear una copia del archivo de entorno de ejemplo, renombrando esta copia como `.env`.
```
cp .env.example .env
```
> Este archivo especificará las variables de cada entorno en el que se aloja la aplicación, en este caso su entorno local.

Finalmente, iniciamos un servidor de prueba con el que podemos acceder a nuestra aplicación web y empezar a desarrollar.
```
php -S localhost:8080 public/index.php
```

## Estructura
La estructura de la aplicación predeterminada está destinada a proporcionar un punto de partida para aplicaciones web pequeñas y rápidas. Existen algunas restricciones, pero puede organizar su aplicación web como lo necesite.

### Directorio raíz

#### 📂 Directorio `app`
Este directorio contiene el código central de su aplicación. Casi todas las clases de su aplicación estarán en este directorio.

#### 📂 Directorio `bootstrap`
Este directorio contiene el archivo helper.php que proporciona métodos útiles para la aplicación. Si es necesario, puede agregar más archivos de ayuda aquí, pero recuerde agregarlos en la sección `autoload.files` del archivo` composer.json`.

#### 📂 Directorio `config`
Este directorio debe contener todos los archivos de configuración de su aplicación. Por el momento, el archivo de rutas de la aplicación principal está aquí.

#### 📂 Directorio `public`
Este directorio contiene el archivo de punto de entrada de la aplicación web, que configura los archivos de carga automática. Este directorio también debe almacenar archivos de activos como imágenes, JavaScript y CSS.

### El directorio `app`
Este directorio contiene la mayor parte del código de su aplicación. De forma predeterminada, este directorio tiene un espacio de nombres en App, utilizando el estándar PSR-4 en el archivo `composer.json`.

De forma predeterminada, este directorio contiene directorios adicionales, como Controladores y Clases, que brindan funcionalidad a la aplicación.

#### 📂 Directorio `Classes`
Este directorio contiene archivos que dan lógica básica a su aplicación web. Normalmente, no es necesario editar o crear nuevos archivos dentro de este directorio.

#### 📂 Directorio `Controllers`
Este directorio contiene las principales acciones que se ejecutarían cuando se solicite cualquier ruta en su aplicación.

#### 📂 Directorio `Exceptions`
Este directorio contiene tipos de errores que su aplicación, en cualquier momento, puede generar. Estaba destinado a identificar y depurar cualquier error de la aplicación.

#### 📂 Directorio `Middlewares`
Este directorio contiene mecanismos que verifican acciones para ejecutar, o no, otras acciones posteriores en su aplicación, por ejemplo, acciones de autenticación.


# Lo básico

## Rutas
El proyecto base proporciona herramientas para crear puntos de entrada a la aplicación de una manera simple y limpia.

Por el momento, los métodos que acepta son: `GET`,` POST`, `PUT` y` DELETE`. Estos son los más utilizados y básicos para la creación de cualquier tipo de aplicación web.

### Enrutamiento básico
Un ejemplo básico puede ser el que se muestra a continuación.
```
Router::route(Request::METHOD_GET, '/', function (Request $request) {
    response('Hello app!');
});
```

Como puede ver, el proyecto escucha una solicitud `GET` a la ruta` /`, si esta solicitud llega, devolverá la cadena de texto que se muestra en la pantalla como respuesta.

### Enrutamiento a controlador
Otra forma aún más limpia de implementar rutas en su aplicación es llamar directamente a una acción de controlador.

Hasta ahora hemos visto cómo ejecutar una función cuando se solicita una determinada ruta, pero el proyecto también ofrece la opción de llamar directamente a un método de un controlador.

Para hacer esto, necesitamos crear un controlador en el directorio `app / controllers`. Luego especificaremos la ruta como se muestra a continuación.
```
Router::route(Request::METHOD_GET, '/', 'IndexController@someMethod');
```
> En esta llamada estamos solicitando la ruta `/` para que `GET` invoque el método` someMethod` del controlador` IndexController`.

## Middlewares
Un middleware es un mecanismo que utiliza el proyecto para ejecutar fragmentos de código como verificación antes de una acción.

Si el resultado de esta comprobación es satisfactorio, se ejecutará la acción principal que la precede.

Un ejemplo muy claro de middleware es la autenticación de usuarios. Para el ejemplo, podemos ver el middleware en la ruta `app / Middlewares` llamado` Auth.php`.

Como puede ver, todo el middleware que creemos tendrá el método `__invoke`. Dentro de esto es donde se ejecutará la funcionalidad del middleware.

En este ejemplo, estamos especificando que el encabezado `X-Api-Key` debe llegarnos a través de la solicitud.

Finalmente, para aplicar el middleware a nuestras rutas de aplicación lo especificaremos como cuarto parámetro, indicando el nombre de la clase de nuestro middleware de la siguiente manera.
```
Router::route(Request::METHOD_GET, '/', function (Request $request) {
	response('Hello app!');
}, Auth::class);
```

## Controladores
Son los archivos que ejecutan las acciones de la aplicación web y están definidos en la ruta `app / Controllers`.

Estos normalmente son llamados por rutas, de modo que reciben una entrada, procesan la información y devuelven una salida con la información procesada.

Podemos crear tantos controladores como necesitemos, no dude en implementar tantos como necesite.

## Peticiones
Todas las peticiones realizadas a nuestra aplicación web tienen asociado un objeto de la clase `Solicitud`.

Este objeto nos proporciona información útil sobre la petición, información como el método, los parámetros y todo tipo de información adicional.

Por ejemplo, para poder leer un parámetro, esto sería una implementación de esa acción.
```
public function doSomething(Request $request) {
    echo $request->get('param_name');
}
```
> El objeto `$request` obtendrá el parámetro solicitado tanto al inspeccionar la cadena de consulta como el cuerpo de la solicitud.

## Respuestas
En respuesta a una solicitud en la aplicación, tenemos una función en el archivo `bootstrap / helper.php` llamada` response`.

Esta función se encarga de tratar los datos que recibe para dar una respuesta codificada al cliente.

Parámetros permitidos:

- `$data` - requerido - Será la respuesta que llegará al cliente.
- `$status` - opcional - Es el código estándar HTTP para la respuesta.
- `$headers` - opcional - Son los encabezados que tendrán esa respuesta asociada.

### Ejemplos de `response`

#### Ejemplo básico
```
response(['foo' => 'bar']);
```

#### Ejemplo con status code
```
response('User not authorized!', \App\Classes\Response::HTTP_UNAUTHORIZED);
```

#### Ejemplo completo
```
response(['foo' => 'bar'], \App\Classes\Response::HTTP_SUCCESS, ['Content-Type' => 'application/json']);
```

## Depuración
Como utilidad de depuración tenemos, en el archivo `bootstrap / helper.php`, tenemos los métodos` debug` y` dddebug`.

### Método `debug`
Este método se utiliza para imprimir, en detalle, el contenido de una variable. Esto será de gran utilidad a la hora de desarrollar nuestra aplicación web.

### Método `dddebug`
Este método funciona igual que `debug`, pero a diferencia del anterior, al invocar este método estaremos deteniendo la ejecución del código al imprimir el contenido de la variable a depurar.

# Despliegue en producción
Para implementar nuestro código en producción, se deben considerar un par de criterios:

- Tener instalada una versión de `php` 7 o superior.
- Tener instalado un servidor web, entre los más comunes: `nginx` o` apache`.

Independientemente de la elección, debemos indicar a nuestro servidor web que el punto de entrada de nuestra aplicación será desde el directorio `público`, con el archivo` index.php`.

## Ejemplo de fichero de despliegue `nginx`
A continuación se muestra un ejemplo de cómo se vería un archivo de implementación básico para los servidores web `nginx`.
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
> Siendo `/var/www/html` la ruta predeterminada que nginx habrá configurado en los sites-enabled.

## Ejemplo de fichero de despliegue `apache`
A continuación se muestra un ejemplo de un archivo de implementación básico con `apache` y` virtual hosts`.
```
<VirtualHost *:80>
    DocumentRoot "/var/www/html/public"
    ServerName mydomain.com

    # Other directives here ...
</VirtualHost>
```
> Siendo `/var/www/html` la ruta predeterminada que apache habrá configurado en sus sites-enabled.