# CHECK24 Credit Card Comparison

This is a Symfony-based application for comparing credit cards, built as a solution to the CHECK24 code challenge. It imports credit card data from an external API, displays it in a sortable list, and allows non-technical users to edit card details with persistence across updates.

## Features
- **Data Import**: Fetches credit card data from webservice API and updates the local MySQL database.
- **Results Page**: Displays a list of credit cards with dynamic sorting by price (`annualFee`) or name (`name`), in ascending or descending order.
- **Editing**: Allows editing of card details (`name`, `annualFee`, `remarks`) via a dedicated page, with changes stored separately to persist across API updates.
- **Remarks**: Highlights key features (e.g., "No annual fee!" or "High TAE!") with checkmarks or warnings this option is handled in frontend with filtering some words.

## Tech Stack
- **Framework**: Symfony (latest version as of March 2025)
- **Database**: MySQL with Doctrine ORM
- **Frontend**: Twig templates with Bootstrap 5 for styling
- **API Client**: Symfony HttpClient for fetching XML data
- **CLI**: Symfony Console for importing data

## Design Principles
- **SOLID**: Single Responsibility (e.g., separate repositories and services), Dependency Inversion (via interfaces).
- **Clean Code**: Readable, maintainable code with meaningful names and minimal complexity.
- **Partial DDD**: Separates concerns with entities, repositories, and services, though not fully domain-driven.
- **Contracts**: Uses `RepositoryInterface` and `CardServiceInterface` for abstraction.
- **Business Logic**: Encapsulated in services and repositories, keeping controllers thin.

## Prerequisites
- PHP 8.1+
- Composer
- MySQL
- Symfony CLI (optional, for `symfony server:start`)
