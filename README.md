<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

This repo is just a beginning for something great — PRs and issues are welcome.

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/9.x/installation)

Clone the repository

    git clone https://github.com/rhoelcartojano/systems-analyst-exam.git
    
Switch to the repo folder

    cd systems-analyst-exam

Install the PHP dependencies

    composer install
    
Install node modules dependencies

    npm install
  
Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate
    
Open your IDE, in this case Visual Studio Code

    code .
    
Run the database migrations and seeders (**Set the database connection in .env before migrating**)

    php artisan migrate --seed
    
Run vite command to build asset files (**Do not close the termninal used to run this command, it is recommended to use terminals in vscode**)

    npm run dev

Start the local development server (**Run in new terminal**)

    php artisan serve

You can now access the server at http://localhost:8000


**TL;DR command list**

    git clone https://github.com/rhoelcartojano/systems-analyst-exam.git
    cd systems-analyst-exam
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    code .
    npm run dev
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate --seed
    php artisan serve
    
***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:fresh --seed
    
***Login*** : After successfully migrating and seeding the database, an admin credential is ready to be used for login
***Go to: http://127.0.0.1:8000/admin/login***

    Username / Email: test@example.com
    password: 12345678
    
    
## Folders

- `app` - Contains all the Eloquent models
- `app/Http/Controllers/Admin` - Contains all the admin CRUD controllers
- `app/Http/Middleware` - Contains the web auth middleware
- `config` - Contains all the application configuration files
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the web routes defined in web.php file
- `resources/views/admin` - Contains all the admin layouts and design
- `resources/views/employee` - Contains all the employee views
- `public/admin` - Contains AJAX and JavaScript codes
