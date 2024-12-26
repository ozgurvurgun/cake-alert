# 🎂 Cake Alert - Birthday Reminder System

Cake Alert is a birthday reminder system that notifies team members about upcoming birthdays. It sends email notifications to the entire team, ensuring no one's birthday goes unnoticed!

## 🚀 Features

- Sends email notifications with customizable content to all team members.
- It sends a notification on your birthday and as many days before your birthday as you choose.
- Uses **PHP** with a clean, layered architecture and some **Domain-Driven Design (DDD)** principles.

## 🛠️ Stack

- **Language**: PHP 8+
- **Email Integration**: PHPMailer
- **Database**: MySQL
- **Dependency Management**: Composer
- **Architecture**: Layered Architecture with DDD elements

## 📋 Prerequisites
Make sure you have the following installed:

- PHP 8.0 or higher
- Composer
- MySQL
- SMTP server credentials (for email notifications)

## ⚙️ Setup Instructions

1. **Clone the Repository**
```bash
git clone https://github.com/your-username/cake-alert.git
```

2. **Go to Project Directory**
```bash
cd cake-alert
```

3. **Install Dependencies**
```bash
composer install
```

4. **Create a .env file in the root directory and configure the following variables**
```bash
DB_HOST=127.0.0.1
DB_NAME=birthday_notifier
DB_USER=root
DB_PASSWORD=

MAIL_HOST=smtp.gmail.com
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-email-password
MAIL_PORT=587
MAIL_FROM=your-email@gmail.com
MAIL_FROM_NAME="Cake Alert"

DEFAULT_NOTIFICATION_DAYS=2
```

5. **Run Database Migration**
- ***Linux, MacOS, CMD:*** Use the `<` operator for input redirection.
```bash
mysql -u root -p birthday_notifier < migration.sql
```
- ***PowerShell:*** PowerShell does not support the < operator. Use type to pipe the content of the SQL file to MySQL.
```bash
type migration.sql | mysql -u root -p birthday_notifier
```

**Start the Application**
```bash
php public/index.php
```