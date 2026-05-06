# PROMPTS.md — Gestión de Flota (Taxis)

> Plantillas de prompts para el proyecto. Úsalas como base — adapta los bloques
> `[Tarea]` y `[Contexto]` a lo que necesites en cada sesión.
> **Requisito:** Carga [CLAUDE.md](CLAUDE.md) al inicio de cada sesión (arquitectura, convenciones, stack).

---

## Cómo usar este archivo

Cada plantilla sigue la estructura de 5 ejes del prompt profesional:

| Eje                   | Pregunta          | Para qué sirve                                    |
| --------------------- | ----------------- | ------------------------------------------------- |
| **Rol**               | ¿Quién eres?      | Define el nivel y especialidad que asume la IA    |
| **Contexto**          | ¿Dónde estamos?   | El proyecto, stack y módulo activo                |
| **Tarea exacta**      | ¿Qué necesitas?   | Concreto y específico — nunca genérico            |
| **Restricciones**     | ¿Qué límites hay? | Convenciones del proyecto que NO se pueden romper |
| **Formato de salida** | ¿Cómo lo quieres? | Estructura del output esperado                    |

> **Regla de oro:** Cuanto más específico sea el bloque `[Tarea]`,
> menos correcciones necesitarás después.

**Reglas de uso:**

- **Carga el CLAUDE.md primero** — contiene arquitectura de capas, ciclo de vida de la request, convenciones y stack.
- **Un prompt por subtarea.** Pedir "el módulo completo" en un solo prompt produce resultados genéricos.
- **Si el output no encaja**, no corrijas manualmente primero — ajusta `[Restricciones]` y repite.
- **El spec antes que el código.** Define qué debe hacer antes de pedir que lo implemente.
- **Guarda los prompts que funcionen bien** en este archivo como nuevas plantillas.

---

## Plantilla base (copia esto y rellena)

> **Antes de usar:** Asegúrate de tener [CLAUDE.md](CLAUDE.md) cargado.

```
[Rol]
Actúa como desarrollador PHP Senior especializado en arquitectura por capas
y patrones de diseño (Repository, ViewModel, DI Container).

[Contexto]
Proyecto: Gestión de Flota — PHP 8.x custom, sin framework.
Stack: Bootstrap 5, Bootstrap Icons, DataTables, SweetAlert2, MySQL (PDO), JavaScript ES6+.
Arquitectura N-Layer: Presentation → Application → Domain → Infrastructure. Framework interno en core/.
Módulo activo: _______________

[Tarea]
_______________

[Restricciones]
- Seguir el patrón del módulo Taxi como referencia (TaxiController / TaxiService / TaxiRepository)
- Cada vista recibe un ViewModel tipado (extends ViewModel) — nunca arrays ni extract()
- View::renderWith(string $view, ViewModel $vm) es el único método de renderizado
- Métodos de controlador válidos: index(), create(), store(), edit(), update(), destroy()
- CSRF obligatorio en todo POST: Csrf::validateOrFail((string) $this->request->post('_token', ''))
- Rutas admin-only: Auth::requireAdmin() al inicio de cada método
- Redirecciones: Response::redirect(app_url('/ruta'))
- Respuestas AJAX: Response::json(['success' => bool, 'message' => '...'])
- Flash messages: Flash::set('success'|'error', 'mensaje') — solo esas dos claves
- Toasts frontend: showToastSuccess() / showToastError() de toast-config.js — nunca Swal.fire() directo
- SQL: siempre bindValue() con PDO — nunca concatenar variables
- Nuevos repositorios deben implementar una interface en app/Infrastructure/Contracts/
- Registrar bindings en config/bindings.php

[Formato de salida]
_______________
```

---

## Plantilla 1 — Generar código nuevo (feature)

Usar cuando: implementar un módulo o funcionalidad nueva.

```
[Rol]
Actúa como desarrollador PHP Senior especializado en arquitectura por capas
y patrones de diseño (Repository, ViewModel, DI Container).

[Contexto]
Proyecto: Gestión de Flota — PHP 8.x custom, sin framework.
Stack: Bootstrap 5, Bootstrap Icons, DataTables, SweetAlert2, MySQL (PDO), JavaScript ES6+.
Arquitectura N-Layer: Presentation → Application → Domain → Infrastructure. Framework interno en core/.
Módulo activo: [nombre del módulo — ej: conductores, taxis, propietarios]

Archivos relevantes del módulo:
- app/Presentation/Controllers/[Módulo]Controller.php
- app/Application/Services/[Módulo]Service.php
- app/Infrastructure/Persistence/Repositories/[Módulo]Repository.php
- app/Infrastructure/Contracts/[Módulo]RepositoryInterface.php
- app/Domain/Models/[Módulo].php
- app/Presentation/ViewModels/[Módulo]*ViewModel.php
- app/Presentation/Views/[modulo]/[vista].php

[Tarea]
Implementar [nombre exacto del requerimiento].

Descripción: [describe qué debe hacer]

[Restricciones]
- Usar el módulo Taxi como referencia exacta de patrón
- Crear ViewModel por cada vista (index, create, edit) extendiendo App\Presentation\ViewModels\ViewModel
- View::renderWith(string $view, ViewModel $vm) — única forma de renderizar
- CSRF en todo POST: Csrf::validateOrFail((string) $this->request->post('_token', ''))
- Auth::requireAdmin() en métodos que lo requieran
- Validaciones de campos propias del modelo en Model::create() lanzando InvalidArgumentException
- Validaciones cross-domain (FK, unicidad) en el Service, antes de llamar al factory del modelo
- Repositorio implementa interface en app/Infrastructure/Contracts/
- Binding registrado en config/bindings.php
- Eliminación: detectar $this->request->isAjax() → Response::json() o Flash + Response::redirect()
- Toasts frontend: showToastSuccess() / showToastError() — no Swal.fire() directo
- SQL: siempre bindValue() con tipos explícitos (PDO::PARAM_INT, etc.)
- Los repositorios no hacen JOINs entre dominios — eso va en el Service
- declare(strict_types=1) en todos los archivos PHP

[Formato de salida]
Devuelve en este orden:
1. Lista de archivos que se crean o modifican
2. Código de cada archivo
3. Queries SQL si hay cambios en BD (CREATE TABLE, ALTER, FKs)
4. Líneas a agregar en routes/web.php
5. Binding a agregar en config/bindings.php
6. Checklist de testing manual (flujo exitoso + edge cases + AJAX delete)
```

---

## Plantilla 2 — Debuggear un error

Usar cuando: algo no funciona y no está claro por qué.

```
[Rol]
Actúa como desarrollador PHP Senior especializado en debugging
de aplicaciones PHP custom con PDO y arquitectura por capas.

[Contexto]
Proyecto: Gestión de Flota — PHP 8.x, MySQL, PDO.
Archivo donde ocurre el error: [ruta completa]
Método/función afectada: [nombre]

[Tarea]
Tengo este error:
[pega el mensaje de error exacto o el comportamiento inesperado]

Código actual:
[pega el bloque de código relevante — no todo el archivo]

Lo que debería hacer:
[describe el comportamiento esperado]

Lo que intenté que no funciona:
[describe lo que ya probaste]

[Restricciones]
- No cambiar la arquitectura del archivo — solo corregir el problema específico
- Mantener las convenciones de naming y capas del proyecto
- Si el fix requiere cambiar más de un archivo, indicarlo antes de proponer código

[Formato de salida]
1. Diagnóstico: causa raíz del error en 2-3 líneas
2. Fix: código corregido con comentario solo si el cambio no es obvio
3. Por qué pasó: explicación breve para no repetirlo
```

---

## Plantilla 3 — Code review antes del merge

Usar cuando: antes de hacer merge de una rama, o cuando el código funciona
pero algo "huele mal".

```
[Rol]
Actúa como Tech Lead PHP con experiencia en code review de sistemas por capas,
seguridad web y patrones de diseño.

[Contexto]
Proyecto: Gestión de Flota — PHP 8.x custom, arquitectura N-Layer.
Rama revisada: feature/[nombre]
Requerimiento implementado: [nombre del requerimiento]

[Tarea]
Revisa el siguiente código antes del merge.

[pega el código o el diff]

[Restricciones]
Evalúa específicamente:
- Seguridad: SQL injection (bindValue), XSS (htmlspecialchars en vistas), CSRF en POST, acceso sin Auth::requireAdmin()
- Capas: SQL fuera del Repository, lógica de negocio fuera del Service/Model, lógica de presentación fuera del Controller/ViewModel
- Dominio: validaciones de campos propios en Model::create(); validaciones cross-domain (FK) en Service
- JOINs cross-domain: los repositorios no deben cruzar tablas de otro dominio — combinar en el Service
- ViewModels: vistas reciben ViewModel tipado, no arrays ni variables sueltas
- Flash/Toasts: Flash::set() con clave 'success' o 'error'; frontend usa showToastSuccess/showToastError
- AJAX delete: $request->isAjax() verificado, Response::json() con estructura {success, message}
- Contratos: repositorio implementa interface en app/Infrastructure/Contracts/, binding en config/bindings.php
- Convenciones: declare(strict_types=1), tipos explícitos en bindValue, app_url() para URLs internas

[Formato de salida]
OK  - Lo que está bien (al menos 2 puntos)
OBS - Observaciones (mejoras no críticas, con sugerencia)
FIX - Problemas a corregir antes del merge (con código corregido)
```

---

## Plantilla 4 — Consulta de arquitectura

Usar cuando: hay una decisión técnica importante antes de implementar,
o cuando no está claro cómo integrar algo nuevo.

```
[Rol]
Actúa como arquitecto de software PHP con experiencia en arquitectura por capas,
patrones Repository/ViewModel y PHP sin framework.

[Contexto]
Proyecto: Gestión de Flota — PHP 8.x custom, sin framework.
Estado actual: módulos implementados — taxis, propietarios, conductores, usuarios, auth.
BD: propietarios, taxis, conductores, usuarios (ver database/schema.sql).
Arquitectura N-Layer: Presentation / Application / Domain / Infrastructure. Framework interno en core/.
Bootstrap: bootstrap/app.php. Bindings: config/bindings.php. Rutas: routes/web.php.

[Tarea]
Necesito decidir: [describe la decisión técnica]

Opciones que estoy considerando:
- Opción A: [describe]
- Opción B: [describe]

[Restricciones]
- No introducir frameworks (ni Laravel, ni Symfony)
- Mantener compatibilidad con el Container, Router y Autoloader actuales
- No introducir librerías JS nuevas sin justificación fuerte
- La solución debe integrarse en la estructura de capas existente
- Considerar impacto en config/bindings.php y routes/web.php si la decisión afecta el bootstrap

[Formato de salida]
1. Recomendación directa (cuál opción y por qué en 3 líneas)
2. Trade-offs de cada opción (tabla si aplica)
3. Impacto en el resto del sistema
4. Primeros pasos concretos para implementar la opción recomendada
```

---

## Ejemplo real — Nuevo módulo completo (Conductores)

> Ejemplo de cómo se ve un prompt de feature bien estructurado para este proyecto.
> El módulo de Taxis ya está implementado — úsalo como referencia de calidad.

```
[Rol]
Actúa como desarrollador PHP Senior especializado en arquitectura por capas
y patrones Repository/ViewModel con PHP puro.

[Contexto]
Proyecto: Gestión de Flota — PHP 8.x custom, sin framework.
Stack: Bootstrap 5, DataTables, SweetAlert2, MySQL (PDO), JavaScript ES6+.
Arquitectura N-Layer: Presentation → Application → Domain → Infrastructure. Framework en core/.
Módulo: conductores (nuevo módulo).

BD relevante:
- conductores (ID, Nombres, Telefono, Placa)  ← FK a taxis(Placa)
- taxis (Placa, Modelo, Marca, Idpropietario)

Módulo de referencia: taxis (TaxiController / TaxiService / TaxiRepository).

[Tarea]
Implementar CRUD completo de conductores.

Criterios de aceptación:
- Listado con DataTables (datos desde PHP, sin AJAX en carga)
- Crear conductor: Nombres, Telefono, Placa (select con taxis disponibles)
- Editar conductor
- Eliminar conductor via AJAX (Fetch + SweetAlert confirm + showToastSuccess/Error)
- Solo Admin puede acceder (Auth::requireAdmin())

[Restricciones]
- Seguir TaxiController/TaxiService/TaxiRepository como referencia exacta
- Un ViewModel por vista: ConductorIndexViewModel, ConductorCreateViewModel, ConductorEditViewModel
- Conductor::create() valida: Nombres no vacío (max 255), Telefono 7-10 dígitos
- ConductorService valida existencia de Placa en BD antes de llamar Conductor::create()
- Repositorio implementa ConductorRepositoryInterface en app/Infrastructure/Contracts/
- Binding en config/bindings.php
- CSRF en todos los POST
- destroy() detecta isAjax() y devuelve Response::json()
- Flash::set('success'|'error') para redirecciones, toasts para AJAX
- SQL con bindValue() y tipos explícitos

[Formato de salida]
Devuelve en este orden:
1. Lista de archivos a crear/modificar
2. app/Infrastructure/Contracts/ConductorRepositoryInterface.php
3. app/Domain/Models/Conductor.php
4. app/Infrastructure/Persistence/Repositories/ConductorRepository.php
5. app/Application/Services/ConductorService.php
6. app/Presentation/ViewModels/Conductor*ViewModel.php (los tres)
7. app/Presentation/Controllers/ConductorController.php
8. app/Presentation/Views/conductores/ (index.php, create.php, edit.php)
9. Líneas para routes/web.php
10. Líneas para config/bindings.php
11. Checklist de testing manual
```

---

_Última actualización: 2026-05-06_
_Mantener sincronizado con CLAUDE.md al inicio de cada sesión._
