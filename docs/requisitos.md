# 1. Definición del problema
Configurar y seleccionar infraestructura Cloud (CPU, RAM, almacenamiento, regiones, disponibilidad, seguridad, backups y servicios de IA) resulta extremadamente complejo para un usuario sin perfil técnico avanzado debido a la gran cantidad de variables técnicas y económicas. Frente a una tienda convencional, nuestra plataforma aporta inteligencia y automatización para traducir necesidades en lenguaje natural a arquitecturas óptimas, tomando como referencia herramientas como DigitalOcean Marketplace, Google Cloud Pricing Calculator y AWS Architecture Center.

# 2. Objetivos del sistema
- **Objetivo principal:** Permitir a los usuarios diseñar, configurar, presupuestar y contratar de forma simulada infraestructuras Cloud a partir de necesidades tecnológicas descritas en lenguaje natural.
- **Objetivos secundarios:** 
  - Ofrecer recomendaciones automatizadas mediante IA.
  - Validar reglas técnicas de compatibilidad y seguridad.
  - Gestionar presupuestos, pasarela de pago simulada y contratación de infraestructura.

# 3. Actores de la aplicación
| Actor | Objetivo | Funcionalidades | Información consultada | Información modificada |
| :--- | :--- | :--- | :--- | :--- |
| **CLIENTE** | Diseñar, presupuestar y contratar infraestructura Cloud. | Crear proyectos, definir requerimientos, aceptar carritos y simular pagos. | Catálogo de productos, precios, regiones y presupuestos. | Proyectos, requerimientos, carrito y pedidos. |
| **ARQUITECTO** | Revisar y validar la coherencia técnica de las infraestructuras. | Analizar propuestas de arquitecturas y ajustar configuraciones. | Requerimientos de proyectos y componentes del catálogo. | Reglas de compatibilidad y propuestas de arquitectura. |
| **AUDITOR** | Inspeccionar la seguridad y cumplimiento normativo. | Revisar registros de auditoría y políticas de seguridad aplicadas. | Logs de actividad, configuraciones de red y firewalls. | Informes de auditoría y restricciones de seguridad. |
| **ADMINISTRADOR** | Gestionar de forma global la plataforma Cloud. | Administrar usuarios, catálogo de servicios, proveedores y regiones. | Usuarios, métricas globales del sistema y catálogo completo. | Datos de usuarios, precios, proveedores y servicios del catálogo. |

# 4. Mapa funcional de la aplicación
- **Módulos principales:** Usuarios, Catálogo de productos, Proveedores, Proyectos, Requerimientos, Carrito, Ítems, Pedidos, Pagos y Despliegues.
- **Funcionamiento por módulo:** Cada módulo gestiona un dominio específico de la plataforma, interactuando de forma que los Proyectos generan Requerimientos, estos se convierten en arquitecturas mediante el Carrito, y derivan en Pedidos y Despliegues simulados bajo estrictas reglas de negocio y validación.

# 5. Investigación del catálogo y componentes Cloud
El catálogo abarca áreas como Computación, Almacenamiento, Bases de Datos, Redes, Seguridad, Backups, Monitorización, Computación IA, Servicios IA y Soporte. 
- Se separa la información general (ID, nombre, proveedor, precio base) de la específica de cada tipo (ej. CPUs, RAM, motor de base de datos) para evitar tablas con decenas de columnas opcionales nulas.
- Se gestiona el concepto de Proveedores y Regiones para garantizar la residencia de datos y comprobar la disponibilidad geográfica de los servicios.

# 6. Proyectos y Requerimientos
- **Proyecto:** Representa el objetivo del cliente (ej. *Despliegue de una academia online*). Ejemplos: 1) Academia de formación, 2) E-commerce de alto tráfico, 3) Entorno de pruebas corporativo, 4) App móvil con base de datos en tiempo real, 5) Blog corporativo seguro.
- **Requerimientos estructurados:** Las necesidades se traducen a expresiones lógicas (ej. `ram >= 8`, `almacenamiento >= 100`, `region = EU`) para que la IA y el motor de reglas puedan interpretarlas de manera precisa y automatizada.