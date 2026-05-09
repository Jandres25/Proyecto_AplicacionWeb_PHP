<h1 align="center">Proyecto Aplicación Web PHP</h1>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/Composer-dependencias-885630?style=flat&logo=composer&logoColor=white" alt="Composer">
  <img src="https://img.shields.io/badge/XAMPP-Apache%20%2B%20MySQL-FB7A24?style=flat&logo=xampp&logoColor=white" alt="XAMPP">
  <img src="https://img.shields.io/badge/arquitectura-N--Layer-0078D4?style=flat" alt="N-Layer">
  <img src="https://img.shields.io/badge/tests-PHPUnit%2010-366488?style=flat&logo=php&logoColor=white" alt="PHPUnit">
  <a href="https://github.com/Jandres25/Proyecto_AplicacionWeb_PHP/actions/workflows/tests.yml">
    <img src="https://github.com/Jandres25/Proyecto_AplicacionWeb_PHP/actions/workflows/tests.yml/badge.svg" alt="Tests">
  </a>
</p>

Aplicación web desarrollada con **PHP** y **Bootstrap**, orientada a la gestión de conductores, propietarios, taxis y usuarios, con autenticación por sesión y panel administrativo.

Arquitectura por capas clásica (N-Layer): **Presentation → Application → Domain → Infrastructure**, con framework interno propio en `core/`.

## ✨ Características

- **Arquitectura N-Layer:** Capas Presentation, Application, Domain e Infrastructure claramente separadas. Framework interno en `core/` desacoplado de la lógica de negocio.
- **Modelos de Dominio Ricos:** Cada modelo expone `Model::create()` con validaciones propias. Los Services orquestan reglas cross-domain (existencia de FK) y delegan al modelo para el resto.
- **Sin JOINs Cross-Domain:** Los repositorios consultan una sola tabla. El ensamblado de datos entre dominios ocurre en el Service (e.g. `TaxiService::allWithOwner()`).
- **Renderizado Tipado de Vistas:** `View::renderWith()` recibe un `ViewModel` tipado; no se usa `extract()` ni arrays crudos.
- **Layout Desacoplado:** El layout consume un `LayoutViewModel` preparado en `core/View`, sin lógica de aplicación en la plantilla.
- **Gestión AJAX:** Eliminación de registros mediante Fetch API con feedback visual vía SweetAlert2.
- **Toasts Centralizados:** Helpers reutilizables en `public/js/toast-config.js` (`showToast`, `showToastSuccess`, `showToastError`).
- **Seguridad:** CSRF en todas las acciones POST y control de acceso basado en roles (Administrador).
- **Tests automatizados:** Suite de unit tests con PHPUnit 10 (modelos de dominio y services). CI via GitHub Actions en PHP 8.2 y 8.3. Correr con `composer test`.
- **Recuérdame:** Cookie persistente de 30 días con token hasheado (SHA-256) y rotación en cada uso. Configurable via variables de entorno.
- **Gestión de sesión:** Duración de sesión configurable via `SESSION_LIFETIME` (minutos).
- **Manejo de Errores HTTP:** Vistas dedicadas para 404, 500 y errores genéricos.

## 🛠 Tecnologías

- **PHP 8.x** (PDO)
- **MySQL**
- **Bootstrap 5 & Icons** (Diseño responsivo)
- **JavaScript (ES6+)**
- **DataTables** (Gestión de tablas dinámicas)
- **SweetAlert2** (Notificaciones y confirmaciones)
- **PHPUnit 10** (Tests unitarios)

## 📋 Requisitos

- XAMPP (Apache + MySQL)
- PHP 8.1 o superior
- Base de datos MySQL
- [Composer](https://getcomposer.org/) (gestor de dependencias PHP)

## ⚙️ Configuración

1. Clonar o copiar el proyecto en:
   `/opt/lampp/htdocs/Proyecto_AplicacionWeb_PHP`
2. Instalar dependencias PHP:
   ```bash
   # Producción
   composer install --no-dev --optimize-autoloader
   # Desarrollo (incluye PHPUnit)
   composer install
   ```
3. Importar la base de datos en MySQL/phpMyAdmin: primero `database/schema.sql`, luego `database/seeder.sql`.
4. Crear el archivo `.env` en la raíz del proyecto usando `.env.example` como base.
5. Configurar las credenciales de base de datos en `.env`.
6. Dar permisos de escritura al directorio de logs:
   - **Linux** (XAMPP): `chown -R daemon:daemon storage/logs`
   - **macOS** (XAMPP): `chmod -R 777 storage/logs`
   - **Windows**: no se requiere acción — XAMPP ya tiene acceso de escritura por defecto.
7. Iniciar Apache y MySQL desde XAMPP.

## 🔑 Variables de entorno

```env
# Base de datos
DB_HOST=localhost
DB_DATABASE=proyecto
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://localhost/Proyecto_AplicacionWeb_PHP/public

# Sesión
SESSION_LIFETIME=120        # duración en minutos (default: 120)

# Recuérdame
REMEMBER_ME_ENABLED=true
REMEMBER_ME_TTL_DAYS=30     # duración de la cookie en días (default: 30)
REMEMBER_ME_COOKIE_NAME=remember_token
```

## 🧪 Tests

```bash
composer test
```

Los tests son unit tests puros (sin BD ni Apache). GitHub Actions los ejecuta automáticamente en cada push y PR a `master`.

## 🚀 Ejecución

Abrir en el navegador:
`http://localhost/Proyecto_AplicacionWeb_PHP/public/`

## 🌐 Nota sobre `APP_URL` y `.htaccess`

- El único `.htaccess` activo está en `public/` — enruta todas las peticiones a `index.php`.
- `APP_URL` debe incluir `/public` al final: `APP_URL=http://localhost/Proyecto_AplicacionWeb_PHP/public`
- No existe `.htaccess` en la raíz del proyecto.

## 📁 Estructura del Proyecto

```
app/
  Presentation/
    Controllers/       ← Controladores HTTP
    Http/              ← Request, Response
    ViewModels/        ← ViewModels tipados (uno por vista)
    Views/             ← Plantillas PHP
  Application/
    Contracts/         ← Interfaces de servicios
    Services/          ← Servicios de aplicación (orquestación)
  Domain/
    Models/            ← Modelos de dominio con Model::create() y validaciones
  Infrastructure/
    Contracts/         ← Interfaces de repositorios
    Persistence/
      Repositories/    ← Implementaciones PDO
      Database.php     ← Singleton PDO
core/                  ← Framework interno (Router, Container, Auth, Csrf, Flash, View…)
config/
  bindings.php         ← Registro de dependencias (DI container)
bootstrap/
  app.php              ← Bootstrap: autoloader, container, router
routes/
  web.php              ← Definición de rutas
public/
  index.php            ← Front controller
  css/, js/, img/      ← Recursos estáticos
  modules/             ← Módulos ES6 (lógica AJAX)
database/
  schema.sql
  seeder.sql
```

## 🛣️ Módulos y Rutas

- `/taxis`: Gestión integral de la flota de vehículos (Solo Admin).
- `/propietarios`: Administración de dueños de vehículos.
- `/conductores`: Gestión de personal de conducción y asignación de vehículos.
- `/usuarios`: Control de usuarios del sistema (Solo Admin).
- `/login`: Autenticación de usuarios.

---

<p align="center">Desarrollado con PHP, Bootstrap y MySQL.</p>
