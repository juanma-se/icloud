## Quick Start
- Clone the repo: `git clone https://github.com/juanma-se/icloud.git`

### <i>Prerequisites</i>
Asegúrate de tener PHP, Composer y MySQL instalados en el sistema `>PHP 8.2` `Composer` `MySQL`.


### Instalación
``` bash
$ cd icloud
$ composer install
$ cp .env.example .env
$ php artisan key:generate
```
Configura los datos de entorno y ejecuta las migraciones

``` bash
$ php artisan migrate:fresh --seed
# Ya puedes correr la aplicación
$ php artisan serve
```

### <i>Librerías utilizadas</i>
### Spatie Roles and Permissions
- [repo](https://github.com/spatie/laravel-permission)
- [documentation](https://spatie.be/docs/laravel-permission/v6/introduction)

-- Roles: Representan diferentes niveles de acceso de los usuarios dentro de la aplicación (por ejemplo, Administrador, Responsable, Asignado).
-- Permisos: Definen acciones específicas que un usuario puede realizar (por ejemplo, editar usuarios, crear publicaciones).

#### Usuarios Generados
- Usuario Administrador: administrador@example.com (contraseña: password) - Asignado al rol "Administrador".
- Usuario Responsable: responsable@example.com (contraseña: password) - Asignado al rol "Responsable".
- Usuario Asignado: asignado@example.com (contraseña: password) - Asignado al rol "Asignado".

### Spatie Laravel Query Builder
- [repo](https://github.com/spatie/laravel-query-builder)
- [documentacion](https://spatie.be/docs/laravel-query-builder/v5/introduction)

Este paquete proporciona una forma más expresiva de construir consultas a la base de datos utilizando una interfaz fluida.

### Documentación de la API con Swagger
Se ha utilizado la librería [darkaonline/l5-swagger](https://github.com/DarkaOnLine/L5-Swagger) para generar automáticamente la documentación de la API.

#### Para acceder a la documentación:
Una vez levantada la app se puede acceder desde el siguiente enlace (se asume que se que se está usando el servidor de desarrollo de laravel).
[acceder a la documentación](http://localhost:8000/api/documentation)
