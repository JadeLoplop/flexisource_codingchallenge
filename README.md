# Laravel Customer Importer API

This Laravel project demonstrates how to build a RESTful API that imports customer data from a third-party API and stores it in a database. It also provides endpoints to list and retrieve customer details.

## Features

- Import customers from the [RandomUser API](https://randomuser.me/api) and store them in the database.
- Display a list of customers stored in the database.
- Retrieve detailed information about a specific customer.

## Project Structure

- **Controller**: Handles HTTP requests and responses.
- **Service**: Contains business logic, including the customer import functionality.
- **Model**: Manages database interactions.
- **Tests**: Unit and feature tests to ensure the correctness of the application.

## Prerequisites

- PHP 8.x
- Composer
- Laravel 11.x
- MySQL or another supported database

## Installation

1. **Clone the repository:**
    ```bash
    git clone https://github.com/JadeLoplop/flexisource_codingchallenge.git
    cd flexisource_codingchallenge
    ```

2. **Install dependencies:**
    ```bash
    composer install
    ```

3. **Copy the `.env` file and configure your environment:**
    ```bash
    cp .env.example .env
    ```
   Update the `.env` file with your database credentials and other necessary configuration.

4. **Run the database migrations:**
    ```bash
    php artisan migrate
    ```

5. **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

6. **Run the application:**
    ```bash
    php artisan serve
    ```

## API Endpoints

### Import Customers

- **Endpoint:** `POST /api/customers/import`
- **Description:** Imports 100 customers with Australian nationality from the RandomUser API and stores them in the database.
- **Example Request:**
    ```bash
    curl -X POST http://127.0.0.1:8000/api/customers/import
    ```

### List Customers

- **Endpoint:** `GET /api/customers`
- **Description:** Retrieves a list of all customers stored in the database.
- **Response Structure:**
    ```json
    [
        {
            "full_name": "John Doe",
            "email": "johndoe@example.com",
            "country": "Australia"
        },
        ...
    ]
    ```

### Get Customer Details

- **Endpoint:** `GET /api/customers/{customerId}`
- **Description:** Retrieves detailed information about a specific customer.
- **Response Structure:**
    ```json
    {
        "data": {
            "full_name": "John Doe",
            "email": "johndoe@example.com",
            "username": "johndoe",
            "gender": "male",
            "country": "Australia",
            "city": "Sydney",
            "phone": "123-456-7890"
        }
    }
    ```

## Tests

- **Unit Tests:** Focus on individual methods or components.
- **Feature Tests:** Validate the functionality of the API endpoints.

### Running Tests

To run all tests, use the following command:

```bash
php artisan test
```

## Design Considerations

- **Single Responsibility Principle**: Code logic is decoupled into services and controllers to ensure maintainability.
  
- **Configuration Management**: Environment-specific values are stored in the `.env` file, ensuring flexibility in case of requirement changes.
  
- **Error Handling**: Proper exception handling is implemented throughout the application to provide meaningful error messages and logging.
