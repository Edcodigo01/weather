
# Aplicación Clima en Laravel 11 y Vue 3

Aplicación que muestra una lista de usuarios junto con el clima actual basado en su ubicación geográfica.

## REQUERIMIENTOS

- Docker y Docker Compose

## DESPLIEGUE

**Nota Importante:** El rendimiento de la aplicación puede verse afectado si se ejecuta en Docker en un entorno Windows, en comparación con su ejecución localmente. Esto se debe a que Docker no funciona de manera óptima en Windows, lo que puede hacer que la aplicación se sienta más lenta. Por esta razón, se presentan dos métodos de despliegue distintos.

### Despliegue total con Docker

Este método permite usar Laravel Horizon y actualizaciones automáticas.

1. Clonar el repositorio, ingresar al directorio descargado y crear el archivo `.env` a partir del archivo `.env.example.docker`.
    ```bash
    git clone https://github.com/Edcodigo01/weather.git
    cd weather
    cp .env.example.docker .env
    ```

2. Levantar los contenedores.
    ```bash
    docker-compose up -d
    ```

3. Instalar dependencias.
    ```bash
    docker-compose exec app composer install
    ```

4. Agregar Laravel Horizon.
    ```bash
    docker-compose exec app composer require laravel/horizon
    ```

5. Instalar Laravel Horizon.
    ```bash
    docker-compose exec app php artisan horizon:install
    ```

6. Reiniciar el contenedor de Laravel después de instalar Horizon.
    ```bash
    docker restart my-laravel-app
    ```

7. Aplicar migraciones y seeds.
    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

8. Ejecutar este comando para traer los primeros datos del clima (solo una vez).
    ```bash
    docker-compose exec app php artisan app:check-weather-users
    ```

**Nota:** Este comando es necesario porque los datos del clima se extraen de forma automática a través de colas (jobs de Laravel) cada 30 minutos con Laravel Task Scheduling. Este paso debe ejecutarse inicialmente para obtener los datos preparados en lugar de esperar 30 minutos.

9. En el navegador, dirigirse a: `http://localhost:8000`

### Despliegue local en Windows

Requiere la dependencia de Redis en Docker o WSL. Esta opción puede mostrar la aplicación de manera más óptima, pero no ejecutará las colas de forma automática a través de Laravel Task Scheduling ni usará Laravel Horizon.

1. Clonar el repositorio, ingresar al directorio descargado y crear el archivo `.env` a partir del archivo `.env.example.local`.
    ```bash
    git clone https://github.com/Edcodigo01/weather.git
    cd weather
    cp .env.example.local .env
    ```

2. Instalar las dependencias de Laravel.
    ```bash
    composer install
    ```

3. Instalar las dependencias del frontend.
    ```bash
    npm install
    ```

4. Crear una base de datos SQL, ya sea en Docker o localmente (PostgreSQL o MySQL). El archivo `.env` ya tiene los datos predefinidos:
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=localhost
    DB_PORT=5432
    DB_DATABASE=weather
    DB_USERNAME=postgres
    DB_PASSWORD=postgres
    ```

5. Instalar Redis. Si usas Docker, ejecuta:
    ```bash
    docker run --name redis -d -p 6379:6379 redis
    ```

6. Ejecutar migraciones y seeds.
    ```bash
    php artisan migrate --seed
    ```

7. Ejecutar el comando para traer los datos del clima.
    ```bash
    php artisan app:check-weather-users
    ```

**Nota:** Este comando es necesario porque los datos del clima se extraen a través de colas (jobs de Laravel) que deberían ejecutarse automáticamente con Laravel Task Scheduling. Sin embargo, en esta opción local no es posible ejecutarlas de forma automática. Si deseas probar la opción automática, aplica el despliegue total con Docker descrito anteriormente.

8. Ejecutar el siguiente comando para desplegar la aplicación.
    ```bash
    npm run laravel-vue
    ```

**Nota:** El comando `npm run laravel-vue` es equivalente a:
    ```bash
    concurrently "vite" "php artisan serve" "php artisan queue:work"
    ```

9. En el navegador, dirigirse a: `http://localhost:8000`

## Metodologías aplicadas

Esta aplicación requiere peticiones optimizadas que obtengan datos climáticos reales. Para esto, se utilizan varios métodos:

- **Laravel Task Scheduling:**  
  En lugar de hacer una llamada a la API externa WeatherApi en cada solicitud de lista de usuarios o un usuario en particular, se usan tareas programadas que actualizan los datos cada 30 minutos. Esto verifica los cambios en la API externa y almacena los datos en la base de datos para cada usuario. De este modo, cada consulta desde el frontend será más eficiente, utilizando solo los datos locales.

- **Colas (Jobs) en Laravel:**  
  Para procesar los datos de forma más eficiente, se usan jobs de Laravel. A través de scheduling, se ejecutan jobs para cada usuario en segundo plano, permitiendo un procesamiento más óptimo. Estos jobs consultarán el clima para cada usuario. En caso de un error externo, están configurados para realizar intentos.

- **Base de datos en memoria Redis:**  
  Para optimizar las consultas locales, se utiliza Redis para almacenar datos frecuentemente consultados sin cambios previos. Por ejemplo, para listar usuarios, la primera vez se extraen desde la base de datos PostgreSQL. En las siguientes consultas, se recuperan directamente desde el cache de Redis, lo que es mucho más rápido y reduce la carga en la base de datos. Los registros de la base de datos se actualizarán en cualquier momento, y con cada job, los datos antiguos en Redis serán eliminados para actualizar el cache con los nuevos datos.

## Consideraciones

Existen varias maneras de optimizar aún más esta aplicación:

- **APIs de respaldo:** Se podrían configurar APIs secundarias para evitar la dependencia de una sola API externa en caso de fallos.

- **Actualización selectiva de datos:** Si hay un número elevado de usuarios, Laravel podría actualizar solo los datos de los usuarios con mayor frecuencia de interacción, verificando la última vez que iniciaron sesión o si están activos, evitando grandes consultas a la API externa y reduciendo la carga de la base de datos.

- **Desnormalización de base de datos:** Para grandes volúmenes de usuarios, se podría desnormalizar la base de datos, creando una tabla única que almacene tanto los datos de los usuarios como los del clima, lo que reduciría las consultas complejas.

- **Websockets:** Para mejorar la interacción, se podrían implementar websockets para notificar al frontend cuando los datos sean actualizados en la base de datos.
