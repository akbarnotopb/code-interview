# Installation
Note : Developed / created on Linux Ubuntu 20.04

## Pre Requisite
1. Docker
2. Docker Compose

## Steps
1. Build the image `docker-compose build`
2. Start the images `docker-compose up` or `docker-compose up -d`
3. Migrate the database `docker-compose exec php php artisan migrate` *
* The .env is included for easier setup
