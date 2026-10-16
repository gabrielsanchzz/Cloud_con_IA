# Análisis del dominio Cloud - Cloud_con_IA

## 1. Catálogo de productos y servicios

En este apartado vamos a analizar los tipos de productos que va a tener el catálogo de la aplicación. Para ello hemos mirado las calculadoras de precios de AWS, Azure y Google Cloud y el Marketplace de DigitalOcean, para ver qué datos piden al configurar cada producto.

Todos los productos tienen algunos datos en común: el nombre, el precio, el proveedor, etc. pero después cada tipo tiene sus propios datos. Por ejemplo, a un servidor virtual le elegimos la CPU y la RAM, y a una base de datos el motor y la versión.

Hay que tener en cuenta que la base de datos que usa nuestra aplicación Laravel para funcionar no es lo mismo que los productos de tipo “Bases de datos” que se venden en el catálogo.

### Datos comunes a todos los productos

- Código: para identificar el producto, por ejemplo “VM-STD-2”.
- Nombre y descripción: lo que ve el cliente en el catálogo.
- Tipo: si es de computación, almacenamiento, base de datos, etc.
- Proveedor: la empresa que ofrece el producto, por ejemplo “NebulaCloud”.
- Regiones: dónde se puede contratar.
- Precio y forma de pago: al mes, por uso o un pago único.
- Estado: si está activo o retirado. Así se puede quitar un producto del catálogo sin borrarlo, porque puede haber pedidos antiguos que lo tengan.

### Datos específicos de cada tipo

En la siguiente tabla se ven los datos propios de cada tipo de producto y un ejemplo de configuración:

| Tipo | Datos propios | Ejemplo |
|---|---|---|
| COMPUTACIÓN | CPU, RAM, disco y sistema operativo | Servidor con 2 CPU, 4 GB de RAM, 80 GB de disco y Ubuntu |
| ALMACENAMIENTO | Tipo de almacenamiento y capacidad | 250 GB de almacenamiento de archivos |
| BASES DE DATOS | Motor, versión, tamaño y réplicas | MySQL 8 con 20 GB y una réplica |
| REDES | Tipo (IP pública, balanceador, DNS...) y tráfico incluido | Balanceador de carga con 1 TB de tráfico al mes |
| SEGURIDAD | Tipo (firewall, certificado SSL...) y número de reglas | Firewall con 20 reglas |
| BACKUP | Cada cuánto se hace la copia, cuántos días se guarda y dónde | Copia diaria que se guarda 7 días en otra región |
| MONITORIZACIÓN | Qué se vigila, cada cuánto y cómo avisa | Revisión cada minuto y aviso por email |
| COMPUTACIÓN_IA | Igual que computación, pero con tarjeta gráfica (GPU) | Servidor con 1 GPU de 24 GB, 8 CPU y 32 GB de RAM |
| SERVICIO_IA | Tipo de IA, límite de uso y dónde se procesan los datos | API de texto con un límite de uso al mes, procesada en Europa |
| SOPORTE | Nivel, tiempo de respuesta y forma de pago | Ayuda con la instalación, 25 € en un pago único |

### ¿Por qué no hacer una sola tabla con 50 columnas?

Si pusiéramos todos los datos de todos los tipos en una sola tabla “productos”, nos saldrían unas 50 columnas y tendríamos varios problemas:

- La mayoría de columnas estarían vacías. Por ejemplo, un firewall no tiene RAM ni motor de base de datos.
- No podríamos poner campos obligatorios, porque la RAM es obligatoria en un servidor pero no en un backup.
- Se podrían guardar datos sin sentido, como un servidor con “motor MySQL”.
- Cada vez que quisiéramos añadir un tipo nuevo, habría que cambiar la tabla entera.

### Ventajas de separar los datos comunes de los específicos

- El catálogo, el carrito y los presupuestos solo usan los datos comunes, así que funcionan igual para cualquier tipo de producto.
- Cada tipo comprueba sus propios datos obligatorios.
- Se pueden añadir tipos nuevos sin tocar los que ya hay.
- La IA puede leer los datos de forma ordenada, por ejemplo “ram >= 8”, en vez de un texto.

### Propuesta inicial

Vamos a tener una tabla “productos” con los datos comunes y un campo “tipo”, que en Laravel será un Enum con los 10 tipos del catálogo.

Para los datos específicos tenemos dos opciones, que decidiremos en la UT2:

- Una tabla por cada tipo, enlazada con “productos”. Es más ordenado, pero son 10 tablas.
- Un campo JSON con la configuración, que Laravel comprueba según el tipo. Es más fácil de ampliar.

Por último, hay que diferenciar lo que permite un producto de lo que elige el cliente. Por ejemplo, un servidor del catálogo puede tener de 2 a 64 GB de RAM, pero el cliente elige 8 GB. Lo que elige el cliente no se guarda en el producto, sino en su arquitectura. Para eso sirven los Arquitectura Items: cada uno guarda un producto que ha elegido el cliente y con qué configuración.

## 2. Proveedores y regiones

## 3. Proyectos y requerimientos

---------------------------------------------------------------------------------------------------------------------------------

## Referencias

| N.º | Fuente | URL | Para qué la usamos |
|---|---|---|---|
| 1 | DigitalOcean Marketplace | https://marketplace.digitalocean.com/ | Ver cómo organizan el catálogo |
| 2 | AWS Pricing Calculator | https://calculator.aws/ | Ver qué se configura en cada producto |
| 3 | Azure Pricing Calculator | https://azure.microsoft.com/es-es/pricing/calculator/ | Ver tipos de producto, regiones y precios |
| 4 | Google Cloud Pricing Calculator | https://cloud.google.com/products/calculator | Ver cómo calculan el precio final |
