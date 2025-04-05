
Built by https://www.blackbox.ai

---

```markdown
# Hotel Management System

## Project Overview
The Hotel Management System is a web application designed to manage room bookings in a hotel. It allows users to view available rooms, reserve them, and provides an administrative panel for managing the room status and reservations. The application is built in PHP and utilizes MySQL for data management.

## Installation
To set up the Hotel Management System locally, follow these steps:

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/hotel-management-system.git
   ```

2. **Navigate to the Project Directory**
   ```bash
   cd hotel-management-system
   ```

3. **Create a Database**
   Set up a MySQL database by running the SQL commands in the `setup_database.php` file after configuring the database connection in `config.php` with your server details.

4. **Configure Database Credentials**
   Edit the `config.php` file to set your database credentials:
   ```php
   $host = 'localhost'; // Your database host
   $dbname = 'your_database_name'; // Your database name
   $username = 'your_database_username'; // Your database username
   $password = 'your_database_password'; // Your database password
   ```

5. **Execute Database Setup**
   Open your browser and navigate to `setup_database.php` to create the required tables.

6. **Run the Application**
   Start a local server (using tools like XAMPP or WAMP) and navigate to `http://localhost/hotel-management-system/index.php`.

## Usage
- **View Rooms**: Upon accessing the application, users can view a map of available rooms along with their status (available, occupied, maintenance).
- **Reserve Room**: By selecting a room, users can complete a form to reserve the room for specified dates.
- **Admin Panel**: Admins can manage room registrations, view and update reservations, and perform check-ins and check-outs.

## Features
- Responsive user interface with Tailwind CSS.
- Room availability filter by floor.
- Administrative functionalities to add, update, and manage room reservations.
- Basic validation for reservation dates to avoid double bookings.

## Dependencies
The project does not contain a `package.json` file, as it is a PHP application without specific JavaScript libraries required for frontend functionality other than external scripts sourced through CDN.

## Project Structure
The project is organized into the following file structure:

```
hotel-management-system/
│
├── admin.php                 // Admin panel for managing reservations
├── cadastrar_quarto.php      // Page to register new rooms
├── config.php                // Database configuration
├── footer.php                // Standard HTML footer
├── header.php                // Standard HTML header
├── index.php                 // Main page showing room availability
├── phpinfo.php              // PHP Info page (for debugging purposes)
├── reservar.php              // Room reservation page
└── setup_database.php        // Script to set up the database and tables
```

## Conclusion
This Hotel Management System is a simple yet robust solution for managing hotel operations. It offers a user-friendly experience with essential features for both guests and administrative staff. Feel free to contribute and enhance the functionality further!
```