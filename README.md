<h1 align="center">Proyecto Aplicación Web PHP</h1>

Aplicación web desarrollada con **PHP** y **Bootstrap**, orientada a la gestión de conductores, propietarios, taxis y usuarios, con autenticación por sesión y panel administrativo.

Actualmente usa una arquitectura por capas (mini-MVC): **Controller -> Service -> Repository -> View**.

## ✨ Características Recientes

- **Diseño Moderno:** Interfaz de usuario renovada en todos los módulos (Taxis, Propietarios, Conductores y Usuarios) basada en un patrón visual limpio y profesional.
- **Gestión AJAX:** Eliminación de registros optimizada mediante peticiones asíncronas (Fetch API) y SweetAlert2.
- **Seguridad Mejorada:** Validación de CSRF en todas las acciones sensibles y control de acceso basado en roles (Administrador).
- **Arquitectura Modular:** Lógica de frontend desacoplada en módulos ES6 (`public/modules/`).
- **Manejo de Errores HTTP:** Vistas dedicadas para errores comunes (404, 500 y genéricas para 403/405/419).

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

- `public/index.php`: Front Controller y punto de entrada.
- `public/modules/`: Módulos JavaScript para lógica de frontend (eliminaciones AJAX).
- `public/css`, `public/js`, `public/img`: Recursos estáticos (estilos, scripts base e imágenes).
- `routes/web.php`: Definición de rutas del sistema.
- `app/Controllers/`: Manejo de peticiones HTTP y lógica de control.
- `app/Services/`: Reglas de negocio, validaciones y orquestación.
- `app/Repositories/`: Abstracción de acceso a datos (PDO).
- `app/Views/`: Plantillas de vista organizadas por módulos.
- `app/Core/`: Núcleo del sistema (Router, Auth, Csrf, Flash, Database, View, Autoloader, Env).

## 🛣️ Módulos y Rutas

- `/taxis`: Gestión integral de la flota de vehículos (Solo Admin).
- `/propietarios`: Administración de dueños de vehículos.
- `/conductores`: Gestión de personal de conducción y asignación de vehículos.
- `/usuarios`: Control de usuarios del sistema (Solo Admin).
- `/login`: Autenticación de usuarios.

---

<p align="center">Desarrollado con PHP, Bootstrap y MySQL.</p>
