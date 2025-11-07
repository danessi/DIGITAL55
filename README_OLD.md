
# Backend - Laravel API (Dockerizado)

Este proyecto proporciona una API RESTful construida con **Laravel**, lista para ser ejecutada en contenedores Docker.

## Requisitos 

- Docker y Docker Compose instalados en tu máquina.

## Instrucciones de instalación

1. **Clona el repositorio:**

   ```bash
   git clone https://github.com/danessi/DIGITAL55.git
   ```

2. **Accede al directorio del proyecto:**

   ```bash
   cd DIGITAL55
   ```
3. **Crear el .env a partir de .env.example:**

   copiar el contenido de .env.example en un nuevo archivo .env

4. **Asegurarse de tener instalado Composer en esa carpeta y luego Levanta los contenedores con Docker Compose:**

   Este comando construirá y levantará los contenedores en segundo plano:

   ```bash
   docker compose up --build -d 
   ```

5. **Accede al contenedor del backend (Laravel):**

   Una vez que los contenedores estén en ejecución, accede al contenedor de Laravel para realizar los ajustes necesarios:

   ```bash
   docker exec -it <nombre-del-contenedor-backend> bash
   ```

   Asegúrate de reemplazar `<nombre-del-contenedor-backend>` por el nombre real de tu contenedor (ejemplo: `0003-DIGITAL55-backend`).

6. **Realiza las optimizaciones necesarias para que la aplicación funcione correctamente:**

   Ejecuta estos comandos dentro del contenedor de Laravel para optimizar la configuración y las rutas:

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan optimize
   ```

7. **Realiza las migraciones de la base de datos y luego el seeder:**

   Asegúrate de que la base de datos esté actualizada con las últimas migraciones ejecutando:

   ```bash
   php artisan migrate
   ```

   ```bash
   php artisan db:seed --class=XxxxxxxSeeder
   ```

## Accesos Url

Acceso a la API: Insomnia_DIGITAL55_2025.yaml
(ajunto un zip en el emai)