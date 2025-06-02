
# SignageSaaS - Multi-Tenant Digital Signage Platform

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version Require](http://poser.pugx.org/laravel/framework/require/php)](https://packagist.org/packages/laravel/framework)
[![Tests](https://github.com/your-org/signagesaas/actions/workflows/run-tests.yml/badge.svg)](https://github.com/your-org/signagesaas/actions/workflows/run-tests.yml)

A robust multi-tenant SaaS platform for managing digital signage, built with Laravel 12, PHP 8.3+, Livewire 3, and Tailwind CSS. Leveraging `stancl/tenancy` for seamless tenant isolation, SignageSaaS provides a scalable, secure, and customizable solution for businesses to manage their digital signage networks efficiently.

---

## 💡 Features

- **Multi-Tenancy**: Dedicated databases, storage, and application layers for each tenant, powered by `stancl/tenancy`.
- **Device, Screen, and Content Management**: Intuitive tools for managing digital signage devices, screens, and content.
- **Livewire-First Architecture**: Interactive features powered by Livewire v3, with Alpine.js for client-side enhancements.
- **AI Integration (Optional)**: Content generation and analytics capabilities (via LangChain PHP).
- **Modern UI**: A clean, responsive, and accessible user interface built with Tailwind CSS 4, featuring dark/light mode.
- **Robust Security**: RBAC, JWT authentication, and comprehensive protection against common web vulnerabilities (OWASP).
- **Comprehensive API**: Well-documented APIs for seamless integration with other systems.

---

## 🏛️ Architecture

SignageSaaS is designed with scalability, security, and maintainability in mind. The platform utilizes a multi-tenant architecture to ensure data isolation and efficient resource utilization.

### Multi-Tenancy

Powered by `stancl/tenancy`, SignageSaaS offers multi-tenancy at the following layers:

- **Database Layer**: Each tenant has their own database, ensuring complete data isolation.
- **Application Layer**: Tenant-specific configurations and settings are managed separately, allowing for customization without affecting other tenants.
- **Storage Layer**: Dedicated storage buckets or directories for each tenant, preventing unauthorized access to assets.

```mermaid
graph LR
    subgraph Shared Infrastructure
        A[Load Balancer] --> B(Application Servers)
        B --> C{Router (stancl/tenancy)}
        C -- Tenant 1 --> D1[Tenant 1 Database]
        C -- Tenant 2 --> D2[Tenant 2 Database]
        C -- Tenant N --> DN[Tenant N Database]
        B --> S(Shared Storage - Tenant Specific Folders);
    end
```

### Scalability

SignageSaaS is designed to scale horizontally to accommodate a growing number of tenants and devices.

- **Horizontal Scaling**: The application can be deployed across multiple servers behind a load balancer.
- **Database Sharding**: For extremely large datasets, database sharding can be implemented to distribute the load across multiple database servers.
- **Caching**: Redis is used for caching frequently accessed data, reducing the load on the database.

### Security

Security is a top priority for SignageSaaS. The platform implements the following security measures:

- **Role-Based Access Control (RBAC)**: Fine-grained control over user permissions.
- **JWT Authentication**: Secure authentication for APIs using JSON Web Tokens.
- **OWASP Protection**: Protection against common web vulnerabilities such as SQL injection, XSS, and CSRF.
- **Data Encryption**: Sensitive data is encrypted at rest and in transit.

### Monitoring and Logging

SignageSaaS is monitored using a combination of tools to ensure optimal performance and uptime.

- **Logging**: Detailed logs are generated for all application events, making it easy to diagnose issues.
- **Alerting**: Real-time alerts are triggered when performance thresholds are breached or errors occur.
- **Performance Analysis**: Tools like New Relic or Datadog can be integrated for in-depth performance analysis.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.3+, Laravel 12, `stancl/tenancy`, Livewire 3
- **Frontend**: Tailwind CSS 4, Alpine.js, Vite
- **Database**: MySQL 8 (Dockerized)
- **Containerization**: Docker, docker-compose
- **Testing**: PHPUnit, Laravel Dusk
- **Optional**: Redis, Laravel Horizon

---

## ⚡ Getting Started

### Prerequisites

- [Docker](https://www.docker.com/)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) (v20+ recommended)

### Quick Start (Docker Compose)

```bash
git clone https://github.com/your-org/signagesaas.git
cd signagesaas
cp .env.example .env
> # Edit .env as needed:
> # - Set APP_KEY
> # - Configure database connection for the main app and tenant databases
> # - Configure Redis connection if using caching
docker-compose up --build -d
```

- App: http://localhost
- phpMyAdmin: http://localhost:8080

### Manual Setup (Local)

```bash
composer install
npm install
cp .env.example .env
> # Edit .env as needed:
> # - Set APP_KEY
> # - Configure database connection for the main app and tenant databases
> # - Configure Redis connection if using caching
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

---

## 🧑‍💻 Development Workflow

- **Run all services**: `docker-compose up`
- **Run tests**: `./vendor/bin/phpunit` (or `php artisan test`)
- **Run Dusk browser tests**: `php artisan dusk`
- **Run frontend**: `npm run dev` (Vite)
- **Code style**: `vendor/bin/pint`

---

## ✅ Testing & Quality

- All code is covered by unit and feature tests (`tests/Unit`, `tests/Feature`)
- Livewire components have dedicated feature tests
- Accessibility is a first-class concern
- Run all tests before submitting PRs

---

## 📦 Core Features

- **Device Manager**: Add, edit, filter, and manage devices.
- **Screen Manager**: Organize screens per device.
- **Content Manager**: Schedule and assign content to screens.
- **Multi-Tenant Isolation**: All data and assets are tenant-scoped, ensuring secure separation.
- **AI Integration (Optional)**: Content generation and analytics to enhance user experience.

#### Example: Device Manager UI

See [`resources/views/livewire/devices/device-manager.blade.php`](resources/views/livewire/devices/device-manager.blade.php) for a full-featured device management interface.

---

## ⚙️ Tenant Management

Tenant management is a crucial aspect of SignageSaaS. Here's how tenants are managed:

- **Tenant Creation**: New tenants can be created via the administrative interface or through an API endpoint.
- **Tenant Update**: Tenant details, such as name, domain, and contact information, can be updated as needed.
- **Tenant Deletion**: Tenants can be deactivated or permanently deleted from the system.
- **Tenant Configuration**: Tenant-specific configurations can be managed through environment variables or a dedicated settings panel.

Example of creating a tenant using the `stancl/tenancy` API:

```php
use App\Models\Tenant;

$tenant = Tenant::create([
    'id' => 'tenant1', // You can use a UUID generator here
    'name' => 'Tenant One',
    'domain' => 'tenant1.example.com',
]);

// Run tenant specific migrations, seeders etc
$tenant->run(function () {
    Artisan::call('migrate', ['--database' => 'tenant', '--path' => 'database/migrations/tenant']);
    Artisan::call('db:seed', ['--database' => 'tenant', '--class' => TenantSeeder']);
});
```

---

## 🎨 Customization and Extensibility

SignageSaaS is designed to be highly customizable and extensible, allowing tenants to tailor the platform to their specific needs.

- **Themes**: Tenants can customize the look and feel of the platform by applying custom themes.
- **Plugins**: The platform supports plugins, allowing tenants to add new features and functionality.
- **Custom Code**: For advanced customization, tenants can add custom code to the platform using Laravel's powerful extension capabilities.

---

## 📚 API Documentation

SignageSaaS provides a comprehensive API for interacting with the platform programmatically.

- **Authentication**: The API uses JWT authentication. Obtain a token by sending a POST request to `/api/login` with your credentials.
- **Request/Response Format**: All API requests and responses are in JSON format.
- **Example Use Cases**:
    - Create a new device: `POST /api/devices`
    - Get a list of screens: `GET /api/screens`
    - Update content on a screen: `PUT /api/screens/{screen_id}/content`

> **Note**: A full OpenAPI/Swagger specification is available at `/api/docs`.

---

## 📜 SLA and Support

We offer different Service Level Agreements (SLAs) based on your subscription plan. Our support channels include:

- **Email Support**: support@signagesaas.com
- **Phone Support**: +1-555-123-4567
- **Knowledge Base**: [Link to Knowledge Base]

> **Note**: Replace placeholders with actual contact information and SLA details.

---

## ⛑️ Disaster Recovery

SignageSaaS includes a robust disaster recovery plan to ensure business continuity in the event of a major outage.

- **Backups**: Regular backups of the database and storage are performed.
- **Restore Procedures**: Detailed procedures are in place for restoring the platform from backups.
- **Redundancy**: The platform is deployed across multiple availability zones to minimize downtime.

> **Note**: Specific details about backup frequency, retention policies, and recovery time objectives (RTOs) should be included here.

---

## 📚 Contributing & Standards

- Use PSR-12 code style and strict typing.
- Document all classes and methods with PHPDoc.
- Submit PRs with passing tests and code review.

---

## 📝 License

MIT

---
## API Example

Below is an example of how you might interact with the API to retrieve a list of devices for a specific tenant using `curl`.  This assumes you have a valid JWT.

```bash
curl -X GET \
  'https://tenant1.signagesaas.com/api/devices' \
  -H 'Authorization: Bearer <YOUR_JWT_TOKEN>' \
  -H 'Content-Type: application/json'
```

This command sends a `GET` request to the `/api/devices` endpoint for the tenant `tenant1`.  The `-H` flags set the necessary headers:

- `Authorization`: Includes the JWT for authentication.  Replace `<YOUR_JWT_TOKEN>` with the actual token.
- `Content-Type`: Specifies that the request body is in JSON format (though not needed for GET requests, it's good practice).

A successful response would return a JSON payload containing a list of devices associated with that tenant.  Error responses would include appropriate HTTP status codes and error messages in JSON format.
```

