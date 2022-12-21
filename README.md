#Figured exercise

## Requirement

- Docker

## Setup
run the following commands.
1. `source bin/bash_aliases`
2. `sail up -d`
3. `sail composer install`
4. `sail artisan migrate`
5. `sail artisan load`
6. `sail npm run dev`

then go to `http://127.0.0.1/price`

##Run Tests

1. `sail artisan migrate --env=testing`
2. `sail artisan test`
