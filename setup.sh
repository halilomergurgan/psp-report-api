#!/bin/bash

echo "Setting up the project with Docker Sail..."

if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies using Docker..."
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --no-dev --prefer-dist --optimize-autoloader
else
    echo "Vendor folder already exists, skipping Composer install."
fi

if [ ! -f "vendor/bin/sail" ]; then
    echo "Sail not found. Installing Sail dependencies..."
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer require laravel/sail --dev
fi

echo "Starting Docker containers"
./vendor/bin/sail up -d

echo "Waiting for MySQL service to be ready..."
./vendor/bin/sail exec mysql bash -c 'until mysqladmin ping -h "mysql" --silent; do echo "Waiting for database connection..."; sleep 5; done'

sleep 7

echo "Entering the Laravel container..."

# Container name is statically set
CONTAINER_NAME="psp-report-api-laravel.test-1"

# Check if the container exists
docker exec -it $CONTAINER_NAME bash -c "
    echo 'Running database migrations...'
    php artisan migrate

    echo 'Seeding the database...'
    php artisan db:seed

    echo 'Exists index delete'
    curl -X DELETE 'http://elasticsearch:9200/users'
    curl -X DELETE 'http://elasticsearch:9200/transactions'
    curl -X DELETE 'http://elasticsearch:9200/customers'

    echo 'Setting up Elasticsearch index and mappings...'
    curl -X PUT 'http://elasticsearch:9200/transactions' -H 'Content-Type: application/json' -d'
    {
      \"mappings\": {
        \"properties\": {
          \"transaction_id\": { \"type\": \"text\" },
          \"merchant_id\": { \"type\": \"keyword\" },
          \"status\": { \"type\": \"keyword\" },
          \"operation\": { \"type\": \"keyword\" },
          \"payment_method\": { \"type\": \"keyword\" },
          \"reference_no\": { \"type\": \"keyword\" },
          \"custom_data\": { \"type\": \"keyword\" },
          \"channel\": { \"type\": \"keyword\" },
          \"acquirer_id\": { \"type\": \"keyword\" },
          \"fx_id\": { \"type\": \"keyword\" },
          \"agent_id\": { \"type\": \"keyword\" },
          \"customer_id\": { \"type\": \"keyword\" },
          \"created_at\": { \"type\": \"date\" },
          \"updated_at\": { \"type\": \"date\" },
          \"fx\": {
            \"properties\": {
              \"id\": { \"type\": \"keyword\" },
              \"original_amount\": { \"type\": \"double\" },
              \"original_currency\": { \"type\": \"keyword\" }
            }
          },
          \"customer\": {
            \"properties\": {
              \"id\": { \"type\": \"keyword\" },
              \"number\": { \"type\": \"keyword\" },
              \"expiry_month\": { \"type\": \"integer\" },
              \"expiry_year\": { \"type\": \"integer\" },
              \"email\": { \"type\": \"keyword\" },
              \"billing_first_name\": { \"type\": \"keyword\" },
              \"billing_last_name\": { \"type\": \"keyword\" },
              \"billing_address1\": { \"type\": \"keyword\" },
              \"billing_city\": { \"type\": \"keyword\" },
              \"billing_postcode\": { \"type\": \"keyword\" },
              \"billing_country\": { \"type\": \"keyword\" },
              \"shipping_first_name\": { \"type\": \"keyword\" },
              \"shipping_last_name\": { \"type\": \"keyword\" },
              \"shipping_address1\": { \"type\": \"keyword\" },
              \"shipping_city\": { \"type\": \"keyword\" },
              \"shipping_postcode\": { \"type\": \"keyword\" },
              \"shipping_country\": { \"type\": \"keyword\" }
            }
          }
        }
      }
    }'

    echo 'Indexing with Laravel Scout...'
    php artisan scout:import 'App\Models\User'
    php artisan scout:import 'App\Models\Transaction'
    php artisan scout:import 'App\Models\Customer'

    if [ ! -L 'public/storage' ]; then
        echo 'Linking storage directory...'
        php artisan storage:link
    else
        echo 'Storage directory link already exists.'
    fi

    echo 'Setup complete!'
"
