.PHONY: build-base build-dev build helm-template helm-install helm-package

BUILD_DIR=docker/php
IMAGE_NAME=poc/php-docker

build-base:
	docker build --target base -t $(IMAGE_NAME):latest $(BUILD_DIR)

build-dev:
	docker build --target dev -t $(IMAGE_NAME):dev $(BUILD_DIR)

build: build-base

helm-template:
	helm template php-poc helm/php-poc

helm-install:
	helm upgrade --install php-poc helm/php-poc --set image.tag=latest

helm-package:
	helm package helm/php-poc
