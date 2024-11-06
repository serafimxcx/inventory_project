# simple_inventory_project

Welcome to the **Simple Inventory Project** repository! This project is a web-based application for managing inventory, sales, suppliers, and other aspects of warehouse management. It is built using **PHP**, **JavaScript**, **MySQL**, **jQuery**, **HTML**, **CSS**, and **Bootstrap**, offering a user-friendly interface for efficient business operations.

## Project Overview

This project consists of several modules, each providing essential functionality to help manage an inventory system effectively.

### Modules

1. **Dashboard**
   - Displays a comprehensive overview of key data:
     - Minimal stock items that need replenishment.
     - Pending supplies from suppliers.
     - The best-selling product.
   - The dashboard serves as a quick summary for monitoring business performance.

2. **Manage Products**
   - Allows users to manage products in different categories:
     - Add, edit, and update product details.
     - Create and modify categories.
     - Delete products or categories when needed.

3. **Manage Suppliers**
   - Provides the ability to manage supplier information:
     - Add new suppliers.
     - Manage orders and track their status.
     - Handle product returns and supplier communications.

4. **Manage Sales**
   - A module for recording product sales:
     - Add products sold, indicating the quantity and order number.
     - Record customer information associated with each sale.

5. **Manage Wastages**
   - Allows users to track and manage inventory wastages:
     - Add items to be discarded, along with the quantity.
     - Provide reasons for wastages to maintain accurate records.

6. **Manage Warehouse**
   - Manage warehouse locations and products:
     - Add, update, and organize warehouse details.
     - Track products stored in each warehouse and manage inventory levels.

7. **View Reports**
   - Generate detailed reports:
     - Stocks, sales, and supplier reports.
     - Generate PDF reports for easy export and sharing.

8. **Manage Users**
   - User management module:
     - Add new users and define roles (Staff, Manager, Admin).
     - Control access levels based on the user’s role.

## Technologies Used

- **PHP**: Server-side scripting language for backend logic.
- **MySQL**: Database management system for storing inventory, sales, and user data.
- **JavaScript & jQuery**: For interactive UI components and asynchronous operations.
- **HTML & CSS**: For structuring and styling the web pages.
- **Bootstrap**: Front-end framework to create responsive and modern layouts.

## Getting Started

To run this project locally, follow these steps:

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/simple_inventory_project.git
   ```

2. **Set up the database**:
   - Import the provided SQL file to create the necessary database and tables for the application.
   - Configure the database connection in the `config.php` file with your MySQL database credentials.

3. **Install dependencies**:
   - Ensure you have a local web server (such as XAMPP, WAMP, or LAMP) to run PHP and MySQL.
   - Make sure jQuery and Bootstrap are linked correctly in the project. If necessary, install via CDN.

4. **Navigate to the project folder**:
   ```bash
   cd simple_inventory_project
   ```

5. **Access the project**:
   - Open the project in your web browser using the local server URL (e.g., `http://localhost/simple_inventory_project`).

6. **Log in**:
   - Use the default admin credentials (or create a new user through the `Manage Users` module) to log into the system.


## Features

- **Responsive UI**: Built using **Bootstrap**, ensuring the app looks great on both desktop and mobile devices.
- **User Access Control**: Different roles (Admin, Manager, Staff) with distinct permissions and access levels.
- **Report Generation**: Ability to generate PDF reports for inventory, sales, and suppliers.
- **Real-time Updates**: Use of **jQuery** and **JavaScript** for dynamic and seamless interaction with the application.
- **MySQL Database**: Data for products, sales, suppliers, and other modules is stored in a MySQL database, ensuring efficient data handling.

## Contributing

Contributions are welcome! If you’d like to improve or extend the project, feel free to fork the repository, make changes, and submit a pull request.

1. Fork the repository.
2. Make your changes in a new branch.
3. Submit a pull request with a detailed description of your changes.

---

This **Simple Inventory Project** serves as a robust solution for small businesses to manage their inventory, suppliers, sales, and users effectively. With user management, data tracking, and reporting features, this application provides a complete inventory management system.
