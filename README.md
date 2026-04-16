<h1 align="center">Proyecto Aplicación Web PHP</h1>

Aplicación web desarrollada con **PHP** y **Bootstrap**, orientada a la gestión de conductores, propietarios, taxis y usuarios, con autenticación por sesión y panel administrativo.

Actualmente usa una arquitectura por capas (mini-MVC): **Controller -> Service -> Repository -> View**.

## Tecnologías

- PHP (PDO)
- MySQL (phpMyAdmin)
- Bootstrap
- jQuery + DataTables
- SweetAlert2

## Requisitos

- XAMPP (Apache + MySQL)
- PHP 7.4 o superior
- Base de datos MySQL

## Configuración

1. Clonar o copiar el proyecto en:
   `/opt/lampp/htdocs/Proyecto_AplicacionWeb_PHP`
2. Importar el archivo `proyecto.sql` en MySQL/phpMyAdmin.
3. Crear el archivo `.env` en la raíz del proyecto usando `.env.example` como base.
4. Configurar las credenciales de base de datos en `.env`.
5. Iniciar Apache y MySQL desde XAMPP.

## Variables de entorno

```env
DB_HOST=localhost
DB_DATABASE=proyecto
DB_USERNAME=root
DB_PASSWORD=
```

## Ejecución

Abrir en el navegador:

`http://localhost/Proyecto_AplicacionWeb_PHP/`

## Estructura principal

- `public/index.php`: front controller y punto de entrada del router.
- `public/css`, `public/js`, `public/img`: recursos estáticos.
- `routes/web.php`: definición de rutas.
- `app/Controllers/`: controladores HTTP.
- `app/Services/`: reglas de negocio y validaciones.
- `app/Repositories/`: acceso a datos (PDO).
- `app/Views/`: vistas de módulos y layout (`app/Views/layout`).
- `app/Core/`: núcleo compartido (router, auth, csrf, flash, db, view, autoload, env).

## Módulos migrados a rutas nuevas

- `/public/propietarios`
- `/public/taxis`
- `/public/conductores`
- `/public/usuarios` (solo administrador)

---

<p align="center">Desarrollado con PHP, Bootstrap y MySQL.</p>
