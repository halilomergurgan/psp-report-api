## PSP Report API
This project is an API designed for managing transactions, including user authentication, transaction listing and other related functionalities. The API logs every request and response in the database, integrates with Elasticsearch for advanced searching, and utilizes JWT for user authentication. The API has been designed with a clean structure, making use of Service(SOA)-Repository patterns, Middleware, and Request Validations.

## Technologies Used

The following technologies were used in this project:

- **Laravel 10**: The core framework used for building the API, providing a robust structure for routing, middleware, authentication, and more.
- **PHP 8.3**: The programming language powering the backend.
- **Docker with Laravel Sail**: A lightweight command-line interface for interacting with Docker, used to set up the environment with services like MySQL, Redis, and Elasticsearch.
- **MySQL**: The primary database used to store transactional data, user data, and request logs.
- **Elasticsearch & Laravel Scout**: Integrated Elasticsearch with the help of Laravel Scout for full-text searching, indexing transactions, and fast querying.
- **JWT (JSON Web Token)**: Used for user authentication and session management. JWT is responsible for issuing and validating tokens that grant access to protected routes.
- **Laravel Filterable**: Utilized to easily apply filters to Eloquent queries based on incoming request parameters. This allows for dynamic querying of resources like transactions, customers, and other models, giving the API flexibility to handle various filter options.
- **Laravel Resources**: These resources allow for complex data manipulation (like including related models) and offer flexibility in controlling what information is exposed in the API responses.

# Prerequisites
Docker installed on your system (you don't need PHP, MySQL, or Composer locally).

# Setup Instructions
To set up the project from scratch, you can use the provided setup.sh script. This script will automatically spin up the Docker containers, install dependencies, migrate the database, seed necessary data.

# Setup Process
1. Clone the repository
2. Run the setup script
```bash
   sh setup.sh
```
This script will:

- Start Docker containers with MySQL, Elasticsearch, and PHP.
- Install Composer dependencies.
- Create the .env file if it doesn't already exist.
- Generate JWT secret key.
- Generate the application key.
- Run database migrations and seeders.
- Create Elasticsearch indexes
- Index the data with Laravel Scout:

3. After the setup is complete, the application should be running on http://localhost.

## API Documentation
For detailed API usage and endpoints, please refer to the official API documentation  [here](https://documenter.getpostman.com/view/7169628/2sAXqp83bt)

### Users

For testing purposes, the following credentials are available:

#### Merchant User:
- **Email**: `merchant@test.com`
- **Password**: `123*-+`

If you have any questions or need further assistance, feel free to reach out to me at halilomergurgan@gmail.com. I'm happy to help!

