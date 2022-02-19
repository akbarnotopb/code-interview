# Installation
Note : Developed / created on Linux Ubuntu 20.04

## Pre Requisite
1. Docker
2. Docker Compose
3. Configure docker permission
   1.  mine was running under main user with uid 1000, see `docker-compose.yml` line `61`
   2.  setup other settings accordingly

## Steps
1. Build the image `docker-compose build`
2. Start the images `docker-compose up` or `docker-compose up -d`
3. Build the laravel `docker-compose exec php composer install`
4. Publish JWT by running
   1. `docker-compose exec php php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`
6. Migrate the database `docker-compose exec php php artisan migrate` *
7. The .env is included for easier setup
   1. However, you can always copy your own .env, but make sure the `database driver` and `db_host` is correctly set up
   2. If you set up your own .env, please run `artisan key:generate` and `artisan jwt:secret`
8. Enjoy

## API Documentation
Please download [this insomnia json and import it onto your insomnia](https://github.com/akbarnotopb/code-interview/blob/main/V1-Api-Insomnia%20Documentation.json)
