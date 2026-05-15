# Event Management System

A comprehensive PHP and MySQL-based Event Management System that allows users to register for events, view schedules, and check participant lists.

## 📋 Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

- **Event Registration**: Users can easily register for upcoming events
- **Event Scheduling**: View comprehensive event schedules with dates and times
- **Participant Management**: Check participant lists for each event
- **User Authentication**: Secure login and registration system
- **Event Details**: Detailed information about each event including description, location, and capacity
- **Admin Panel**: Manage events, users, and registrations
- **Responsive Design**: User-friendly interface accessible on multiple devices

## 🛠 Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Web Server**: Apache/Nginx

## 📦 Prerequisites

Before you begin, ensure you have the following installed:
- PHP (version 7.4 or higher)
- MySQL (version 5.7 or higher)
- Apache or Nginx web server
- Composer (optional, for dependency management)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ArijeetSinha123/Event-Management-System.git
   cd Event-Management-System
   ```

2. **Create a database**
   ```sql
   CREATE DATABASE event_management;
   ```

3. **Import the database schema**
   ```bash
   mysql -u root -p event_management < database.sql
   ```

4. **Configure database connection**
   - Open `config.php` or the configuration file
   - Update database credentials (host, username, password, database name)

5. **Start the web server**
   ```bash
   php -S localhost:8000
   ```
   Or configure Apache/Nginx accordingly.

6. **Access the application**
   - Open your browser and navigate to `http://localhost:8000`

## 📖 Usage

### For Users
1. **Register**: Create a new account with your email and password
2. **Browse Events**: View available events on the dashboard
3. **Register for Events**: Click on an event and register as a participant
4. **View Schedule**: Check your registered events and their schedules
5. **View Participants**: See who else is attending the events you registered for

### For Administrators
1. **Login**: Access the admin panel with admin credentials
2. **Manage Events**: Create, edit, or delete events
3. **Manage Users**: View and manage user accounts
4. **View Registrations**: Monitor all event registrations
5. **Generate Reports**: Export participant lists and event data

## 📁 Project Structure

```
Event-Management-System/
├── index.php                # Homepage
├── config.php               # Database configuration
├── login.php                # User login page
├── register.php             # User registration page
├── dashboard.php            # User dashboard
├── admin/
│   ├── admin_dashboard.php  # Admin panel
│   ├── manage_events.php    # Event management
│   └── manage_users.php     # User management
├── css/
│   └── style.css            # Stylesheet
├── js/
│   └── script.js            # JavaScript functionality
├── includes/
│   ├── header.php           # Header component
│   ├── footer.php           # Footer component
│   └── db_connection.php    # Database connection
├── database.sql             # Database schema
└── README.md                # This file
```

## 🗄 Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Events Table
```sql
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    event_date DATE NOT NULL,
    event_time TIME,
    capacity INT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### Registrations Table
```sql
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('registered', 'cancelled') DEFAULT 'registered',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);
```

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open source and available under the MIT License.

---

**Author**: Arijeet Sinha & Argha Das
**Created**: October 18, 2025  
**Last Updated**: May 15, 2026

For more information or support, please open an issue on the [GitHub repository](https://github.com/ArijeetSinha123/Event-Management-System).
