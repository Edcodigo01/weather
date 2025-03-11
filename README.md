
# Aplicación Clima en Laravel 11 y Vue 3

Aplicación que muestra una lista de usuarios junto con el clima actual basado en su ubicación geográfica.

## REQUERIMIENTOS

- Docker y Docker Compose

## DESPLIEGUE

**Nota Importante:** El rendimiento de la aplicación puede verse afectado si se ejecuta en Docker en un entorno Windows en comparación con su ejecución localmente. Debido a que Docker no funciona de manera óptima en Windows, la aplicación podría parecer más lenta. Por esta razón, a continuación se presentan dos métodos de despliegue distintos.

### Despliegue total Docker

Permite usar Laravel Horizon y actualizaciones automáticas.

- Clonar el repositorio, ingresar al directorio descargado y crear .env a partir del archivo .env.example.docker.
```
git clone https://github.com/Edcodigo01/weather.git
cd weather
cp .env.example.docker .env
```
 
- Levantar contenedores
```
docker-compose up -d
```

- Agregar Laravel Horizon
```
docker-compose exec app composer require laravel/horizon
```
- Instalar Laravel Horizon
```
docker-compose exec app php artisan horizon:install
```
- Iniciar Laravel Horizon
```
docker-compose exec app php artisan horizon
```
- Aplicar migración y seeders
```
docker-compose exec app php artisan migrate --seed
```

- Se ejecuta este comando para traer los primeros datos del clima (solo 1 vez)
```
docker-compose exec app php artisan app:check-weather-users
```

**Nota:** Este comando es necesario, pues los datos del clima se extraen de forma automática a través de colas (jobs Laravel) cada 30 minutos con Laravel Task Scheduling, para mejorar el rendimiento de cada petición al servidor, evitando llamadas innecesarias a la API de WeatherApi. Se debe ejecutar la primera vez para tener los datos preparados, en vez de esperar 30 minutos.

- En el navegador dirigirse a: http://localhost:8000

### Despliegue local Windows

Requiere dependencia de Redis en Docker o WSL. Esta opción puede mostrar la aplicación de una forma más óptima, sin embargo, no se ejecutarán las colas de forma automática a través de Laravel Task Scheduling y tampoco podrá usar Laravel Horizon.

- Clonar el repositorio, ingresar al directorio descargado y crear .env a partir del archivo .env.example.docker, ya tiene las configuraciones necesarias.
```
git clone https://github.com/Edcodigo01/weather.git
cd weather
cp .env.example.local .env
```

- Instalar dependencias Laravel
```
composer install
```

- Instalar dependencias para el frontend
```
npm install
```

- Crear una base de datos SQL, en Docker o local como PostgreSQL o MySQL. El archivo .env tiene predefinidos los siguientes datos:
```
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=weather
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

- Instalar Redis, para instalar en Docker usar:
```
docker run --name redis -d -p 6379:6379 redis
```

- Ejecutar migraciones y seeds
```
php artisan migrate --seed
```

- Se ejecuta el comando para traer los datos del clima.
```
php artisan app:check-weather-users
```

**Nota:** Este comando es necesario, pues los datos del clima se extraen a través de colas (jobs Laravel) que se deberían ejecutar de forma automática a través de Laravel Task Scheduling. De forma local no es posible, pero si desea probar la opción automática, aplique el despliegue total en Docker descrito en la sección anterior.

- Ejecutar el siguiente comando para desplegar la aplicación
```
npm run laravel-vue
```

**Nota:** El comando **npm run laravel-vue** es equivalente a: `concurrently "vite" "php artisan serve" "php artisan queue:work"`

- En el navegador dirigirse a: http://localhost:8000

## Metodologías aplicadas

Para esta aplicación se requieren peticiones optimizadas. Estas deberán traer datos del clima reales, debido a esto se recurrió al uso de una combinación de métodos.

- Laravel Task Scheduling  
  En vez de hacer una llamada a la API externa WeatherApi, en cada solicitud de lista de usuarios o un usuario en particular, se aplica el uso de tareas programadas, que actualizarán los datos cada 30 minutos, para verificar cambios en la API externa y almacenarlos en bases de datos para cada usuario. De este modo, cada consulta desde el frontend de la aplicación al backend será mucho más eficiente, dependiendo solo de los datos locales.

- Colas (Jobs) Laravel  
  Para procesar los datos de una forma más eficiente, se utilizan jobs de Laravel. A través de scheduling se llama la ejecución de jobs para cada usuario. Estos jobs ejecutarán su función en colas y en segundo plano, permitiendo un procesamiento de estas tareas de una forma más óptima. A través de estos trabajos es que se consultará el clima para cada usuario. En caso de algún error externo, están configurados para realizar intentos.

- Base de datos en memoria Redis  
  Para optimizar un poco más las consultas locales, se recurre al uso de cache Redis. Con esto podemos almacenar datos que suelen ser usados con mucha frecuencia sin cambios previos. En este caso, para listar los usuarios, la primera vez que se acceda a la lista, extraerá los datos desde la base de datos PostgreSQL. Para la siguiente vez que intente acceder, estos datos ya se habrán guardado en cache en la petición anterior, y en esta oportunidad, los datos, en vez de ser traídos desde la base de datos, se obtendrán directamente desde cache, lo que es mucho más rápido y elimina carga de trabajo para la base de datos. Por supuesto, los registros de la base de datos serán actualizados en cualquier momento. En este caso, con cada job, es acá donde se eliminarán los datos antiguos desde la cache, para que al hacer otra petición al listado de usuarios, estos extraigan los nuevos datos insertados en la base de datos y actualicen los de la cache.

## Consideraciones

Es posible aplicar más métodos para optimizar esta aplicación.

Se podrían utilizar APIs de respaldo, es decir, si falla la API usada, hacer una consulta a una segunda, y así evitar dependencia de una única API.

En caso de tener un número de usuarios mucho mayor, se podría configurar Laravel para que actualice los datos solo de los usuarios que tienen mayor frecuencia en la aplicación, verificando la última vez que inició sesión, o si está activo en ese momento, evitando de esta forma grandes consultas a la API externa y trabajo para la base de datos.

En cuanto a la base de datos, en este caso se aplican relaciones, pero para grandes volúmenes de usuarios se puede aplicar la desnormalización de base de datos, que consiste en crear tablas que obtengan todos los datos requeridos para una solicitud, en este caso sería una tabla única para almacenar los datos de los usuarios, a la vez que almacena los datos del clima que ofrece la API externa.

Para mejorar la interacción con los usuarios de la aplicación, se podrían aplicar websockets, para comunicar al frontend cuando los datos sean actualizados en base de datos y se haga la actualización de estos.
