# Zapix MIS - Management Information System

A modern Laravel 12 application built with Livewire, Volt, and Flux UI Pro.

## Tech Stack

### Backend
- **PHP 8.2+** with **Laravel 12.0**
- **Livewire 3** with **Volt** (single-file components)
- **Flux UI Pro 2.2** - Premium UI component library
- **SQLite** database (configurable)

### Frontend
- **Tailwind CSS 4.0** with Vite integration
- **Vite 7.0** as the build tool
- **Axios** for HTTP requests
- **Alpine.js** (via Livewire)

### Development Tools
- **Pest PHP 3.8** - Modern testing framework
- **Laravel Pint** - Code style fixer
- **Larastan/PHPStan** - Static analysis (Level 6)
- **Laravel Pail** - Real-time log viewer
- **Laravel Sail** - Docker environment

## Architecture Overview

### Project Structure
```
zapix-mis/
├── app/
│   ├── Http/Controllers/     # Traditional controllers
│   ├── Livewire/            # Livewire components
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── resources/
│   ├── css/                 # Tailwind CSS
│   ├── js/                  # JavaScript assets
│   └── views/
│       ├── components/      # Blade components
│       ├── livewire/        # Volt single-file components
│       └── flux/            # Custom Flux UI icons
├── routes/                  # Application routing
├── database/               # Migrations, factories, seeders
├── tests/                  # Pest tests
└── config/                 # Configuration files
```

### Key Features
- **Authentication System** - Complete auth scaffolding with registration, login, password reset, and email verification
- **User Management** - Profile management, settings, and account controls
- **Component-Based UI** - Livewire Volt components with Flux UI Pro
- **Dark Mode Support** - Built-in theme switching
- **Responsive Design** - Mobile-first approach with adaptive layouts

## Code Style Rules (Pint)

This project uses Laravel Pint with the PER preset and additional custom rules:

### Configuration
```json
{
    "preset": "per",
    "rules": {
        "phpdoc_align": false,
        "new_with_parentheses": true,
        "not_operator_with_successor_space": false,
        "simplified_null_return": false,
        "trailing_comma_in_multiline": true,
        "declare_strict_types": true,
        "align_multiline_comment": true,
        "array_indentation": true,
        "blank_line_after_namespace": true,
        "combine_consecutive_issets": true,
        "combine_consecutive_unsets": true,
        "concat_space": {
            "spacing": "one"
        },
        "ordered_imports": {
            "sort_algorithm": "alpha",
            "imports_order": ["const", "class", "function"]
        },
        "declare_parentheses": true,
        "explicit_string_variable": true,
        "fully_qualified_strict_types": true
    }
}
```

### Key Style Rules Explained

1. **Strict Types** - All PHP files must declare `declare(strict_types=1);`
2. **Import Ordering** - Imports are alphabetically sorted and grouped by type (const, class, function)
3. **Trailing Commas** - Required in multi-line arrays, function calls, etc.
4. **Concatenation Spacing** - One space around concatenation operators
5. **Array Indentation** - Consistent indentation for multi-line arrays
6. **Parentheses** - Required with `new` statements and `declare` statements
7. **Comment Alignment** - Multi-line comments are properly aligned

### Running Code Style Checks

```bash
# Fix code style issues
composer lint-fix

# Analyze code with PHPStan
composer analyse

# Run all tests
composer test
```

## Getting Started

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (or your preferred database)

### Installation

1. Clone the repository
```bash
git clone <repository-url>
cd zapix-mis
```

2. Install PHP dependencies
```bash
composer install
```

3. Install NPM dependencies
```bash
npm install
```

4. Copy environment file and configure
```bash
cp .env.example .env
php artisan key:generate
```

5. Run database migrations
```bash
php artisan migrate
```

6. Start development servers
```bash
composer run dev
```

This command runs:
- Vite development server (with hot reload)
- Laravel development server
- Queue worker
- Real-time log viewer (Pail)

## Development Workflow

### Available Commands

```bash
# Development
composer run dev          # Start all development services
npm run dev              # Start Vite dev server only
php artisan serve        # Start Laravel server only

# Testing & Quality
composer test            # Run Pest tests
composer analyse         # Run PHPStan analysis
composer lint-fix        # Fix code style with Pint

# Queue & Logs
php artisan queue:listen # Start queue worker
php artisan pail        # View logs in real-time
```

### Testing

Tests are written using Pest PHP. Run tests with:

```bash
composer test
```

Tests are located in the `tests/` directory with feature and unit test subdivisions.

### Static Analysis

The project uses PHPStan at level 6 for static analysis:

```bash
composer analyse
```

## Security Considerations

- CSRF protection enabled
- Session encryption available
- Password hashing with bcrypt (12 rounds)
- Email verification support
- Route throttling on sensitive endpoints
- Environment-based configuration for secrets

## License

[License information here]