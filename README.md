# AMACCS2024 - Sistema de Solicitud y Gestión de Consultas

Descripción
-----------
AMACCS2024 es una aplicación web desarrollada en PHP diseñada para permitir a estudiantes solicitar consultas con docentes, ver la disponibilidad de los profesores y gestionar detalles relacionados con las citas. El proyecto está pensado para funcionar en un entorno LAMP/WAMP/XAMPP y usa archivos de configuración locales y PHPMailer para el envío de correos.

Funcionalidades principales
---------------------------
- Autenticación básica de usuarios (login) a través de `login.php`.
- Visualización de disponibilidad de docentes en `views/verDisponibilidad.php`.
- Solicitud de consulta por parte de alumnos (`views/solicitarconsulta.php` y `controller/solicitarConsulta.php`).
- Vista para docentes con su disponibilidad y solicitudes (`views/vistaDocente.php`).
- Buscador/filtrado de alumnos y docentes (`controller/buscarAlumno.php`, filtros en la rama `filtrarDocentes`).
- Gestión y actualización de datos de docentes (`controller/actualizaDocente.php`).
- Sistema de detalles y configuración serializada en `config/detalles.json` y script para regenerar detalles (`scripts/regenerar_detalles.php`).
- Envío de correos mediante PHPMailer (incluido en `vendor/phpmailer`).

Requisitos
----------
- PHP 7.4+ (o versión compatible instalada en XAMPP/WAMP/LAMP).
- Servidor web local (XAMPP recomendado para desarrollo en Windows).
- MySQL / MariaDB con la base de datos creada (ver `DB.sql`).
- Extensiones PHP necesarias: mysqli, openssl (para PHPMailer), json.

Instalación (local con XAMPP)
-----------------------------
1. Coloca la carpeta del proyecto en `htdocs` (ejemplo: `C:\xampp\htdocs\daw\AMACCS2024--SOFT41A-GRUPO-03`).
2. Importa `DB.sql` en tu servidor MySQL para crear las tablas y datos iniciales.
3. Configura la conexión a la base de datos en `config/cn.php` (host, usuario, contraseña, nombre de BD).
4. Revisa y ajusta `config/email_config.php` con los datos SMTP si deseas activar el envío de correos.
5. Abre el proyecto en el navegador: `http://localhost/AMACCS2024--SOFT41A-GRUPO-03/`.

Archivos y rutas importantes
---------------------------
- `index.php` — Página de entrada / redirección.
- `login.php` — Formulario y lógica de inicio de sesión.
- `config/cn.php` — Configuración de conexión a la base de datos.
- `config/detalles.json` — Archivo JSON con detalles usados por la app.
- `controller/` — Controladores que reciben requests y procesan acciones.
- `model/` — Modelos para entidades como `alumno.php`, `docente.php`, `detalle.php`.
- `views/` — Vistas que generan la interfaz para usuarios y docentes.
- `scripts/regenerar_detalles.php` — Script utilitario para regenerar `detalles.json`.
- `vendor/phpmailer/` — Librería PHPMailer incluida para envío de correos.

Cómo usar (flujo típico)
------------------------
1. El estudiante inicia sesión en `login.php`.
2. Navega a `views/verDisponibilidad.php` para ver cuándo están disponibles los docentes.
3. Envía una solicitud de consulta mediante `views/solicitarconsulta.php`; el controlador `controller/solicitarConsulta.php` procesa la solicitud y notifica al docente (si está configurado el correo).
4. El docente ve las solicitudes en `views/vistaDocente.php` y puede aceptar o actualizar su disponibilidad con `controller/actualizaDocente.php`.

Pruebas rápidas
---------------
- Verifica la conexión a la base de datos desde `config/cn.php`.
- Prueba el envío de correo con `config/email_config.php` y una ruta de prueba que invoque PHPMailer.
- Carga `views/verDisponibilidad.php` para confirmar que la vista presenta datos de docentes.

Contribuir
----------
- Para cambios en features, crea una rama nueva basada en `filtrarDocentes` o `main` según convenga.
- Envía merge requests con descripciones claras y referencias a issues.
- Mantén la coherencia del estilo de código y agrega pruebas mínimas cuando modifiques lógica.

Licencia
--------
Revisa el archivo `LICENSE` incluido en el repositorio para detalles de la licencia del proyecto.

Contacto
--------
Para dudas o reportes, usa los issues del repositorio o contacta al equipo responsable del proyecto.

Notas finales
-------------
Este README ofrece una visión general para arrancar y entender las piezas principales del sistema. Si quieres, puedo agregar secciones específicas: ejemplos de configuración `config/cn.php`, instrucciones paso a paso para el envío de correo con PHPMailer, o un apartado de despliegue en servidor de producción.
