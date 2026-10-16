Planificación inicial del proyecto
1. Objetivo
Organizar el desarrollo de Cloud_con_IA, una plataforma web que permitirá definir proyectos tecnológicos, configurar infraestructuras Cloud, consultar recomendaciones, calcular presupuestos y simular su contratación y despliegue.
La planificación es inicial y podrá cambiar durante el curso. En la UT1 se prepara el entorno y se analiza la aplicación; la implementación de las funcionalidades se realizará progresivamente en las siguientes unidades.
La regla principal del proyecto es que la IA recomienda y Laravel valida y decide. No se contratarán servicios reales ni se desplegarán máquinas reales en proveedores Cloud: los pagos y despliegues serán simulados.
2. Fases de trabajo
Fase	Tareas principales	Resultado esperado
1. Preparación del entorno	Comprobar Ubuntu, PHP, Composer, Node.js, npm, Git, la base de datos y Laravel. Documentar las versiones y los pasos de instalación.	Entorno reproducible y funcional para todos los integrantes.
2. Repositorio y trabajo en equipo	Mantener el repositorio compartido, organizar las ramas main, develop, test y feature/*, y realizar commits identificables.	Trabajo colaborativo y cambios integrados de forma ordenada.
3. Análisis funcional	Definir el problema, los objetivos, los actores, los requisitos, las restricciones y los casos de uso. Revisar plataformas de referencia como AWS, Azure, Google Cloud y DigitalOcean.	Requisitos y casos de uso definidos.
4. Diseño de datos y arquitectura	Revisar las entidades y relaciones de modelo-datos.md; definir los módulos y la separación de responsabilidades en Laravel.	Modelo conceptual y arquitectura inicial documentados.
5. Catálogo, proveedores y regiones	Definir los tipos de servicios Cloud, sus datos comunes y específicos, los proveedores y las regiones en las que están disponibles.	Diseño del catálogo y de las reglas de disponibilidad.
6. Proyectos y requerimientos	Definir los datos de cada proyecto y cómo expresar necesidades como RAM mínima, almacenamiento, región o presupuesto máximo.	Requerimientos estructurados que puedan validarse desde la aplicación.
7. Arquitecturas y recomendaciones	Diseñar la creación y revisión de arquitecturas, sus componentes y las reglas de compatibilidad. Estudiar cómo la IA propondrá servicios y alternativas.	Proceso definido para generar y validar propuestas de infraestructura.
8. Precios, carrito y presupuestos	Diseñar el cálculo de precios mensuales y únicos, el carrito y los presupuestos con subtotal, impuestos, total y caducidad.	Proceso de presupuestación especificado.
9. Pedidos, pagos y despliegues simulados	Definir los estados de los pedidos y pagos, los posibles errores y la creación de recursos simulados tras una contratación.	Flujo de contratación y despliegue simulado definido.
10. Usuarios, permisos y auditoría	Establecer los permisos de Cliente, Arquitecto, Auditor y Administrador, y qué acciones importantes deben registrarse.	Control de acceso y auditoría definidos.
11. Implementación progresiva	Crear las funcionalidades en Laravel por módulos, integrar los cambios mediante Git y actualizar la documentación.	Aplicación desarrollada de forma incremental.
12. Pruebas y revisión	Probar los casos de uso, las validaciones, los cálculos, los permisos, los errores y la protección de datos sensibles.	Errores detectados y corregidos antes de la entrega.
13. Documentación y exposición	Actualizar los documentos Markdown, reunir capturas claras del entorno y del repositorio, y preparar la presentación del equipo.	Entregables y exposición preparados.


3. Orden de prioridad y dependencias
Se seguirá este orden general:
1. Verificar el entorno, Laravel, la base de datos y el repositorio compartido.
2. Completar y revisar los requisitos, actores y casos de uso.
3. Revisar el modelo conceptual y la arquitectura propuesta.
4. Definir el catálogo, proveedores, regiones y configuraciones de productos.
5. Diseñar la gestión de proyectos y los requerimientos estructurados.
6. Definir las arquitecturas y sus reglas de compatibilidad.
7. Diseñar el cálculo de precios, carrito y presupuestos.
8. Diseñar pedidos, pagos simulados, despliegues y auditoría.
9. Incorporar recomendaciones de IA con validaciones de Laravel.
10. Implementar, probar y documentar las funcionalidades por módulos.
Las tareas de documentación podrán realizarse en paralelo cuando no dependan de decisiones previas. No se empezará una funcionalidad sin tener claros, al menos, su objetivo, los datos que necesita y las reglas principales.
4. Reparto inicial de responsabilidades
Las responsabilidades se basan en la organización actual descrita en roles-equipo.md. Son una referencia inicial; todos los integrantes deben conocer el funcionamiento general del proyecto.
- Gabriel Sánchez — Coordinación técnica, DevOps, Git y seguridad: mantenimiento del repositorio, control de versiones, permisos, .gitignore y documentación del entorno.
- Eliezer — Backend y base de datos: apoyo en el entorno, lógica de negocio, rutas, controladores, modelos y migraciones de Laravel.
- Joselu — Análisis de requisitos y calidad: requisitos funcionales y no funcionales, casos de uso y comprobación de las funcionalidades.
- Javi — Arquitectura, modelo de datos e IA: diseño de módulos y entidades, arquitectura del sistema y propuesta de integración de inteligencia artificial.
El reparto podrá ajustarse en las siguientes fases. Las tareas importantes se revisarán entre varios integrantes para evitar que una sola persona conozca únicamente una parte del sistema.
5. Flujo de trabajo con Git y GitHub
1. Mantener main como rama estable y utilizar develop para integrar el trabajo en desarrollo.
2. Usar test para las comprobaciones acordadas por el equipo.
3. Crear ramas feature/nombre-tarea para desarrollar cambios concretos.
4. Hacer commits pequeños con mensajes claros y aportaciones identificables.
5. Sincronizar los cambios antes de empezar a trabajar y resolver los conflictos con el equipo.
6. Revisar los cambios antes de integrarlos en las ramas compartidas.
7. No subir .env, contraseñas, tokens, claves de API ni otros datos sensibles. Mantener un .env.example sin secretos.
6. Riesgos y medidas preventivas
Riesgo	Medida preventiva
El entorno funciona de forma diferente en cada equipo	Documentar versiones y pasos de instalación y comprobarlos en un entorno limpio.
Conflictos al integrar cambios	Trabajar con ramas, commits frecuentes y revisión antes de integrar.
Requisitos ambiguos o incompletos	Revisar los requisitos y casos de uso antes de implementar cada módulo.
Configuraciones Cloud incompatibles	Definir reglas y validarlas desde Laravel antes de aceptar una arquitectura.
Errores en los precios	Centralizar los cálculos y probar precios mensuales, pagos únicos, impuestos y totales.
Exposición de credenciales	Excluir .env del repositorio y revisar .gitignore y .env.example.
Recomendaciones incorrectas de la IA	Validar las recomendaciones con reglas de negocio; la IA no debe tomar decisiones críticas.
Fallos durante el pago o despliegue simulado	Definir estados, controlar errores y mantener coherentes los datos relacionados.
Reparto desigual del trabajo o retrasos	Revisar periódicamente las tareas y asegurar que todos contribuyen al repositorio y conocen el proyecto.


7. Seguimiento
El equipo revisará periódicamente las tareas completadas, pendientes y bloqueadas. La planificación se actualizará cuando cambien los requisitos o aparezcan nuevas dependencias.
Se considerará que una fase está preparada para continuar cuando sus tareas principales estén terminadas, la documentación esté actualizada y el equipo pueda explicar las decisiones tomadas.
8. Estado y alcance actual
El repositorio incluye documentación inicial sobre el entorno, requisitos, casos de uso, arquitectura, modelo de datos y organización del equipo. El siguiente paso es revisar que el entorno Laravel y la base de datos estén disponibles y comprobados para todos los integrantes, y completar los apartados de análisis que siguen pendientes antes de avanzar con la implementación.
Esta planificación no implica que todas las funcionalidades estén desarrolladas en la UT1. En esta primera fase se prepara el entorno y se define el proyecto; el desarrollo de la aplicación se realizará progresivamente en las siguientes unidades.