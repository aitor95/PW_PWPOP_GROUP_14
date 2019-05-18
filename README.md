# PWPOP

Esta práctica ha sido realizada por:
* Joan Lluís Mudarra Barros
* Aitor Blesa López

## Configuración

Para el correcto funcionamiento de la práctica, es necesario tener instalado [Composer](https://getcomposer.org/doc/00-intro.md#using-composer) y tener instalado  uno de estos servidores *Apache HTTP* 
- [XAMPP](https://www.apachefriends.org/es/download.html)
- [MAMPP](https://www.mamp.info/en/downloads/)

Además es necesario modificar el archivo **settings.php** que se encuentra dentro de la carpeta **config** en la raíz del proyecto.

Los Parámetros a cambiar son:
* **username**: Utilizar el usuario indicado de nuestro servidor apache.
* **password**: Utilizar el usuario indicado de nuesro servidor apache.
* **dbName**: Utilizar el nombre pwpop en nuestro caso.


```php
<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [ 
            'username' => 'root',
            'password' => '',
            'dbName' => 'pwpop',
            'host' => 'localhost',
        ],
    ],
];


```
Por último, destacar que hemos preparado un archivo sql que contiene el código necesario para poder crear nuestra Base de Datos y que sea posible la ejecución del proyecto, así como un archivo *UML* creado con [Star UML](http://staruml.io/download).
 
## Consideraciones
Hemos utilizado el método de encriptación *md5* para la contraseña.
## Organización

 Las tareas las hemos ido repartiendo de modo que cada miembro del grupo hiciese aquello que más le gustaba y que tuviera más facilidad a la hora de realizar las tareas.

### Horas Necesarias 
Hemos calculado que hemos necesitado aproximadamente 60 horas para poder realizar toda la práctica incluyendo las tareas opcionales.

### Problemas Encontrados
Uno de los miembros del grupo ha tenido problemas a la hora de poder hacer todos los *commits*, *push*/*pull* en `Github` por lo que no todos los *commits* se ven reflejados
