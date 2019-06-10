docker-compose up -d && docker-compose exec $(sed -e 's/^/-e /' .env.testing) php ./vendor/bin/phpunit "$@"
