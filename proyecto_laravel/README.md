# Cloud_con_IA - UT2

Aplicación en Laravel para hacer presupuestos de infraestructuras Cloud. En esta versión se pueden subir archivos a un proyecto, usar el carrito y emitir presupuestos, todo guardado en la base de datos.

DSW - 2º DAW - Curso 2026/2027

Integrantes: Gabriel Sánchez [apellido], Eliezer [apellidos], Joselu [apellidos] y Javier Moreno Pablo.

## 1. Requisitos

Lo que usamos nosotros:

- PHP 8.3
- Composer 2
- Node.js y npm
- Laravel 13
- SQLite (ya viene con PHP)

En Windows lo más fácil es instalar Laravel Herd (https://herd.laravel.com), que ya trae PHP y Composer. Después en la terminal:

```
herd use 8.3
php -v
composer -V
```

Node.js se descarga de https://nodejs.org (la versión LTS).

En Ubuntu los pasos están en `docs/entorno.md`.

## 2. Instalación

Dentro de la carpeta del proyecto (donde está el archivo `artisan`):

```
composer install
npm install
```

## 3. Configurar el .env

```
copy .env.example .env
php artisan key:generate
```

En Linux es `cp` en vez de `copy`.

Luego abrir el `.env` y poner una `#` delante de esta línea para que use SQLite:

```
# DB_DATABASE=cloud_con_ia
```

El `.env` no se sube a GitHub.

## 4. Base de datos

```
php artisan migrate
```

Si pregunta si quieres crear la base de datos, poner yes.

Para tener datos de prueba:

```
php artisan tinker --execute="App\Models\Proyecto::factory()->create()"
php artisan tinker --execute="App\Models\Producto::factory()->count(4)->create()"
```

## 5. Arrancar

```
npm run build
php artisan serve
```

Y abrir en el navegador:

- Inicio: http://127.0.0.1:8000
- Archivos del proyecto 1: http://127.0.0.1:8000/proyectos/1/archivos
- Carrito del proyecto 1: http://127.0.0.1:8000/proyectos/1/carrito

## 6. Tests

```
php artisan test
```

Los tests no tocan la base de datos ni los archivos de verdad, usan una base de datos en memoria y un disco falso.

## 7. Problemas en Windows

Si al subir un archivo sale "El archivo no se ha podido subir" y en la consola pone `unable to create a temporary file`, es por el `php.ini` de Herd, que trae `sys_temp_dir = "/tmp"` (una ruta de Linux).

Para arreglarlo:

1. Mirar dónde está el `php.ini` con `php --ini`.
2. Cambiar estas dos líneas poniendo tu usuario:
   ```
   sys_temp_dir = "C:\Users\TU_USUARIO\AppData\Local\Temp"
   upload_tmp_dir = "C:\Users\TU_USUARIO\AppData\Local\Temp"
   ```
3. Cerrar la terminal, abrirla otra vez y volver a hacer `php artisan serve`.

Si no encuentra `php` o `composer`, usar `cmd` o PowerShell. La terminal de MSYS2 (UCRT64) no ve los programas de Herd.

## 8. Qué hay en esta versión

Funciona:

- Subir, ver, sustituir y borrar archivos de un proyecto (PDF, PNG o JPG de hasta 1 MB).
- Carrito guardado en la base de datos: añadir, cambiar la cantidad y quitar.
- Emitir presupuestos. El presupuesto guarda una copia de los precios, así que no cambia aunque luego cambie el catálogo.

Falta (para la UT3 y UT4):

- Login y permisos.
- API REST.
- La IA.
- Pagos y despliegues.
