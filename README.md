# Autenticación basada en Tokens con PHP

Este proyecto implementa un servicio de autenticación basado en tokens utilizando PHP. El cliente se autentica enviando su usuario y contraseña al servidor, que devuelve un token de sesión. El token se utiliza en el encabezado de futuras solicitudes para autorizar al cliente.

## Descripción

- **Cliente (client.php)**: El cliente envía su nombre de usuario y contraseña al servidor, obtiene un token de sesión y lo utiliza en futuras solicitudes.
- **Servidor (server.php)**: El servidor valida las credenciales del usuario, genera un token único y lo devuelve al cliente. Además, el servidor verifica el token en solicitudes posteriores.
- **Almacenamiento de Tokens**: Los tokens generados se almacenan en un archivo JSON (`tokens.json`), que puede ser sustituido por una base de datos para mayor seguridad.

## Requisitos

- PHP 7.0 o superior
- Extensiones de PHP:
  - `json`
  - `openssl`
  - `cURL`
- Un servidor web compatible con PHP (como Apache o Nginx).

## Instalación

1. Clona este repositorio en tu servidor local o en el entorno de desarrollo.
   ```bash
   git clone https://github.com/tu-usuario/autenticacion-tokens-php.git
Navega hasta la carpeta del repositorio.

bash
Copiar
Editar
cd autenticacion-tokens-php
Asegúrate de que tu servidor PHP esté corriendo y que el archivo server.php esté accesible.

El archivo server.php maneja tanto la autenticación como la verificación de tokens. Debes configurarlo para que esté accesible desde el cliente.

Uso
1. Autenticación del Cliente
Abre el archivo client.php.

En el archivo client.php, edita las variables $usuario y $password con las credenciales de un usuario.

Ejecuta el archivo client.php desde tu navegador o línea de comandos para autenticarte y obtener el token.

bash
Copiar
Editar
php client.php
Si las credenciales son correctas, el servidor devolverá un token que puedes usar para autenticarte en futuras solicitudes.

2. Validación del Token en Solicitudes
El servidor valida el token que se pasa en el encabezado de las solicitudes:

Utiliza el token que se obtiene tras la autenticación.
El servidor verifica el token para autorizar la solicitud.
Para realizar una solicitud al servidor con el token, añade el token al encabezado de la solicitud HTTP.

bash
Copiar
Editar
curl -H "Authorization: TU_TOKEN_AQUI" http://localhost/server.php
3. Almacenamiento de Tokens
Los tokens generados se almacenan en un archivo JSON llamado tokens.json. Este archivo almacena los tokens junto con la información del usuario y su fecha de expiración. Puedes modificar el almacenamiento para usar una base de datos según sea necesario.

Seguridad
Las contraseñas se cifran utilizando password_hash y se verifican con password_verify.
Los tokens generados son aleatorios y tienen una expiración de 1 hora.
Contribuciones
Las contribuciones son bienvenidas. Si tienes alguna mejora o corrección de errores, por favor crea un pull request.

Licencia
Este proyecto está bajo la Licencia MIT - consulta el archivo LICENSE para más detalles.

Contacto
Si tienes alguna duda o pregunta, no dudes en ponerte en contacto.

r
Copiar
Editar
