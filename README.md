# Flexisource Coding Challenge

This repository contains the solution for the Flexisource coding challenge. The application is built using Laravel and demonstrates how to import, store, and retrieve customer data from a third-party API.

## Features
- Import customers from a third-party data provider.
- Display a list of customers from the database.
- Retrieve and display details of a single customer from the database.

## Design Considerations
- **Single Responsibility Principle**: Code logic is decoupled into services and controllers to ensure maintainability.
- **Configuration Management**: Environment-specific values are stored in the `.env` file, ensuring flexibility in case of requirement changes.
- **Error Handling**: Proper exception handling is implemented throughout the application to provide meaningful error messages and logging.

## Installation Instructions

1. **Clone the Repository**
    ```bash
    git clone https://github.com/JadeLoplop/flexisource_codingchallenge.git
    cd flexisource_codingchallenge
    ```

2. **Install Dependencies**
    ```bash
    composer install
    ```

3. **Environment Configuration**
    - Copy the `.env.example` to `.env`
    - Set up your database credentials in the `.env` file.
    - Run the migrations to set up the database schema:
        ```bash
        php artisan migrate
        ```

4. **Running the Importer (Optional, Since you can use API Endpoint which by default is 100)**
    - After migrating the database, you need to import customer data. You can manually trigger the import using the artisan command:
        ```bash
        php artisan customers:import [number]
        ```
      Replace `[number]` with the number of records you want to import. The default value is 100 if no number is provided.

5. **Running Tests**
    - Run the test suite to ensure everything is functioning as expected:
        ```bash
        php artisan test
        ```

## API Endpoints

- **GET /api/customers**: Retrieve the list of all customers.
- **GET /api/customers/{customerId}**: Retrieve details of a specific customer.
- **POST /api/customers/import**: Import customers from the third-party API.

## Postman Collection
A Postman collection is included in the project for testing the API endpoints.

- Import the `UserImporter.postman_collection.json` file into Postman to use the pre-configured requests.
