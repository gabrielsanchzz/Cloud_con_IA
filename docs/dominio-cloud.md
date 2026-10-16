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



---------------------------------------------------------------------------------------------------------------------------------

## Referencias

| N.º | Fuente | URL |
|---|---|---|
| 1 | DigitalOcean Marketplace | https://marketplace.digitalocean.com/ |
| 2 | AWS Pricing Calculator | https://calculator.aws/ |
| 3 | Azure Pricing Calculator | https://azure.microsoft.com/es-es/pricing/calculator/ |
| 4 | Google Cloud Pricing Calculator | https://cloud.google.com/products/calculator |
