# Translation Management Service

A Laravel-based service for managing translations. 
This service allows you to store and manage translations for different locales and device types (mobile, tablet, desktop).

## Features

- Store translations for multiple locales
- Device-specific translations (mobile, tablet, desktop)
- Group translations for better organization
- RESTful API endpoints for CRUD operations
- Swagger API documentation
- Token-based authentication (using Laravel Sanctum)
- Caching support for better performance
- Error handling and validation
- Data Transfer Objects (DTOs) for type safety
- Used repository pattern
- Form Request validation
- Http reponse trait
- Laravel Resources for API responses
- Database Factories and Seeders
- Custom Artisan Commands

## Requirements

- PHP 8.3 or higher
- Laravel 12.x
- MySQL
- Composer

## Installation

1. Clone the repository:
git clone <repository-url>
cd <project-directory>

2) composer install

3. Copy the environment file:  cp .env.example .env

4. Generate application key: php artisan key:generate

5. Configure your database in `.env`:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=translation
DB_USERNAME=root
DB_PASSWORD=

6. Run migrations:  php artisan migrate


7. Generate Swagger documentation: php artisan l5-swagger:generate


* Project Structure

* Interfaces
- `TranslationServiceInterface`: Defines the contract for translation service operations
- `TranslationRepositoryInterface`: Defines the contract for translation data access

* Form Requests
- `TranslationRequest`: Handles validation for translation creation and updates
- `LocaleRequest`: Handles validation for locale-related operations

* Resources
- `TranslationResource`: Transforms translation models into JSON responses
- `LocaleResource`: Transforms locale models into JSON responses

* Http Response Trait
- Transforms translation models records into JSON responses

* Exceptions
- `TranslationNotFoundException`: Custom exception for missing translations
- `LocaleNotFoundException`: Custom exception for missing locales
- `InvalidDeviceTypeException`: Custom exception for invalid device types

* Factories
- `TranslationFactory`: Generates test data for translations
- `LocaleFactory`: Generates test data for locales
- `UserFactory`: Generates test data for users

* Commands
- `CreateTranslation`: Artisan command to create translations via CLI
- `UpdateTranslation`: Artisan command to update translations via CLI
- `DeleteTranslation`: Artisan command to delete translations via CLI
- `GenerateTestTranslations`: Generates test translations for all locales and device types

* Seeders
- `DatabaseSeeder`: Main seeder that calls other seeders
- `UserSeeder`: Seeds default users
- `LocaleSeeder`: Seeds default locales
- `TranslationSeeder`: Seeds sample translations

* Data Generation

1. Run seed: php artisan db:seed

2. Generate test translations:

* Generate default number of translations (100,000)
php artisan translations:generate

* Or specify a custom number of translations
php artisan translations:generate 50000

This will create:
- Default users (admin and test user)
- Default locales (en, es, fr)
- Sample translations for each locale
- Test translations for all device types (mobile, tablet, desktop)
- Random number of translations based on the specified count (default: 100,000)

* Test Data Structure
The generated test translations include:
- Common UI elements (buttons, labels, messages)
- Error messages
- Success messages
- Navigation items
- Form labels and placeholders
- Device-specific content variations

* Available Device Types
- `desktop`: Full desktop experience
- `tablet`: Tablet-optimized content
- `mobile`: Mobile-optimized content

* Translation Groups
- `general`: Common UI elements
- `auth`: Authentication related messages
- `validation`: Form validation messages
- `errors`: Error messages
- `success`: Success messages
- `navigation`: Navigation items
- `forms`: Form-related content

* API Documentation

The API documentation is available at `/api/documentation` after running the Swagger generation command.

* Authentication

All API endpoints are protected with token-based authentication using Laravel Sanctum. To access the API:

1. Create a personal access token:
```bash
php artisan sanctum:token
```

2. Include the token in your API requests:
```
Authorization: Bearer your-token-here
```

* Available Api Endpoints

- `GET /api/translations` - List all translations
- `POST /api/translations` - Create a new translation
- `GET /api/translations/{id}` - Get a specific translation
- `PUT /api/translations/{id}` - Update a translation
- `DELETE /api/translations/{id}` - Delete a translation
- `GET /api/translations/locale/{locale}` - Get translations by locale



