# psp-media
PSP Media coding task

# Installation:
1. Download code
2. Make sure you have installed Docker and Docker Compose
3. Open terminal, switch to the 'html' folder with downloaded application code and execute 'composer install'
4. Go up one level 'cd ../' and execute 'docker-compose up -d'
5. API endpoints will be available at 

    http://localhost:8080/index.php/api/customers
  
    http://localhost:8080/index.php/api/customers/[id]
    
    http://localhost:8080/index.php/api/deposit
    
    http://localhost:8080/index.php/api/withdrawal
    
    http://localhost:8080/index.php/api/report
  
Please note, database initialization may take up to 20 seconds.

# Notes:
1. Codeigniter has issues with PHPUnit usage for testing. One from the most popular libraries for tests (https://github.com/kenjis/ci-phpunit-test) still doesn't support PHP7, so I didn't use it for the task. My tests based on the built-in test class, you cas see results at http://localhost:8080/index.php/tests

2. In the ./misc/ folder you can find Postman collection I've used to check functionality, fill free to import it :)
