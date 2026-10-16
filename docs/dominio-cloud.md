# Análisis del dominio Cloud - Cloud_con_IA

## 1. Catálogo de productos

En el catálogo vamos a tener productos de varios tipos. Para ver qué datos tiene cada uno hemos mirado las calculadoras de precios de AWS, Azure y Google Cloud.

Todos los productos tienen unos datos comunes: código, nombre, descripción, tipo, proveedor, regiones, precio, forma de pago (mensual, por uso o único) y estado (activo o retirado). Después cada tipo tiene sus propios datos:

| Tipo | Datos propios | Ejemplo |
|---|---|---|
| COMPUTACIÓN | CPU, RAM, disco, sistema operativo | 2 CPU, 4 GB RAM, Ubuntu |
| ALMACENAMIENTO | Tipo y capacidad | 250 GB |
| BASES DE DATOS | Motor, versión, tamaño | MySQL 8, 20 GB |
| REDES | Tipo (IP pública, balanceador, DNS) | Balanceador de carga |
| SEGURIDAD | Tipo (firewall, SSL) | Firewall con 20 reglas |
| BACKUP | Frecuencia y días que se guarda | Diario, 7 días |
| MONITORIZACIÓN | Qué vigila y cómo avisa | Aviso por email |
| COMPUTACIÓN_IA | Como computación pero con GPU | 1 GPU de 24 GB |
| SERVICIO_IA | Tipo de IA y límite de uso | API de texto |
| SOPORTE | Nivel y tiempo de respuesta | Instalación, 25 € una vez |

Hay que tener en cuenta que la base de datos de nuestra aplicación no es lo mismo que los productos de tipo “Bases de datos” del catálogo.

### ¿Por qué no una sola tabla con 50 columnas?

- Casi todas las columnas estarían vacías (un firewall no tiene RAM).
- No se podrían poner campos obligatorios, porque cada tipo necesita unos distintos.
- Para añadir un tipo nuevo habría que cambiar toda la tabla.

Por eso separamos los datos comunes de los específicos. Así el carrito y los presupuestos solo usan los comunes y funcionan con cualquier producto.

### Propuesta

Una tabla “productos” con los datos comunes y un campo “tipo” (un Enum en Laravel). Los datos específicos irán en una tabla por tipo o en un campo JSON, lo decidiremos en la UT2.

Lo que el producto permite (por ejemplo, de 2 a 64 GB de RAM) no es lo mismo que lo que elige el cliente (8 GB). Lo que elige el cliente se guarda en los Arquitectura Items.

## 2. Proveedores y regiones

En la aplicación cada producto lo ofrece un proveedor, y cada proveedor tiene sus servidores en varias regiones. Una región es una zona del mundo donde el proveedor tiene sus centros de datos. Por ejemplo:

```
NebulaCloud
├── Madrid
├── Frankfurt
└── Virginia
```

Un proveedor tiene varias regiones, pero cada región es de un solo proveedor. Además, un producto no tiene por qué estar en todas las regiones del proveedor, así que hay que guardar en qué regiones está disponible cada producto.

### Residencia de datos

La residencia de datos es el sitio donde se guardan físicamente los datos. Es importante porque cada zona tiene sus propias leyes. Por ejemplo, en Europa se aplica el RGPD, y una empresa europea que guarda datos de sus clientes puede necesitar que esos datos no salgan de Europa. Por eso en un proyecto el cliente puede poner como requerimiento “region = EU”.

### Diferencias entre EU, US y Asia

- Leyes: cada zona tiene sus propias leyes de protección de datos.
- Velocidad: cuanto más cerca estén los servidores de los usuarios, más rápido carga todo.
- Precio: el mismo producto puede costar distinto según la región.

### ¿Por qué un producto puede no estar en una región?

- Porque el proveedor no tiene ese hardware ahí, por ejemplo servidores con GPU para IA.
- Porque los servicios nuevos suelen salir primero en algunas regiones.
- Por temas legales del país.

En la aplicación, cuando el cliente elija un producto, Laravel comprobará que está disponible en la región que ha elegido. Si no lo está, no le dejará añadirlo.

## 3. Proyectos y requerimientos

### ¿Qué es un proyecto?

Un proyecto es lo que el cliente quiere conseguir, por ejemplo montar la web de una academia. No es una lista de productos, sino la necesidad que tiene. A partir del proyecto la aplicación (o la IA) propone qué productos necesita.

Un proyecto guarda estos datos: nombre, descripción, presupuesto máximo al mes, número de usuarios que se esperan, nivel de seguridad (bajo, medio o alto) y región donde quiere los datos.

### Ejemplos de proyectos

| Proyecto | Usuarios | Presupuesto | Lo que necesita |
|---|---|---|---|
| Academia online con Laravel | 500 | 50 €/mes | Servidor, base de datos, backup diario, datos en Europa |
| Tienda online | 5.000 | 300 €/mes | Varios servidores, balanceador, base de datos con réplica, firewall |
| Entorno de pruebas para una empresa | 20 | 30 €/mes | Un servidor pequeño, sin backup |
| App móvil con base de datos | 2.000 | 150 €/mes | Servidor, base de datos, almacenamiento para imágenes, monitorización |
| Blog de una empresa | 1.000 | 20 €/mes | Servidor pequeño, certificado SSL, backup semanal |

### De texto a requerimientos

El cliente puede escribir lo que necesita en texto, por ejemplo: “Necesito una web para una academia con 500 usuarios, datos en Europa y backup diario”. El problema es que la aplicación no puede comprobar un texto, así que hay que pasarlo a requerimientos con un formato fijo:

| Atributo | Operador | Valor | Tipo |
|---|---|---|---|
| usuarios | >= | 500 | Obligatorio |
| region | = | EU | Obligatorio |
| backup | = | diario | Obligatorio |
| precio_mensual | <= | 50 | Obligatorio |
| ram | >= | 4 | Opcional |

Cada requerimiento tiene un atributo, un operador (=, >=, <=), un valor y si es obligatorio u opcional.

- Obligatorio: se tiene que cumplir sí o sí. Si una arquitectura no lo cumple, Laravel no la acepta.
- Opcional: es una preferencia. Si se puede cumplir mejor, pero no es imprescindible.

### ¿De dónde salen los requerimientos?

- Del cliente, que los pone a mano en el formulario del proyecto.
- De la IA, que lee el texto del cliente y propone los requerimientos. El cliente los revisa antes de guardarlos.
- De las reglas de la aplicación, por ejemplo, si el proyecto tiene seguridad alta, se añade un firewall obligatorio.

### Ventajas

- Laravel puede comprobar automáticamente si una arquitectura cumple lo que pide el cliente.
- Se pueden comparar varias propuestas entre sí.
- La IA recibe los datos ordenados, así que sus recomendaciones son más fáciles de comprobar después.

---------------------------------------------------------------------------------------------------------------------------------

## Referencias

| N.º | Fuente | URL |
|---|---|---|
| 1 | DigitalOcean Marketplace | https://marketplace.digitalocean.com/ |
| 2 | AWS Pricing Calculator | https://calculator.aws/ |
| 3 | Azure Pricing Calculator | https://azure.microsoft.com/es-es/pricing/calculator/ |
| 4 | Google Cloud Pricing Calculator | https://cloud.google.com/products/calculator |
