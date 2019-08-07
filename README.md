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
 composer require admad/cakephp-jwt-auth
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

6. Create a user by accessing [http://localhost:8765/api/users/register](http://localhost:8765/api/users/register).

 #### cUrl
 
Below is an example cUrl API request.

```curl
curl -X POST -H 'Accept: application/json' -H 'Content-Type: application/x-www-form-urlencoded' -i http://localhost:8765/api/users/register --data 'name=Jefferson Vantuir&email=jeffersonbehling@gmail.com&password=test123'
```

If all goes well, you will get a return similar to this one below.

```json
{
    "success": true,
    "data": {
        "id": "76210f94-3b78-433f-a06c-2358df5f9f11",
        "message": "User has been registered.",
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI3NjIxMGY5NC0zYjc4LTQzM2YtYTA2Yy0yMzU4ZGY1ZjlmMTEiLCJleHAiOjE1NjU3OTE2MTR9.V930GtcvLvQu548UaHr8nY311aIHqaaugnJrQKbfkD4"
    }
}
```

## Pages

Below are all pages that you can access on this project.

#### Pages allowed without Token authentication

 - /api/users/register (POST)
 - /api/users/login (POST)
 - /api/articles (GET)
 
#### Pages allowed only with Token authentication
 
 - /api/users/edit (POST, PUT)
 - /api/articles (POST, PUT)