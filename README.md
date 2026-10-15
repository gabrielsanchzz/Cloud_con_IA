# Cloud_con_IA — Plataforma Inteligente de Infraestructuras Cloud

## 1. Descripción del Proyecto
Cloud_con_IA es una plataforma web orientada al backend desarrollada en Laravel que permite a los usuarios diseñar, configurar, presupuestar y simular la contratación de infraestructuras Cloud. El sistema integra capacidades de Inteligencia Artificial para ofrecer recomendaciones automatizadas de arquitecturas optimizadas en costes y rendimiento, asegurando siempre que la validación, la seguridad y la lógica crítica de negocio permanezcan bajo el control determinista de la aplicación Laravel.

## 2. Integrantes y Roles del Equipo
* **Gabriel Sánchez**: Coordinación Técnica, DevOps, GitOps, Seguridad y Control de Versiones (Rol 1)
* **Joselu**: Backend Developer, SysAdmin y Responsable del Entorno de Desarrollo (Rol 2)
* **Eliezer**: Analista Funcional y Requisitos (Rol 3)
* **Javi**: Arquitecto de Software, Dominio de Datos e Inteligencia Artificial (Rol 4)

## 3. Tecnologías y Stack Tecnológico
* **Sistema Operativo**: Ubuntu 24.04 LTS
* **Framework Backend**: Laravel 11 sobre PHP 8
* **Gestión de Dependencias**: Composer y npm / Node.js
* **Empaquetador Frontend**: Vite
* **Persistencia y Datos**: Base de datos relacional y SQLite para desarrollo
* **Control de Versiones y Repositorio**: Git, GitHub y TortoiseGit

## 4. Estructura de Documentación del Proyecto (docs)
Toda la documentación técnica del análisis funcional, arquitectura y diseño del sistema se encuentra unificada y estructurada dentro de la carpeta `docs` del repositorio:
* **`docs/entorno.md`**: Documentación técnica sobre la preparación, instalación y verificación reproducible del entorno de desarrollo (Ubuntu, PHP, Composer, Node.js y Laravel).
* **`docs/arquitectura.md`**: Descripción detallada del patrón arquitectónico MVC, el ciclo completo de una petición HTTP en Laravel y la propuesta de separación de responsabilidades por capas.
* **`docs/requisitos.md`**: Especificación completa del análisis funcional, abarcando los requisitos funcionales (RF01 a RF10), requisitos no funcionales (RNF01 a RNF05) y las reglas de negocio (RN01 a RN08).
* **`docs/casos-de-uso.md`**: Recopilación y detalle de los doce casos de uso principales de la aplicación (CU01 a CU12) con sus respectivos actores, flujos y postcondiciones.
* **`docs/roles-equipo.md`**: Definición de la organización interna del grupo, asignación de responsabilidades por roles y normativa interna de colaboración y control de versiones.
* **`docs/planificacion.md`**: Análisis de riesgos técnicos y de seguridad, medidas preventivas implementadas (protección del archivo `.env`) y backlog inicial de épicas para la planificación del desarrollo.

## 5. Estado Actual del Proyecto
El proyecto se encuentra en la **Fase 1 (UT1)** completada: el entorno de desarrollo en Ubuntu está verificado y operativo, el repositorio compartido en GitHub está configurado bajo una estricta política de seguridad (exclusión de credenciales y uso de `.gitignore`), se ha creado la estructura base de documentación en `docs` y el proyecto Laravel inicial cuenta con la vista de comprobación del patrón MVC.