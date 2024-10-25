# Laravel Filament Quotation

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
![PHP Version](https://img.shields.io/badge/PHP-8.1-blue)
![Laravel Version](https://img.shields.io/badge/Laravel-10-orange)

This is a Laravel-based quotation project that uses the Filament for building modern and feature-rich dashboards.


## Requirements

- PHP >= 8.3
- Laravel >= 10
- Composer
- NPM


## Installation and Setup

Follow these steps to set up the project on your local machine:

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/muflifadla38/filament-quotation.git
   cd filament-quotation

2. **Install Dependencies:**
   ```bash
   composer install
   npm install

3. **Configure Vendor:** <br/>
   Copy folder ```backup/vendor``` to ```vendor```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate

5. **Run Database Migrations & Seeders:**
   ```bash
   php artisan migrate
   php artisan db:seed

6. **Start the Development Server:**
   ```bash
   npm run dev
   php artisan serve


## Usage
This dashboard core provides a foundation for generate quotation. You can extend it by adding your own components, modules, and features.


## Contributing
Contributions are welcome! If you have suggestions, bug reports, or want to contribute to this project, please create an issue or submit a pull request.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
