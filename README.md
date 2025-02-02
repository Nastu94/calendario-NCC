# Calendario-NCC - Management System for Chauffeur-Driven Car Rental (NCC)

Calendario-NCC is a management system dedicated to companies offering **chauffeur-driven car rental** (NCC) services. The system allows the management of bookings, vehicle fleets, trip logs, and user roles with an intuitive interface and advanced features.

## Key Features

- **Booking Management**: Create, modify, and share bookings for transportation services.
- **Customizable Roles and Permissions**: Specific roles for administrators, companies, and drivers with granular permission management.
- **Company Management**: Each company can register information about their vehicles and drivers.
- **Trip Logs**: Track trips, kilometers traveled, and trip details.
- **Booking Sharing**: Bookings can be shared and accepted by other users in the system.
- **Calendar Integration**: View and manage bookings through FullCalendar.
- **Modern and Responsive Interface**: Designed to provide a smooth experience on both desktop and mobile devices.

## System Requirements

- **PHP >= 8.3.8**
- **Composer**
- **Laravel >= 11.x**
- **Node.js >= 22.x**
- **npm >= 10.x**
- **MySQL/MariaDB Database** or another Laravel-compatible database

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/Nastu94/calendario-NCC.git
   ```

2. Navigate to the project directory:
   ```bash
   cd Calendario-NCC
   ```

3. Install PHP dependencies using Composer:
   ```bash
   composer install
   ```

4. Install JavaScript dependencies using npm:
   ```bash
   npm install
   ```

5. Create the `.env` file by copying the example file:
   ```bash
   cp .env.example .env
   ```

6. Configure the database in the `.env` file:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=database_name
   DB_USERNAME=user_name
   DB_PASSWORD=password
   ```

7. Generate the application key:
   ```bash
   php artisan key:generate
   ```

8. Run the migrations to create the tables in the database:
   ```bash
   php artisan migrate
   ```

9. Start the local server:
   ```bash
   php artisan serve
   ```

## Future Features

- **Billing and Cost Management**: System for generating invoices and managing booking costs.
- **Google Maps Integration**: Vehicle geolocation, route details, and integrated navigation for drivers.
- **Payment Management**: Integration with payment platforms for managing booking fees.
- **Email and SMS Notifications**: Automatic alerts for customers and drivers.
- **Advanced Dashboard**: Reporting, company performance analysis, and trip statistics.

## Contributing

Currently, the project does not accept external contributions, but we are open to suggestions and ideas. If you wish to propose a new feature or improvement, feel free to contact the author via [GitHub](https://github.com/Nastu94).

## License

This project is owned by the author. Distribution, modification, and use of the code are subject to specific conditions set by the author. For further details, please refer to the [LICENSE](LICENSE) file included in the project.



