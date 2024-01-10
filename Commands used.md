# Main OS

## Docker

sudo docker-compose up -d         # "detached" mode. The containers will run in the background
sudo docker-compose up -d --build # it will start the services defined in your docker-compose.yml file in detached mode, and rebuild the Docker images for those services if needed.
sudo docker-compose ps            # output that shows information about the containers, such as their names, IDs, status (running, stopped, etc.)
sudo docker-compose exec app bash # open an interactive shell session inside the "app" service container.
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' op3ra_database_1 # Check the IP address to configure dbeaver host connection

## Redis

docker-compose exec redis redis-cli # Enter the Redis CLI via docker compose
KEYS *                              # List all keys


## Linux

mv skeleton/* ./                  # Move everything outside the skeleton folder to the parent directory
mv skeleton/.env ./               # Move .env outside the skeleton folder to the parent directory
mv skeleton/.gitignore ./         # Move .gitignore outside the skeleton folder to the parent directory
rmdir skeleton                    # Delete the skeleton folder

## Container

## Composer

composer create-project symfony/skeleton:"6.2.*" #  Creates a new Symfony project based on the Symfony Skeleton package with a version constraint of Symfony 6.2.x
composer require symfony/orm-pack                # Symfony ORM Pack along with its dependencies, will ask whether we want to add config for dockerfile, which we want.
composer require --dev symfony/maker-bundle      # Symfony Maker Bundle as a development dependency to a Symfony project
composer require symfony/serializer-pack
composer require symfony/test-pack               # PHPunit etc
composer require symfony/validator
composer require symfony/intl
composer require symfony/security-bundle         # Security bundle. Will add a securiy file, **config/packages/security.yaml**

## PHPUnit

sudo docker-compose exec app php bin/phpunit

## Custom Command (User)

sudo docker-compose exec app php bin/console app:user-create administrador boss ROLE_ADMIN, ROLE_USER

# Symfony

symfony check:requirements
php bin/console
php bin/console make:entity
php bin/console make:user                        # From symfony/security-bundle
php bin/console make:command                     # Command is prefixed with the project name (app) for grouping sake

## Doctrine

php bin/console doctrine:migrations:status
php bin/console doctrine:migrations:list
php bin/console doctrine:migrations:migrate

## cURL

### Auth

**login**
curl --location --request GET 'localhost:8000/login' --header 'Content-Type: application/json' --header 'Accept: application/json' --data-raw '{"username":"admin","password":"1234"}'

**Passing the Authentication Bearer token**
-H 'Authorization: Bearer <token>'

### Composer

**index**
curl --location --request GET 'localhost:8000/composer' --header 'Content-Type: application/json' --header 'Accept: application/json'
**show**
curl --location --request GET 'localhost:8000/composer/1' --header 'Content-Type: application/json' --header 'Accept: application/json'
**create**
curl --location --request POST 'localhost:8000/composer' --header 'Content-Type: application/json' --header 'Accept: application/json' --data-raw '{"firstName":"Wolfgang","lastName":"Mozart", "dateOfBirth":"1756-01-27","countryCode":"AT"}'
**update**
curl --location --request PUT 'localhost:8000/composer/20' --header 'Content-Type: application/json' --header 'Accept: application/json' --data-raw '{"firstName":"Wolfgang","lastName":"Mozart", "dateOfBirth":"1756-01-27","countryCode":"AT"}'
**delete**
curl --location --request DELETE 'localhost:8000/composer/20' --header 'Content-Type: application/json' --header 'Accept: application/json'

### Symfony

**index**
curl --location --request GET 'localhost:8000/symfony' --header 'Content-Type: application/json' --header 'Accept: application/json'
**show**
curl --location --request GET 'localhost:8000/symfony/1' --header 'Content-Type: application/json' --header 'Accept: application/json'
**create**
curl --location --request POST 'localhost:8000/symfony' --header 'Content-Type: application/json' --header 'Accept: application/json' --data-raw '{"name":"No. 1","description":"Moving", "composerId": 1}'
**update**
curl --location --request PUT 'localhost:8000/symfony/20' --header 'Content-Type: application/json' --header 'Accept: application/json' --data-raw '{"name":"No. 1","description":"Powerful", "composerId": 1}'
**delete**
curl --location --request DELETE 'localhost:8000/symfony/20' --header 'Content-Type: application/json' --header 'Accept: application/json'