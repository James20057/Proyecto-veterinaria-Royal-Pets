

# Proyecto: Sistema de Gestión Veterinaria Royal Pets

## Contexto

La Veterinaria **Royal Pets** necesita un sistema web para gestionar citas, servicios y clientes. El sistema será utilizado por el área administrativa para:

- Registrar y gestionar citas de clientes
- Administrar servicios veterinarios
- Consultar listados filtrables y ordenables
- Gestionar estados de citas (pendiente, confirmada, completada, cancelada)
- Visualizar estadísticas en tiempo real

El sistema está desarrollado como una aplicación web usando:

- **Frontend:** HTML, CSS, Bootstrap, jQuery, DataTables, Font Awesome
- **Backend:** PHP con sesiones, organizado en arquitectura MVC
- **Base de datos:** PostgreSQL
- **Comunicación:** AJAX (sin recargar la página en las operaciones CRUD)

## Requerimientos funcionales

### 1. Autenticación

- Debe existir una pantalla de login.
- Solo usuarios autenticados pueden acceder al módulo de gestión.
- Las credenciales se validan contra la tabla `usuarios` en PostgreSQL.
- Debe utilizarse manejo de sesión en PHP.


### 2. Módulo de citas

**Listado de citas en una tabla interactiva:**
- Búsqueda
- Ordenamiento
- Paginación

La tabla debe implementarse con **DataTables**.

**Debe permitir:**
- Crear una nueva cita
- Editar una cita existente
- Eliminar una cita
- Cambiar el estado de la cita (pendiente, confirmada, completada, cancelada)

Todas las operaciones se deben realizar vía **AJAX**, consumiendo APIs en PHP.


### 3. Módulo de servicios

**Listado de servicios en una tabla interactiva:**
- Búsqueda
- Ordenamiento
- Paginación

**Debe permitir:**
- Crear un nuevo servicio
- Editar un servicio existente
- Eliminar un servicio

Las operaciones CRUD se realizan mediante AJAX.

### 4. Estadísticas en tiempo real

El dashboard debe mostrar tarjetas con:
- Total de citas pendientes
- Total de citas confirmadas
- Total de citas completadas
- Total de citas canceladas

Las estadísticas se actualizan automáticamente después de cada operación.

### 5. Múltiples páginas

Debe existir al menos:
- `index.php` - página pública informativa
- `login.php` - pantalla de autenticación
- `dashboard.php` - página principal de gestión

---

## Requerimientos no funcionales

- **Código organizado y comentado** con formato estándar.
- **Nombres de carpetas y archivos coherentes.**
- **Manejo básico de errores** (mostrar mensajes si falla el login o el CRUD).
- **Validaciones mínimas** en los formularios (ej. nombre obligatorio).
- **Variables CSS** para mantener consistencia en colores y estilos.








