This project uses the Laravel 5 framework. Actually this is starter Laravel 5 project. It contains user managment system, including register, login, forgotten password, change password and other related functionalities. It also contains Basic admin panel and user roles implementation.
The project uses several modules:

1. [laracasts/flash](https://github.com/laracasts/flash) - module used for the flash message notifications
2. [fzaninotto/faker](https://github.com/fzaninotto/Faker) - used to generate dummy data
3. [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar) - used for the debug bar in development mode
4. [orchestra/imagine](https://github.com/orchestral/imagine) - module used to manipulate images. Used for the user avatar
5. [mmanos/laravel-search](https://github.com/mmanos/laravel-search) - used to provide full text search functionallity in the admin panel
6. [zendframework/zendsearch](https://github.com/zendframework/ZendSearch) - used from mmanos/laravel-search
7. [dimsav/laravel-translatable](https://github.com/dimsav/laravel-translatable) - used to translate models. It is necessary if you plan to create multilanguage application.

The project contains also several frontend plugins:

1. [Bootstrap 3](http://getbootstrap.com/) - can be found in ```public/bs/```
2. [jQuery 1.11.1](https://jquery.com/) - can be found in ```public/js/jquery.js```
3. [Select2 3.5.2 with Bootstrap compitability](http://select2.github.io/select2/) - can be found in ```public/select2/```
4. [Lightbox 2.7.1](http://lokeshdhakar.com/projects/lightbox2/) - can be found in ```public/lightbox/```
5. [FontAwesome 4.3.0](http://fortawesome.github.io/Font-Awesome/) - can be found in ```public/fa/```

In the ```libs/``` directory there are some custom libraries written by me. Those are ICR - The Image Crop Resizer library used to manipulate images through the Imagine bundle. And the TableSorter, which provides an methods for table columns sorting. You can find exapmles for the usage of both libraries in the code itself. 
For the ICR you can check the ```putChangeAvatar()``` method in the ```app/Http/Controllers/UserController.php```. 
For TableSorter check some of the templates named ```all.blade.php``` and ```search.blade.php``` in ```resources/views/admin/users/``` or in the ```resources/views/admin/roles/```. Check the related methods in the controllers for some hints. Such methods are ```getAll()```, ```getSearch()``` and ```postSearch()``` in ```app/Http/Controllers/Admin/AdminUsersController.php```. 

# Installation
First clone the project. Than run
    
    composer update
    
Depending on your OS this command may be in different format.

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

Put your database host, username and password. ```EMAIL_ADDRESS``` is the application mailing service address. ```EMAIL_PASSWORD``` is the password for the mailbox. I am using this way of configuration due to the mail.php config file commit. I do not want to distribute my email and password ;).

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
