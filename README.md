#Figured exercise

## Requirement

- Docker
- composer

## Setup
run the following commands.

1. `composer install`
2. `source bin/bash_aliases`
3. `sail up -d`
5. `sail artisan migrate`
6. `sail artisan load`
7. `sail npm install`
8. `sail npm run dev`

then go to `http://127.0.0.1/price`

##Run Tests

1. `sail artisan migrate --env=testing`
2. `sail artisan test`
