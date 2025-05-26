# SignageSaaS

A multi-tenant SaaS platform for digital signage management, built with Laravel 12, PHP 8.3+, Livewire 3, Tailwind CSS, and stancl/tenancy. Designed for scalable, secure, and accessible management of devices, screens, and content across isolated tenant environments.

---

## 🚀 Project Overview

- **Multi-tenant**: Each customer (tenant) has their own subdomain and isolated data.
- **Device, Screen, and Content Management**: Manage digital signage devices, organize screens, and schedule content.
- **Livewire-First**: All interactive features use Livewire v3; Alpine.js for client-side enhancements.
- **AI Integration**: (Optional) Content generation and analytics via LangChain PHP.
- **Modern UI**: Utility-first Tailwind CSS, dark/light mode, accessible and responsive.
- **Secure**: RBAC, JWT for APIs, tenant isolation at all layers.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.3+, Laravel 12, stancl/tenancy, Livewire 3
- **Frontend**: Tailwind CSS 4, Alpine.js, Vite
- **Database**: MySQL 8 (Dockerized)
- **Containerization**: Docker, docker-compose
- **Testing**: PHPUnit, Laravel Dusk
- **Other**: Redis (optional), Laravel Horizon (optional)

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
# (Edit .env as needed)
docker-compose up --build -d
```

- App: http://localhost
- phpMyAdmin: http://localhost:8080

### Manual Setup (Local)

```bash
composer install
npm install
cp .env.example .env
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

- **Device Manager**: Add, edit, filter, and manage devices
- **Screen Manager**: Organize screens per device
- **Content Manager**: Schedule and assign content to screens
- **Multi-Tenant Isolation**: All data and assets are tenant-scoped
- **AI Integration**: (Optional) Content generation and analytics

#### Example: Device Manager UI

See [`resources/views/livewire/devices/device-manager.blade.php`](resources/views/livewire/devices/device-manager.blade.php) for a full-featured device management interface.

---

## 📚 Contributing & Standards

- Use PSR-12 code style and strict typing
- Document all classes and methods with PHPDoc
- Submit PRs with passing tests and code review

---

## 📝 License

MIT

---