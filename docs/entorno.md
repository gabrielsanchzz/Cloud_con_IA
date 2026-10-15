# Configuración del Entorno de Desarrollo y Despliegue

**Proyecto:** Cloud_con_IA  
**Responsable de Entorno y DevOps:** Gabriel Sánchez

---

## 1. Stack Tecnológico del Proyecto

El proyecto está desarrollado utilizando las siguientes tecnologías y herramientas base:

* **Lenguaje principal:** PHP (Versión >= 8.1)
* **Framework Backend:** Laravel (Versión >= 10.x / 11.x)
* **Gestor de Dependencias:** Composer
* **Base de Datos:** MySQL / MariaDB (Entorno XAMPP o nativo)
* **Control de Versiones:** Git (Cliente gráfico: TortoiseGit / Git Bash)
* **Repositorio Remoto:** GitHub

---

## 2. Requisitos Previos e Instalación en Local

Para levantar el entorno de desarrollo localmente en cualquier equipo del equipo, seguir estos pasos:

### Paso 1: Clonar el Repositorio
```bash
git clone https://github.com/gabrielsanchzz/Cloud_con_IA.git
cd Cloud_con_IA
```
### Paso 2: Instalación de Dependencias de PHP
```bash
cd proyecto_laravel
composer install
```
### Paso 3: Configuración del Archivo .env
```bash
cp .env.example .env
php artisan key:generate
```
### Paso 4: Base de Datos y Migraciones
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cloud_con_ia
DB_USERNAME=root
DB_PASSWORD=

php artisan migrate
```
### Paso 5: Iniciar el Servidor de Desarrollo
```bash
php artisan serve
```
---

### 3. Políticas de Seguridad y Control de Versiones (GitOps)
* **Protección de Datos Sensibles (.gitignore):**
Queda estrictamente prohibido subir al repositorio el archivo .env, claves de API o contraseñas locales.
La carpeta vendor/ y archivos temporales están excluidos mediante el .gitignore configurado en la raíz.

* **Flujo de Trabajo en Git:**
La rama principal de integración y entrega es main.
Todo el equipo debe realizar un Git Pull antes de comenzar a trabajar diariamente para sincronizar los cambios de los compañeros.
Mensajes de commit obligatorios con formato claro.