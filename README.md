## Local Docker Compose

1. Copy the example environment file:

```bash
cp .env.example .env
```

2. Build and start the stack:

```bash
docker-compose up -d --build
```

3. Open the app:

- Direct: `http://localhost:8081`
- Traefik: `http://app.localhost` (requires local DNS / hosts entry)

### Dev Image with xdebug

Use the `dev` target from `.env`:

```bash
APP_ENV=dev docker-compose up -d --build
```

This builds the `dev` image stage from `docker/php/Dockerfile`, installs `xdebug`, and exposes the same app stack.

## Build Commands

Use the provided `Makefile` to build the Docker images:

```bash
make build-base
make build-dev
```

`build-base` produces `poc/php-docker:latest`, and `build-dev` produces `poc/php-docker:dev`.

## Helm Chart

Install the chart with the default image tag:

```bash
helm upgrade --install php-poc helm/php-poc --set image.tag=latest
```

Install the dev image variant:

```bash
helm upgrade --install php-poc helm/php-poc --set image.tag=dev
```

Render the template locally:

```bash
make helm-template
```

## Environment

The app loads variables from `/etc/environment` at container startup, then makes them available to Apache.

Example values in `.env.example`:

```text
MY_CUSTOM_VAR=Hello from Compose
APP_ENV=base
REDIS_HOST=redis
REDIS_PORT=6379
MYSQL_HOST=mysql
MYSQL_PORT=3306
MYSQL_DATABASE=app
MYSQL_USER=appuser
MYSQL_PASSWORD=apppassword
```

