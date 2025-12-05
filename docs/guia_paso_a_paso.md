# Guía paso a paso para implementar el proyecto Royal Pets

Esta guía está pensada para que el cliente pueda desplegar el proyecto Royal Pets.

---

## Paso 1. Preparar la base de datos en PostgreSQL

### 1. Crear la base de datos, por ejemplo:

```
CREATE DATABASE veterinaria_royal_pets;
CREATE EXTENSION IF NOT EXISTS pgcrypto;
```

### 2. Crear la tabla de usuarios y un usuario de prueba:

```
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(50) DEFAULT 'admin',
    fecha_creacion TIMESTAMP DEFAULT NOW()
);

INSERT INTO usuarios (nombre, email, contrasena, rol, fecha_creacion) 
VALUES (
    'Admin Royal Pets',
    'admin@royalpets.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'admin',
    NOW()
);
```

### 3. Crear la tabla de servicios:

```
CREATE TABLE servicios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    duracion_minutos INT,
    fecha_creacion TIMESTAMP DEFAULT NOW()
);
```

### 4. Crear la tabla de citas:

```
CREATE TABLE citas (
    id SERIAL PRIMARY KEY,
    nombre_cliente VARCHAR(100) NOT NULL,
    email_cliente VARCHAR(100),
    telefono_cliente VARCHAR(20),
    nombre_mascota VARCHAR(100) NOT NULL,
    tipo_mascota VARCHAR(50),
    servicio_id INT REFERENCES servicios(id),
    fecha_cita TIMESTAMP NOT NULL,
    estado VARCHAR(20) DEFAULT 'pendiente',
    notas TEXT,
    usuario_id INT REFERENCES usuarios(id),
    fecha_creacion TIMESTAMP DEFAULT NOW()
);
```

### 5. Insertar servicios de ejemplo:

```
INSERT INTO servicios (nombre, descripcion, precio, duracion_minutos) VALUES
('Consulta General', 'Revisión veterinaria completa', 50000, 30),
('Baño y Corte', 'Estética profesional para mascotas', 25000, 60),
('Vacunación', 'Aplicación de vacunas según esquema', 35000, 15),
('Desparasitación', 'Tratamiento interno y externo', 20000, 20),
('Cirugía Menor', 'Procedimientos quirúrgicos básicos', 150000, 120);
```

---

## Paso 2. Configurar el backend (PHP + PDO)

### 1. Configurar `backend/config/database.php` con las credenciales reales de PostgreSQL.

### 2. Recordar:

- Manejo de excepciones con `try/catch`
- Por qué usar `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`

### 3. Revisar los modelos:

- `Cita.php` para el CRUD de citas
- `Servicio.php` para el CRUD de servicios

---

## Paso 3. Manejo de sesiones

### 1. Revisar `backend/core/session.php`:

- `session_start()`
- Funciones `require_login_page()` y `require_login_api()`

### 2. Revisar el flujo:

- El login guarda datos en `$_SESSION`
- Las APIs usan `require_login_api()` para protegerse
- Las páginas internas usan `require_login_page()` para redirigir al login

---

## Paso 4. Implementar la autenticación (login/logout)

### 1. Ver `backend/controllers/AuthController.php`:

- Validación de usuario y contraseña
- Uso de `password_verify()` para verificar el hash

### 2. Ver `backend/api/auth.php`:

- Endpoint `action=login` para procesar el formulario vía AJAX
- Endpoint `action=logout` para cerrar sesión

### 3. Revisar `frontend/pages/login.php`:

- Formulario de login con Bootstrap
- Envío del formulario con jQuery AJAX

---

## Paso 5. Implementar el módulo de citas (CRUD)

### 1. Revisar `backend/models/Cita.php`:

- Métodos `all()`, `find()`, `create()`, `update()`, `delete()`

### 2. Revisar `backend/controllers/CitaController.php`:

- Cómo encapsula la lógica de validación

### 3. Revisar `backend/api/citas.php`:

- Acciones `list`, `get`, `create`, `update`, `delete`

### 4. Revisar `frontend/pages/dashboard.php`:

- Tabla inicializada con DataTables y fuente AJAX
- Modales de Bootstrap para crear/editar
- Botones de editar y eliminar que disparan peticiones AJAX

---

## Paso 6. Implementar el módulo de servicios (CRUD)

### 1. Revisar `backend/models/Servicio.php`:

- Métodos `all()`, `find()`, `create()`, `update()`, `delete()`

### 2. Revisar `backend/controllers/ServicioController.php`:

- Cómo encapsula la lógica de validación

### 3. Revisar `backend/api/servicios.php`:

- Acciones `list`, `get`, `create`, `update`, `delete`

### 4. Revisar `frontend/pages/dashboard.php`:

- Tabla de servicios con DataTables
- Modal para crear/editar servicios
- Botones de acción con AJAX

---

## Paso 7. Separación frontend/backend y estadísticas

### 1. Explorar la estructura de carpetas:

- `frontend/pages`
- `frontend/assets` (css, js)
- `backend/api`, `backend/models`, etc.

### 2. Revisar `backend/api/estadisticas.php`:

- Endpoint que retorna contadores de citas por estado
- Usado en el dashboard para actualizar tarjetas en tiempo real




