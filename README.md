# Aplicación de Cuestionarios en PHP

## Descripción
Este proyecto es una aplicación de cuestionarios basada en PHP que permite a los usuarios realizar cuestionarios, recibir retroalimentación inmediata y almacenar los resultados en una base de datos. Incluye autenticación de usuarios, gestión de cuestionarios y manejo seguro de datos de usuario.

## Características
- Registro e inicio de sesión de usuarios.
- Creación y gestión de cuestionarios.
- Carga dinámica de preguntas desde la base de datos.
- Retroalimentación inmediata al completar un cuestionario.
- Almacenamiento seguro de contraseñas y gestión de sesiones.
- Protección contra inyecciones SQL y ataques XSS.

## Tecnologías Utilizadas
- PHP
- MySQL
- Docker
- Apache

## Instrucciones de Configuración

### Prerrequisitos
- Docker y Docker Compose instalados en tu máquina.

### Pasos
1. **Clonar el repositorio:**
   ```sh
   git clone https://github.com/IES-Rafael-Alberti/quizz-app-24-25-Llavesuke/tree/simple/app
   cd quiz-app
   ```

2. **Construir y ejecutar los contenedores Docker:**
   ```sh
   docker-compose up --build
   ```

3. **Acceder a la aplicación:**  
   Abre tu navegador web y navega a `http://localhost:8080`.

## Esquema de la Base de Datos
El esquema de la base de datos incluye las siguientes tablas:
- **Usuarios**: Almacena información de los usuarios.
- **Cuestionarios**: Almacena información de los cuestionarios.
- **Preguntas**: Almacena las preguntas de cada cuestionario.
- **Respuestas**: Almacena las respuestas de los usuarios.
- **Resultados**: Almacena los resultados de los cuestionarios.

## Medidas de Seguridad
- **Protección contra Inyecciones SQL**: Uso de sentencias preparadas con vinculación de parámetros.
- **Protección contra XSS**: Escapado de entradas de usuario con `htmlspecialchars`.
- **Seguridad de Contraseñas**: Contraseñas encriptadas usando `password_hash`.
- **Cookies Seguras**: Cookies configuradas con atributos `secure`, `httponly` y `samesite`.

## Estructura de Archivos
- `app/controllers`: Contiene los controladores para manejar las solicitudes.
- `app/models`: Contiene los modelos para interactuar con la base de datos.
- `app/views`: Contiene las plantillas de vistas.
- `config`: Contiene archivos de configuración.
- `public`: Contiene archivos accesibles públicamente.
- `sql`: Contiene el esquema de la base de datos.

## Uso
### Registro de Usuario
1. Navega a la página de registro.
2. Completa los detalles requeridos y envía el formulario.

### Inicio de Sesión de Usuario
1. Navega a la página de inicio de sesión.
2. Ingresa tus credenciales y envía el formulario.

### Creación de un Cuestionario
1. Inicia sesión como administrador.
2. Navega a la página de gestión de cuestionarios.
3. Haz clic en "Crear Nuevo Cuestionario" y completa los detalles.

### Realización de un Cuestionario
1. Inicia sesión como usuario.
2. Navega a la lista de cuestionarios disponibles.
3. Selecciona un cuestionario y responde las preguntas.

---
