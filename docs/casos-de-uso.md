# Casos de uso de la plataforma

1. **CU-01: Registro de usuario**
   - **Actor:** Cliente / Visitante
   - **Descripción:** El usuario se registra en la plataforma introduciendo sus credenciales.
   - **Precondiciones:** No estar registrado previamente.
   - **Flujo principal:** Introducir datos -> Validar email -> Guardar en base de datos -> Redirigir al panel.
   - **Resultado:** Usuario activo en el sistema.

2. **CU-02: Inicio de sesión**
   - **Actor:** Todos los roles
   - **Descripción:** Acceso a la plataforma con usuario y contraseña.
   - **Precondiciones:** Estar registrado.
   - **Flujo principal:** Introducir credenciales -> Validación de seguridad -> Acceso concedido.
   - **Resultado:** Sesión iniciada.

3. **CU-03: Creación de un nuevo proyecto Cloud**
   - **Actor:** Cliente
   - **Descripción:** Definir un nuevo objetivo tecnológico y presupuesto.
   - **Precondiciones:** Estar autenticado.
   - **Flujo principal:** Indicar nombre, descripción y presupuesto máximo -> Guardar proyecto.
   - **Resultado:** Proyecto creado y listo para añadir requerimientos.

4. **CU-04: Definición de requerimientos tecnológicos**
   - **Actor:** Cliente
   - **Descripción:** Especificar restricciones técnicas (RAM, disco, región).
   - **Precondiciones:** Tener un proyecto creado.
   - **Flujo principal:** Seleccionar parámetros estructurados -> Guardar requerimientos.
   - **Resultado:** Requerimientos asociados al proyecto.

5. **CU-05: Solicitud de recomendación por IA**
   - **Actor:** Cliente
   - **Descripción:** Obtener una propuesta de infraestructura basada en los requerimientos.
   - **Precondiciones:** Proyecto con requerimientos definidos.
   - **Flujo principal:** Enviar datos a la IA -> Recibir propuesta de arquitectura -> Mostrar alternativa.
   - **Resultado:** Propuesta arquitectónica sugerida.

6. **CU-06: Modificación de arquitectura por el Arquitecto**
   - **Actor:** Arquitecto
   - **Descripción:** Revisar y ajustar técnicamente la infraestructura propuesta.
   - **Precondiciones:** Existir una propuesta de arquitectura generada.
   - **Flujo principal:** Analizar componentes -> Modificar elementos incompatibles -> Guardar cambios.
   - **Resultado:** Arquitectura validada técnicamente.

7. **CU-07: Adición de elementos al carrito Cloud**
   - **Actor:** Cliente
   - **Descripción:** Incorporar los recursos aprobados al carrito de compra.
   - **Precondiciones:** Disponer de una arquitectura validada.
   - **Flujo principal:** Seleccionar recursos -> Calcular costes mensuales y únicos -> Añadir al carrito.
   - **Resultado:** Carrito actualizado con desglose de costes.

8. **CU-08: Generación de presupuesto formal**
   - **Actor:** Cliente
   - **Descripción:** Crear un documento de presupuesto con fecha de caducidad e impuestos.
   - **Precondiciones:** Tener elementos en el carrito.
   - **Flujo principal:** Calcular subtotal e impuestos -> Establecer fecha de caducidad -> Generar presupuesto.
   - **Resultado:** Presupuesto emitido listo para pago.

9. **CU-09: Simulación de pago de un pedido**
   - **Actor:** Cliente
   - **Descripción:** Abonar el importe del presupuesto mediante pasarela simulada.
   - **Precondiciones:** Disponer de un presupuesto válido no caducado.
   - **Flujo principal:** Confirmar pago simulado -> Cambiar estado del pedido a pagado -> Ejecutar transacción atómica.
   - **Resultado:** Pedido completado correctamente.

10. **CU-10: Despliegue simulado de infraestructura**
    - **Actor:** Sistema / Cliente
    - **Descripción:** Transformar el pedido pagado en recursos cloud simulados.
    - **Precondiciones:** Pedido con pago verificado.
    - **Flujo principal:** Asignar recursos virtuales -> Activar monitorización y firewalls -> Cambiar estado a desplegado.
    - **Resultado:** Infraestructura simulada en funcionamiento.

11. **CU-11: Auditoría de seguridad del sistema**
    - **Actor:** Auditor
    - **Descripción:** Revisar los registros de operaciones y accesos críticos.
    - **Precondiciones:** Estar autenticado como auditor.
    - **Flujo principal:** Consultar logs del sistema -> Filtrar por incidencias o cambios de arquitectura -> Generar informe.
    - **Resultado:** Informe de auditoría completado.

12. **CU-12: Gestión del catálogo de productos**
    - **Actor:** Administrador
    - **Descripción:** Añadir, modificar o eliminar servicios del catálogo cloud.
    - **Precondiciones:** Estar autenticado como administrador.
    - **Flujo principal:** Acceder al panel de gestión -> Actualizar precios o especificaciones -> Guardar cambios.
    - **Resultado:** Catálogo actualizado para nuevos proyectos.