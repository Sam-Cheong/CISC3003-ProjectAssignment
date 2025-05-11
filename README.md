# Course Enrollment System

Hi Everyone, welcome to our Course Enrollment System.

To use the system, you need to pay attention to the instructions below:

1. Configuration of .env file:
    - Create a .env file (The "." before "env" is reserved) under the root directory
    - Add some environment variable names as follow:
        1. DB_HOST = YOUR_DATABASE_HOST
        2. DB_USER = YOUR_DATABASE_USERNAME
        3. DB_PASSWORD = YOUR_DATABASE_PASSWORD
        4. DB_NAME = YOUR_DATABASE_NAME (Not the same as DB_USER)
        5. DB_CHARSET = utf8mb4 (Requirement)
        5. DB_CHARSET = utf8mb4
        6. MAIL_HOST = smtp.google.com (Send to Gmail Inbox)
        7. MAIL_USER = YOUR_GMAIL_ACCOUNT
        8. MAIL_PASS = YOUR_GMAIL_APPLICATION_PASSWORD (Not the Gmail password that you login)
        9. MAIL_PORT = 465 (For the security)
    - Further sensitive variables will be added in .env

2. Deploy the system to your localhost
    - Prepare the XAMPP Integrated Pack. If you have not heard about this, [click here to download and install XAMPP](https://www.apachefriends.org/download.html), select your corresponding operating system, it will start to download.
    - After installation, move the whole project directory to "path/to/xampp/htdocs/" which may have something there already, but just ignore those files.
    - Open XAMPP you can find/search in the start menu (Windows)
    - Start the Apache Server and MySQL Server
    - Open **[schema.sql](./schema.sql)** in the root of the directory
    - Visit the [MySQL Database Management Dashboard](http://localhost/phpmyadmin/)
    - Create new database on the left side bar, the database name is same with DB_NAME in .env
    - Copy all the SQL statements in schema.sql and paste to the SQL options on the navigate bar on the top
    - Execute the SQL statements
    - Finally, Start using the system from http://localhost/CISC3003-ProjectAssignment/