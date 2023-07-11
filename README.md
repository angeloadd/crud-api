# CRUD API to manage products

## Start

To bootstrap application you have to install docker. Follow official guidelines for docker installation
([Docker](https://docs.docker.com/engine/install/)) then run:

```shell
docker compose up -d
docker exec -it rebuy-app composer install
docker exec -it rebuy-app php bin/console doctrine:migrations:migrate
```

After building and lunching the containers' application can be accessed at http://localhost:8001 while a swagger
documentation ui can be accessed at http://localhost:8082

## Test

See composer for further info. You can lunch test by running one of these commands:

```shell
docker exec -it rebuy-app composer test
docker exec -it rebuy-app composer test:unit
docker exec -it rebuy-app composer test:integration
docker exec -it rebuy-app composer test:api
docker exec -it rebuy-app composer test:filter #add name of the test
```

Or if you want to run a shell into the container:

```shell
docker exec -it rebuy-app /bin/bash
```

## Standard

For simple fixed php-cs-fixer was added to project. Run it as:

```shell
docker exec -it rebuy-app composer fix
```
