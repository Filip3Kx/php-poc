# PHP-POC

A PHP proof-of-concept application for containerization with Docker, docker-compose, and Helm.

The app uses PHP 8.x with Apache, loads `/etc/environment` on startup, installs `pdo_mysql` and `redis`, and exposes PHP info together with runtime Redis/MySQL connectivity status.

## Repository Contents
- `docker/php/Dockerfile` — multi-stage Docker build for `base` and `dev` images
- `docker/php/entrypoint.sh` — loads `/etc/environment` into Apache process environment
- `docker-compose.yml` — local setup with Traefik, Redis, MySQL, and the PHP app
- `helm/php-poc/` — reusable Helm chart for Kubernetes deployments
- `Makefile` — CLI helper for building images and rendering Helm templates
- `.env.example` — example environment variables for local docker-compose

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

### Helm Chart Features
- Configurable image repository and tag
- Mounts generated `/etc/environment` from a ConfigMap
- Supports Traefik via ingress annotations
- Deploys a `Service`, `Deployment`, and `Ingress`

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

## What this PoC covers
- PHP 8.x with Apache
- `pdo_mysql` extension installed
- `redis` extension installed
- `composer.json` dependency file present
- `/etc/environment` loaded by entrypoint
- Helm chart support with a reusable configuration
- Optional dev image via Dockerfile `dev` target
- Traefik integration in `docker-compose.yml`

## Notes / Missing Items

The app now attempts a Redis connection and a MySQL connection, but it does not implement a full application domain model or persistent storage logic. This PoC is intentionally minimal, focusing on containerization, environment injection, and runtime connectivity.

