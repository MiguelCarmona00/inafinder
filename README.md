# InaFinder

Buscador y mercado de fichajes de jugadores de Inazuma Eleven. App MVC en PHP 8.2
(sin framework) con MariaDB, contenedorizada con Docker.

## Estructura

```
inafinder/          Código de la aplicación (PHP) + Dockerfile
compose/inafinder/  docker-compose (base de producción + override de desarrollo)
bds/                Dump de la base de datos (esquema + datos semilla)
```

## Desarrollo

Requisitos: Docker con Compose v2.

```bash
cd compose/inafinder
docker compose up -d --build
```

- App: http://localhost:8080 (puerto configurable con `APP_PORT`)
- MariaDB expuesta en `localhost:3306` (solo en desarrollo, vía
  `docker-compose.override.yml`, que monta además el código fuente en el
  contenedor para ver los cambios sin reconstruir)
- La BD se inicializa sola con `bds/inafinderprod.sql` en el primer arranque

## Producción

```bash
cd compose/inafinder
docker compose -f docker-compose.yml up -d --build
```

Sin el override: la app corre desde la imagen construida (sin bind-mount del
código) y el puerto de MariaDB **no** se publica en el host.

> ⚠️ Los usuarios sembrados por el dump son de desarrollo. En un despliegue
> real, cambia sus contraseñas y las credenciales de BD (variables
> `DB_ROOT_PASSWORD`, `DB_PASSWORD`, etc. del compose y `inafinder/.env.docker`).

## Configuración

La app lee su configuración de `/var/www/html/.env` dentro del contenedor
(montado desde `inafinder/.env.docker`, que solo contiene credenciales locales
de Docker). El archivo `inafinder/.env` (ignorado por git) es para entornos
fuera de Docker.
