#  Ornamental-Plant-Order-Management-System

📌 Project Description

A comprehensive **web-based platform** designed to streamline the process of ordering, tracking, and managing ornamental plants.  
This system enhances customer convenience by enabling online browsing, ordering, **secure online payments**, and delivery tracking, while allowing administrators to efficiently manage plant inventory, orders, and customers.

## ✨ Features

### - Plant Management
   - Maintain detailed records of ornamental plant varieties
   - Update stock quantities and availability
   - Include descriptions, images, and prices for each plant
  
### - Ordering System
   - Browse available plants with category filtering
   - Add items to a shopping cart
   - Secure checkout process for order placement

### - Online Payment Integration
  - Secure payment flow for customer orders
  - Automatic payment confirmation updates in order records

### - Delivery Tracking
  - Assign and track delivery orders
  - Monitor order status from placement to completion
  - Generate delivery reports

### - Customer Management
  - User registration and secure login
  - Manage customer profiles and order history
  - Provide personalized order recommendations

### - Admin Dashboard
  - Manage plants, orders, and customers from a single interface
  - Generate sales and order reports
  - Monitor stock levels and restock alerts

###  - Search & Filter
  - Search plants by name or category
  - Filter by price range or plant type

### - Responsive Design
  - Fully functional across desktop, tablet, and mobile devices
    

## ⚙️ Installation Guide
   ### **Prerequisites**
  - XAMPP or any local server environment (Apache, MySQL)
  - Git (optional, for cloning the repository)
### 1. Clone the Repository
    -git clone https://github.com/yourusername/ornamental-plant-order-management.git
Alternatively, download the ZIP file and extract it.

### 2. Set Up the Database
   - Open phpMyAdmin
   - Create a new database named opoms_db
   - Import opoms_db.sql from the /database folder
     
### 3. Configure the Application
      $host = "localhost";           // Server host
      $username = "root";            // Database username
      $password = "";                // Database password
      $database = opoms_db"; // Database name
      
### 4. Deploy to Web Server
   - Copy the project folder to your web server's root directory
     For XAMPP: C:\xampp\htdocs\ornamental-plant-order-management

### 5. Start Services
   - Launch XAMPP Control Panel
   - Start Apache and MySQL services
     
### 6. Access the Application
       http://localhost/ornamental-plant-order-management

##  🛠️ Technologies Used
  - Backend: PHP
  - Frontend: HTML, CSS, Bootstrap, JavaScript
  - Database: MySQL
  - Tools: XAMPP, phpMyAdmin, PhpMailer

## 📂 Project Structure

      ornamental-plant-order-management/
      ├── assets/             # Static resources
      │   ├── css/            # Stylesheets
      │   ├── js/             # JavaScript files
      │   └── images/         # Plant images
      ├── includes/           # Configuration and helper files
      ├── pages/              # User-facing pages
      ├── customer/           # Customer panel
      ├── admin/              # Admin panel
      ├── store_keeper/       # Store keeper panel
      ├── delivery_driver/    # Delivery driver panel
      ├── payment/            # Payment gateway integration files
      ├── database/           # SQL dump file
      ├── config.php          # Database configuration
      └── index.php           # Entry point
## System Preview
![Shop Overview](https://github.com/MGHirushiThilakna/Ornamental-Plant-Order-Management-System/blob/main/Screenshots%20of%20the%20system/plant%20catalog.png)

![Item Overview](https://github.com/MGHirushiThilakna/Ornamental-Plant-Order-Management-System/blob/main/Screenshots%20of%20the%20system/item%20view.png)

![Checkout Overview](https://github.com/MGHirushiThilakna/Ornamental-Plant-Order-Management-System/blob/main/Screenshots%20of%20the%20system/check%20out.png)

## Future Enhancements
  - AI-based plant recommendation system
  - Multi-language support
  - Advanced sales analytics dashboard
  - Customer reviews and ratings for plants

## 📝 License
  - This project is licensed under the MIT License – see the LICENSE.md file for details.

## 👥 Contributing
   1.  Fork the project
   2.  Create your feature branch
   
           git checkout -b feature/AmazingFeature
   3. Commit your changes
      
          git push origin feature/AmazingFeature
   4. Push to the branch
   5. Open a Pull Request
