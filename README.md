# Cloud_con_IA — Plataforma Inteligente de Infraestructuras Cloud

## 1. Descripción del Proyecto
Cloud_con_IA es una plataforma web orientada al backend desarrollada en Laravel que permite a los usuarios diseñar, configurar, presupuestar y simular la contratación de infraestructuras Cloud. El sistema integra capacidades de Inteligencia Artificial para ofrecer recomendaciones automatizadas de arquitecturas optimizadas en costes y rendimiento, asegurando siempre que la validación, la seguridad y la lógica crítica de negocio permanezcan bajo el control determinista de la aplicación Laravel.

## 2. Integrantes y Roles del Equipo
* **Gabriel Sánchez**: Coordinación Técnica, DevOps, GitOps, Seguridad y Control de Versiones (Rol 1)
* **Eliezer**: Backend Developer, SysAdmin y Responsable del Entorno de Desarrollo (Rol 2)
* **Joselu**: Analista Funcional y Requisitos (Rol 3)
* **Javier**: Arquitecto de Software, Dominio de Datos e Inteligencia Artificial (Rol 4)

## 3. Tecnologías y Stack Tecnológico
* **Sistema Operativo**: Ubuntu 22.04.5 LTS
* **Framework Backend**: Laravel 13 sobre PHP 8.3.35
* **Gestión de Dependencias**: Composer y npm / Node.js
* **Empaquetador Frontend**: Vite
* **Base de Datos**: MySQL 8.0.46
* **Control de Versiones y Repositorio**: Git, GitHub y TortoiseGit

## 4. Estructura de Documentación del Proyecto (docs)
Toda la documentación técnica del análisis funcional, arquitectura y diseño del sistema se encuentra unificada y estructurada dentro de la carpeta `docs` del repositorio:
* **`docs/entorno.md`**: Documentación técnica sobre la preparación, instalación y verificación reproducible del entorno de desarrollo (Ubuntu, PHP, Composer, Node.js y Laravel).
* **`docs/requisitos.md`**: Definición del problema, objetivos del sistema, actores, requisitos y reglas de negocio.
* **`docs/casos-de-uso.md`**: Recopilación y detalle de los doce casos de uso principales de la aplicación (CU01 a CU12) con sus respectivos actores, flujos y postcondiciones.
* **`docs/modulos.md`**: Mapa funcional de la aplicación con la tabla de módulos y su diagrama.
* **`docs/dominio-cloud.md`**: Catálogo de productos Cloud, proveedores y regiones, proyectos y requerimientos estructurados.
* **`docs/modelo-datos.md`**: Modelo conceptual de datos con las entidades, relaciones y el diagrama E-R.
* **`docs/arquitectura.md`**: Arquitectura Laravel inicial: función de routes, Controllers, Models, Requests, Services, Resources y Enums, y separación de responsabilidades.
* **`docs/ia.md`**: Uso de la Inteligencia Artificial (la IA recomienda y Laravel decide), sus límites, riesgos y la matriz de riesgos técnicos y de seguridad.
* **`docs/roles-equipo.md`**: Definición de la organización interna del grupo, asignación de responsabilidades por roles y normativa interna de colaboración y control de versiones.
* **`docs/planificacion.md`**: Planificación inicial del desarrollo: fases de trabajo, prioridades, dependencias y reparto de responsabilidades.

## 5. Requisitos previos
* Ubuntu 22.04.5 LTS (o equivalente)
* PHP 8.3 con las extensiones mbstring, xml, curl, zip, pdo_mysql y bcmath
* Composer 2
* Node.js y npm
* MySQL 8
* Git

## 6. Instalación
```bash
git clone https://github.com/gabrielsanchzz/Cloud_con_IA.git
cd Cloud_con_IA/proyecto_laravel
composer install
npm install
```

## 7. Configuración
```bash
cp .env.example .env
php artisan key:generate
```
Después hay que poner en el `.env` los datos de la base de datos local (`DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`). El `.env` nunca se sube al repositorio.

## 8. Base de datos
MySQL, base de datos `cloud_con_ia` con un usuario propio (no root). Tras configurar el `.env`:
```bash
php artisan migrate
```

## 9. Ejecución
```bash
php artisan serve
```
Abrir http://127.0.0.1:8000 en el navegador.

## 10. Estado Actual del Proyecto
El proyecto se encuentra en la Fase 1 que está completada: el entorno de desarrollo en Ubuntu está verificado y operativo, el repositorio compartido en GitHub está configurado bajo una estricta política de seguridad (exclusión de credenciales y uso de `.gitignore`), se ha creado la estructura base de documentación en `docs` y el proyecto Laravel inicial cuenta con la vista de comprobación del patrón MVC.
