# CakePHP with JWT authentication

Project developed using CakePHP framework in version 3.8. * in conjunction with JWT plugin.

The project aims to show how the JWT plugin is implemented so that it can be used in API's.

The project consists of:
 - User Registration and Editing.
 
 - Token Generation User Authentication.
 
 - Token usage to access the rest of the content.

### Used Plugins

 - [CakePHP JWT Auth](https://github.com/ADmad/cakephp-jwt-auth)
 ```bash
 composer require friendsofcake/crud
 ```
 
 - [Crud](https://github.com/FriendsOfCake/crud)
 ```bash
 composer require friendsofcake/crud
 ```
 
 - [Firebase JWT](https://github.com/firebase/php-jwt)
 ```bash
 composer require firebase/php-jwt
 ```

## For testing the project

1. Clone the project with command line `git clone https://github.com/jeffersonbehling/cakephp-jwt.git`.

2. Run `composer install`.

3. Create your database and configure `config/app.php` file.

4. Execute the migrations with command line `bin/cake migrations:migrate`.

5. Start the server with `bin/cake server -p 8765` and access the browser [http://localhost:8765](http://localhost:8765).
