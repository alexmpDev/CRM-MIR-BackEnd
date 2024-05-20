# CRM-MIR-BackEnd

## Descripción
El proyecto CRM-MIR-BackEnd es la parte trasera de una aplicación de CRM MIR, desarrollada con el framework Laravel. Proporciona una API RESTful para la gestión de clientes, ventas, productos y usuarios.

## Instalación

1. Clona el repositorio:

    ```bash
    git clone https://github.com/alexmpDev/CRM-MIR-BackEnd.git
    ```

2. Instala las dependencias:

    ```bash
    composer install
    ```

3. Configura las variables de entorno:

    - Crea un archivo `.env` en el directorio raíz del proyecto.
    - Copia el contenido del archivo `.env.example` y pégalo en el archivo `.env`.
    - Define las siguientes variables de entorno en el archivo `.env`:

        ```plaintext
        APP_NAME=MIR
        APP_ENV=local
        APP_KEY=
        APP_DEBUG=true
        ...
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=nombre_de_tu_base_de_datos
        DB_USERNAME=tu_usuario_de_mysql
        DB_PASSWORD=tu_contraseña_de_mysql
        MAIL_MAILER=smtp
        MAIL_HOST=smtp.example.com
        MAIL_PORT=587
        MAIL_USERNAME=tu_correo@example.com
        MAIL_PASSWORD=tu_contraseña_secreta
        MAIL_ENCRYPTION=tls
        MAIL_FROM_ADDRESS=tu_correo@example.com
        MAIL_FROM_NAME="${APP_NAME}"
        ```

4. Genera una nueva clave de aplicación:

    ```bash
    php artisan key:generate
    ```

5. Ejecuta las migraciones para crear las tablas de la base de datos:

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

## Uso

- Inicia el servidor:

    ```bash
    php artisan serve
    ```

- Accede a la API en http://localhost:8000.

- Para acceder al backoffice, asegúrate de estar logeado con una cuenta de administrador y visita la URL `/admin`.
  
## Hay usuarios de prueba de todos los roles 

- Sus credenciales son:
- {rol} hace referencia al rel que quieras probar (admin,profesor,bibliotecta,secretaria , etc..)
  rol@example.com
  rolpassword
  
## Nota sobre el correo electrónico

Para que la funcionalidad de correo electrónico funcione correctamente, asegúrate de haber configurado las variables de entorno relacionadas con el correo electrónico en el archivo `.env` como se muestra arriba. Estas variables incluyen el servidor SMTP, las credenciales de inicio de sesión y la configuración de cifrado.
