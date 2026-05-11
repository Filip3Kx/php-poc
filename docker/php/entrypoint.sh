#!/bin/bash
set -e

if [ -f /etc/environment ]; then
    echo "Loading env variables"
    echo "set -a; . /etc/environment; set +a" >> /etc/apache2/envvars
fi

exec docker-php-entrypoint "$@"