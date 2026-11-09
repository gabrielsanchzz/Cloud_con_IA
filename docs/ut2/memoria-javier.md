# Memoria UT2 - Parte de Javier

Aquí están los apartados que me tocan a mí (carrito, presupuesto, ficheros, pruebas y seguridad). Luego los pasamos a la plantilla. Las capturas están en `docs/ut2/capturas/`.

## 8. Selección, presupuesto histórico, transacción y Storage

### 8.1. Selección/carrito en base de datos

| Decisión | Descripción específica | Archivo/test |
|---|---|---|
| Persistencia sin login | En la UT2 todavía no hay login, así que el carrito no lo podemos poner por usuario como dijimos en la UT1. Lo hemos puesto por proyecto: cada proyecto tiene un carrito. Cuando haya usuarios, el proyecto será de un usuario y el carrito seguirá igual. | Migración `create_carritos_table` |
| Líneas y configuraciones | Cada producto que se añade es una fila en `carrito_lineas` con el producto, la cantidad y la configuración (en JSON). Si añades un producto que ya estaba, se suma la cantidad en vez de salir repetido. | `CarritoLinea.php`, `CarritoController@agregar` |
| Recalcular importes | En el carrito no guardamos el total. Cada vez que se abre, el `PresupuestoService` lo calcula con el precio que tiene el producto en la base de datos, y separa lo mensual de lo que se paga una vez. Desde el formulario solo se manda el producto y la cantidad, el precio nunca. | `PresupuestoService.php` |
| Carrito vacío o inválido | Si el carrito está vacío no deja emitir el presupuesto y sale un mensaje. Tampoco deja si algún producto ya no está activo. La cantidad tiene que ser entre 1 y 100. | Test `test_un_carrito_vacio_no_emite_presupuesto`, captura `9.2-carrito-vacio.png` |
| Quitar/editar líneas | Cambiar la cantidad hace un update de la línea (PATCH) y quitar la borra (DELETE). Al estar en la base de datos, si recargas la página el carrito sigue igual. | Test `test_se_puede_cambiar_la_cantidad_y_quitar_una_linea`, captura `8.1-carrito-con-productos.png` |

### 8.2. Presupuesto como snapshot histórico

| Dato congelado al emitir | Cómo se conserva (líneas/JSON/mixto) | Comprobación de inmutabilidad |
|---|---|---|
| Nombre/tipo del elemento | Se copian en `nombre_producto` y `tipo_producto` de la tabla `presupuesto_lineas`. | En el T09 cambiamos el nombre del producto y el presupuesto sigue con el nombre de antes. |
| Configuración/cantidad | La cantidad en su columna y la configuración en JSON. De momento la configuración va vacía porque las arquitecturas las hace otro compañero. | Al emitir se vacía el carrito, así que el presupuesto ya no depende de él. |
| Tarifa y periodicidad | Se guarda el precio de ese momento, si es mensual o único y el subtotal de la línea. | En el T09 el precio pasa de 20 € a 35,50 € y en el presupuesto sigue a 20 €. |
| Totales/fecha de emisión | En `presupuestos` guardamos el total mensual, el total único, la fecha y el nombre del proyecto. | El T09 también comprueba que el total sigue igual (60 €). |

Lo hemos hecho mixto: columnas normales para los precios y la cantidad, que son lo que se consulta, y JSON solo para la configuración porque cambia según el producto. La línea también guarda el `producto_id`, pero si se borra el producto se queda a null y el presupuesto no pierde nada porque los datos ya están copiados.

**Prueba histórica:**

1. Emitimos el presupuesto #1 con 3 productos. El "Servidor 884" costaba 35,91 € al mes y pusimos 2, así que el subtotal era 71,82 €. Total mensual 158,67 € y pago único 47,19 € (`8.2-presupuesto-emitido.png`).
2. Con tinker cambiamos el producto a "Servidor 884 PRO" y 99,99 €. En el carrito ya sale con el nombre y precio nuevos (`8.2-catalogo-cambiado.png`).
3. Volvimos a abrir el presupuesto #1 y sigue saliendo "Servidor 884" a 35,91 € y el total 158,67 € (`8.2-presupuesto-sin-cambios.png`).

Esto mismo es lo que comprueba el test T09.

### 8.3. Transacción y consistencia

| Operación de emisión | Escrituras dentro de la transacción | Fallo controlado / rollback esperado |
|---|---|---|
| Emitir presupuesto (`PresupuestoService@emitir`) | 1. Crear el presupuesto con los totales. 2. Crear una línea por cada producto del carrito. 3. Vaciar el carrito. | En el T10 hacemos que falle al guardar la segunda línea. Después comprobamos que no hay ningún presupuesto ni ninguna línea, y que el carrito sigue con sus 2 productos. |

**¿Qué garantiza DB::transaction() y qué NO revierte cuando se almacena un fichero?**

Con `DB::transaction()` todo lo que se guarda en la base de datos dentro de la transacción va junto: o se guarda todo o no se guarda nada. Si salta un error a mitad, Laravel hace rollback y la base de datos se queda como estaba. Así no puede quedar un presupuesto a medias o un carrito vaciado sin presupuesto.

Lo que no deshace es lo que no está en la base de datos, como los archivos. Si guardamos un archivo con Storage y después falla el insert, el rollback no borra el archivo y se queda en el disco sin que ninguna fila lo apunte. Por eso en los archivos lo hacemos a mano: si falla la base de datos, borramos el archivo que acabamos de subir (lo explico en el 8.4). Y lo mismo si se borra un proyecto: la base de datos borra sus filas de archivos, pero los archivos del disco hay que borrarlos aparte.

### 8.4. Subida y ciclo de vida del fichero

| Aspecto | Elección del equipo | Evidencia / prueba |
|---|---|---|
| Entidad a la que se adjunta | Al proyecto, porque es donde tiene sentido que el cliente suba algo (un documento con lo que quiere, un diagrama...). Un proyecto puede tener varios archivos. | `Proyecto.php` (relación `archivos`) |
| Formulario multipart, validación tipo/tamaño | El formulario lleva `enctype="multipart/form-data"` y `@csrf`. En `SubirArchivoRequest` solo dejamos PDF, PNG y JPG de 1 MB como mucho, con los mensajes en español. Pusimos 1 MB porque PHP ya corta a los 2 MB. | `SubirArchivoRequest.php`, captura `9.2-fichero-rechazado-y-subida-correcta.png` |
| Disco, ruta y metadatos en BD | Usamos el disco `local`, que es privado. Los archivos van a `proyectos/{id}/` con un nombre que pone Laravel, y en la tabla `archivos` guardamos el nombre original, la ruta, el tipo y el tamaño. | `ArchivoController@store` |
| Acceso/visualización | Como el disco es privado, no se puede abrir el archivo poniendo la URL. Se ve desde la ruta `/archivos/{id}`, que lo devuelve con `Storage::response()`. | `ArchivoController@show` |
| Sustitución y eliminación | Al sustituir guardamos primero el nuevo, actualizamos la fila y al final borramos el viejo. Al eliminar borramos la fila y el archivo. Lo miramos en la carpeta después de sustituir y solo quedaba el archivo nuevo. | Test `test_t08_sustituye_el_archivo_y_borra_el_anterior`, captura `8.4-archivo-sustituido.png` |
| Fallo de BD después de escribir archivo | El insert va en un try/catch. Si falla, borramos el archivo que se acaba de guardar y volvemos a lanzar el error, así no quedan archivos sueltos. | `ArchivoController.php`, líneas 27-38 y 60-70 |
| Storage::fake / UploadedFile::fake | Hay 4 tests con el disco falso: subir un PDF, rechazar un `.exe`, sustituir y una subida que falla. | `tests/Feature/ArchivoTest.php` |

## 9.1. Tests automatizados (T08, T09 y T10)

| ID y prueba exigida | Unit/Feature + archivo | Entrada y aserción verificable | Resultado |
|---|---|---|---|
| T08 · Storage / archivo | Feature, `ArchivoTest.php` (4 tests que empiezan por `test_t08_`) | Subimos un PDF falso y comprobamos que está en el disco y en la tabla. Un `.exe` da error y no se guarda nada. Al sustituir, el archivo viejo ya no existe. Una subida fallida da el error en español. | Pasa (09/11/2026) |
| T09 · Snapshot histórico | Feature, `PresupuestoTest.php` (`test_t09_el_presupuesto_no_cambia_si_se_modifica_el_catalogo`) | Producto a 20 € al mes, cantidad 3. Emitimos, cambiamos el nombre y el precio a 35,50 €, y la línea sigue con el nombre viejo, 20 € y subtotal 60 €. | Pasa (09/11/2026) |
| T10 · Transacción / fallo | Feature, `PresupuestoTest.php` (`test_t10_si_falla_la_emision_no_queda_nada_a_medias`) | Carrito con 2 productos. Forzamos un error en la segunda línea y comprobamos que hay 0 presupuestos, 0 líneas y el carrito sigue con 2. | Pasa (09/11/2026) |

También hay 4 tests del carrito en `CarritoTest.php`. Captura de los tests pasando: `9.3-tests-pass-y-log-sin-error.png`.

## 9.2. Pruebas manuales (mi parte)

| Escenario de entrada | Esperado | Obtenido y evidencia |
|---|---|---|
| Cantidad/configuración fuera de rango | Con cantidad 0 o más de 100 no se añade. | Sale "La cantidad mínima es 1." y no se guarda. Test `test_rechaza_una_cantidad_fuera_de_rango`. |
| Carrito vacío | No se emite el presupuesto. | Sale "El carrito está vacío, no se puede emitir el presupuesto." y no aparece ningún presupuesto. `9.2-carrito-vacio.png` |
| Fichero rechazado | Un archivo que no es PDF, PNG o JPG no se sube. | Sale "Solo se permiten archivos PDF, PNG o JPG." y no sale en la lista. `9.2-fichero-rechazado-y-subida-correcta.png` |
| Tarifa modificada tras emitir presupuesto | El presupuesto no cambia. | El presupuesto #1 sigue a 35,91 € aunque el producto ahora cuesta 99,99 €. `8.2-catalogo-cambiado.png` y `8.2-presupuesto-sin-cambios.png` |

## 9.3. Investigación real de un fallo

| Momento | Describir con datos/archivos |
|---|---|
| Síntoma reproducible | Al subir cualquier archivo en `/proyectos/1/archivos`, aunque fuera un PDF pequeño, salía "The archivo failed to upload." y no se subía. Encima el mensaje salía en inglés. |
| Hipótesis inicial | Pensamos que el archivo pesaba más de 2 MB y PHP lo cortaba, porque en Herd vimos que el límite era 2 MB. |
| Herramienta utilizada | Miramos la consola de `php artisan serve`, `php --ini` para ver qué `php.ini` usaba Herd, y un test. |
| Causa confirmada | En la consola salía `unable to create a temporary file`. No era el tamaño: PHP no podía crear el archivo temporal. En el `php.ini` de Herd ponía `sys_temp_dir = "/tmp"`, que es una ruta de Linux y en Windows no existe. Lo del inglés era porque nos faltaba el mensaje de la regla `uploaded` en `SubirArchivoRequest`. |
| Corrección aplicada | Cambiamos en el `php.ini` las carpetas temporales a `C:\Users\javie\AppData\Local\Temp` (lo pusimos en el README) y añadimos el mensaje `archivo.uploaded` en `SubirArchivoRequest.php`. Commit `2dfd5c7`, PR #4. |
| Test que inicialmente fallaba | `test_t08_subida_fallida_en_php_da_error_en_espanol`. Antes de arreglarlo fallaba porque salía "The archivo failed to upload." en vez del mensaje en español. Después pasa (`9.3-tests-pass-y-log-sin-error.png`). |

## 9.4. Seguridad básica e integridad (mi parte)

| Control que se ha comprobado | Cómo se verifica (ruta, test, revisión) |
|---|---|
| CSRF en formularios | Todos los formularios de archivos y del carrito llevan `@csrf`, y los de PATCH y DELETE también `@method`. |
| Validación servidor | `SubirArchivoRequest` para los archivos y `validate()` en el `CarritoController` para el producto y la cantidad. Lo comprueban los tests del `.exe` y de la cantidad fuera de rango. |
| Escape de salida HTML | En las vistas usamos siempre `{{ }}`. Buscamos `{!!` y no hay ninguno. Por ejemplo, el nombre del archivo lo escribe el usuario y se enseña con `{{ }}`. |
| Protección de asignación masiva | Todos los modelos tienen `$fillable` y no usamos `$request->all()`. Los ids de las relaciones no están en `$fillable`, los pone Laravel al crear desde la relación. |
| Integridad de FK y existencia | Todas las tablas tienen claves foráneas. No se puede borrar un proyecto que tiene presupuestos ni un producto que está en un carrito. Antes de añadir al carrito se comprueba que el producto existe. |
| Ausencia de .env y secretos en Git/ZIP | El `.gitignore` tiene `.env`, `vendor/`, `node_modules/` y las bases de datos. Quitamos del repo la base de datos `cloud_con_ia` que se había subido (PR #3). |

Falta revisar lo mismo en el código de los demás cuando esté subido.

## Anexo B. Rutas y evidencias (mi parte)

| ID | Ruta/artefacto exacto | Para qué sirve | Dónde se explica en memoria |
|---|---|---|---|
| EV-J01 | `routes/web.php:11-15` (rutas de archivos) | Subir, ver, sustituir y borrar archivos | 8.4 |
| EV-J02 | `routes/web.php:17-20` (rutas del carrito) | Carrito | 8.1 |
| EV-J03 | `routes/web.php:22-23` (rutas de presupuestos) | Emitir y ver presupuestos | 8.2, 8.3 |
| EV-J04 | `app/Services/PresupuestoService.php` | Copia de datos y transacción | 8.2, 8.3 |
| EV-J05 | `app/Http/Controllers/ArchivoController.php` | Borrar el archivo si falla la BD | 8.3, 8.4 |
| EV-J06 | Migraciones `create_carritos_table` y `create_presupuestos_table` | Tablas y claves foráneas | 8.1, 8.2 |
| EV-J07 | `tests/Feature/ArchivoTest.php` | T08 | 8.4, 9.1, 9.3 |
| EV-J08 | `tests/Feature/PresupuestoTest.php` | T09 y T10 | 8.2, 8.3, 9.1 |
| EV-J09 | `tests/Feature/CarritoTest.php` | Tests del carrito | 8.1, 9.2 |
| EV-J10 | PR #3, #4 y #5 | Ramas y revisiones | 3, 9.4 |
| EV-J11 | `docs/ut2/capturas/` | Capturas de las pruebas | 8, 9.2, 9.3 |
