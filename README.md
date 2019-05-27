# CoolFrogs

## How to run the project

code was tested under PHP 7.2 but should work on php 7.0+

**INSTALLATION STEPS**

1. Clone the repo and ``cd into the project folder``
2. run ```composer install```
2. Copy .env.example ```cp .env.example .env``` and update database configs
3. Import ```database.sql```
4. Run ``` php -S localhost:9002 -t public``` and open  http://localhost:9002

### If you plan to use apache/nginx to run the app you should point document root to /public


