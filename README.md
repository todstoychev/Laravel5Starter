This project uses the Laravel 5 framework. Actually this is starter Laravel 5 project. It contains user managment system, including register, login, forgotten password, change password and other related functionalities. It also contains Basic admin panel and user roles implementation.
The project uses several modules:

1. [laracasts/flash](https://github.com/laracasts/flash) - module used for the flash message notifications
2. [fzaninotto/faker](https://github.com/fzaninotto/Faker) - used to generate dummy data
3. [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar) - used for the debug bar in development mode
4. [orchestra/imagine](https://github.com/orchestral/imagine) - module used to manipulate images. Used for the user avatar
5. [mmanos/laravel-search](https://github.com/mmanos/laravel-search) - used to provide full text search functionallity in the admin panel
6. [zendframework/zendsearch](https://github.com/zendframework/ZendSearch) - used from mmanos/laravel-search
7. [dimsav/laravel-translatable](https://github.com/dimsav/laravel-translatable) - used to translate models. It is necessary if you plan to create multilanguage application.

# Installation
First clone the project. 

## Cofiguration
Than you can create your .env file as it is in [Laravel 5 documentation](http://laravel.com/docs/master) or can use this sample:
    
    APP_ENV=local
    APP_DEBUG=true
    APP_KEY=your_key_here 

    DB_HOST=db_host
    DB_DATABASE=database_name
    DB_USERNAME=database_user
    DB_PASSWORD=database_password

    CACHE_DRIVER=file
    SESSION_DRIVER=file

    EMAIL_ADDRESS=application_email@domain.com
    EMAIL_PASSWORD=email_password

    PROVIDERS=Barryvdh\Debugbar\ServiceProvider

    ALIASES='Debugbar' => 'Barryvdh\Debugbar\Facade',

Put your database host, username and password. ```EMAIL_ADDRESS``` is the application mailing service address. ```EMAIL_PASSWORD``` is the password for the mailbox. ```PROVIDERS``` may contain your modules required for the current configuration. In this case the Bardryvdh/Debugbar is used only for local environment which is development mode. ```ALIASES``` is the same like ```PROVIDERS``` but here you can add your aliases for the modules.

For more details about the .env file, check [Laravel's documentation](http://laravel.com/docs/master) or just Google about it. There is a plenty of info out there.

## Run the migrations
First create your database and set the proper driver in the ```config/database.php``` file.
Use the Laravel's artisan console with the common commands to run the migrations. First cd to the project directory and depending from your OS run 
    
    php artisan migrate
    
## Add some dummy data
This project has seeders which provide the initial and some dummy data necessary for the project to run.
Use: 
    
    php artisan db:seed
    
to run the migrations.

## Your first login
Use 'admin' as username and 'admin' as password to enter the application. The 'admin' account has an administrator role so you have access to all application futures.
