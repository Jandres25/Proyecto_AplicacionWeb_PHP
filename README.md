<h1 align="center">Proyecto Aplicación Web PHP</h1>

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
- **Manejo de Errores HTTP:** Vistas dedicadas para 404, 500 y errores genéricos.

## 🛠 Tecnologías

- **PHP 8.x** (PDO)
- **MySQL**
- **Bootstrap 5 & Icons** (Diseño responsivo)
- **JavaScript (ES6+)**
- **DataTables** (Gestión de tablas dinámicas)
- **SweetAlert2** (Notificaciones y confirmaciones)

## 📋 Requisitos

- XAMPP (Apache + MySQL)
- PHP 7.4 o superior
- Base de datos MySQL

## ⚙️ Configuración

1. Clonar o copiar el proyecto en:
   `/opt/lampp/htdocs/Proyecto_AplicacionWeb_PHP`
2. Importar el archivo `proyecto.sql` en MySQL/phpMyAdmin (si está disponible en la raíz o carpeta `database/`).
3. Crear el archivo `.env` en la raíz del proyecto usando `.env.example` como base.
4. Configurar las credenciales de base de datos en `.env`.
5. Iniciar Apache y MySQL desde XAMPP.

## 🔑 Variables de entorno

```env
DB_HOST=localhost
DB_DATABASE=proyecto
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://localhost/Proyecto_AplicacionWeb_PHP
```

## 🚀 Ejecución

Abrir en el navegador:
`http://localhost/Proyecto_AplicacionWeb_PHP/`

## 🌐 Nota sobre `APP_URL` y `.htaccess`

- Este proyecto está configurado para **mantener** el `.htaccess` en la raíz.
- Con esta configuración, `APP_URL` debe quedar **sin** `/public`:
  `APP_URL=http://localhost/Proyecto_AplicacionWeb_PHP`
- El `.htaccess` de la raíz redirige internamente hacia `public/` para conservar URLs limpias.

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
