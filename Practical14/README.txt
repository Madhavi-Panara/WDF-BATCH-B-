WDF PRACTICAL 14 - STUDENTHUB ADMIN DASHBOARD

1. Copy the Practical14 folder to:
   D:\WDF PRACTICALS\Practical14

2. Ensure MySQL is running before testing. Do not reset or replace your existing database.

3. Review setup.sql. It creates only users and audit_logs if they do not already exist.
   Import it in phpMyAdmin after selecting the studenthub database.

4. Create an admin password hash:
   Open Command Prompt and run:
   cd /d C:\xampp\php
   php -r "echo password_hash('Admin@123', PASSWORD_DEFAULT), PHP_EOL;"
   Copy the resulting hash.

5. In phpMyAdmin, select studenthub > SQL and run the following, replacing PASTE_HASH_HERE with the hash:
   INSERT INTO users (username, password, role, status)
   VALUES ('admin', 'PASTE_HASH_HERE', 'admin', 'active');

   If an admin user already exists, do not insert a duplicate; use the existing account or update it carefully.

6. Open:
   http://localhost:8080/wdf/Practical14/index.php

   Login:
   Username: admin
   Password: Admin@123

7. Security note: change the demo password before real use. This is a college practical demo, not a production authentication system.

8. Schema note:
   dashboard.php and registrations.php currently assume registration has:
   registration_id, student_id, event_id
   and students has student_id, name.
   If your existing tables use different column names, adjust those SELECT queries to match phpMyAdmin.
