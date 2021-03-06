# telkom-server

 Back-end server for telkom-juara client PWA, build with Lumen version 8.0

 *Read this in other languages: [English](https://github.com/diolan12/telkom-server), [Indonesia](https://github.com/diolan12/telkom-server/blob/main/README.id.md)*

## Requirements

- Web server Apache (recommended XAMPP)
- PHP (recommended version 7)
- Browser (e.g. Chrome, Firefox, Safari, Edge)
- Gitbash (download [here](https://git-scm.com/downloads)) or Github Desktop 64bit (download [here](https://desktop.github.com/)).
- Visual Studio Code (download [here](https://code.visualstudio.com/Download)).
- Postman (download [here](https://www.postman.com/downloads/)).

## Installation (Windows)

Assuming that you already install the required tools above, let's just proceed with the installation.

1. Clone this repository.
2. Launch XAMPP as administrator.
3. Start the Apache server and Mysql service.
4. Open PhpMySql in your browser (<http://localhost/phpmyadmin>).
5. Create new database (e.g. `telkom-v1`).
6. Open the application directory in VSCode.
7. Copy paste file `.env.example`, then rename to `.env`
8. Open and edit `.env`, then make sure the server configuration is correct.
    - Change the database name with your newly created database name in mysql.
    `DB_DATABASE=your_database_name`
    - Change mysql username (XAMPP default username is `root`)
    `DB_USERNAME=your_mysql_username`
    - Change mysql password (XAMPP default password is blank)
    `DB_PASSWORD=your_mysql_password`
9. Open Command Prompt, run as administrator to avoid any error.
10. Open the directory of this application in your Command Prompt.
11. Launch this installation, type `./install` in cmd then hit enter.
    If there is an error within the installation, check again step number 8.
12. Done, it's finish but don't close the Command Prompt yet, we'll be launching the server.

## Launch The Server (Windows)

1. From your Command Prompt, type `php -S your_ip4_address:your_port` (recommended use port 8080) then hit enter.
    Make sure to replace it with your machine IP4 Address and Port e.g. `php -S 192.168.1.5:8080`
2. Done, it's running. Now open your browser and go to `http://your_ip4_address:your_port`. You should see this text in your browser `Lumen (8.2.4) (Laravel Components ^8.0)`.
To stop the server, press Ctrl+C in your Command Prompt.

## Updating The Server

   1. Open Command Prompt on project root directory then run this command.

      ```bat
      git pull
      ```

   2. Server has been updated.

## Documentation

There are 2 main routes available in this server, `/auth` and `/api`. `/auth` is responsible for authenticating request for using this server. And the `/api` is responsible for handling the resource data within the server, to access this route you need to pass the 'Authorization' header in your request.

### Routes Map

- **`POST`** `/auth/login`
- **`GET`** `/auth/verify`
- **`GET`** `/api/{resource_name}`
- **`GET`** `/api/{resource_name}/{id}`
- **`POST`** `/api/{resource_name}`
- **`PUT`** `/api/{resource_name}/{id}`
- **`DELETE`** `/api/{resource_name}/{id}`

### Routes

#### Auth

- **`POST`** `/auth/login`
  
  Handling the login for getting JWT token.

    **Require body:**
  - nik
  - password

  **Return:**

    JWT token for successful login and returning error message upon login error.

  example success login:

    ```json
    {
        "type": "SUCCESS",
        "message": "some_jwt_token"
    }
    ```

    example error login:

    ```json
    {
        "type": "ERROR",
        "message": "some_error_message"
    }
    ```

- **`GET`** `/auth/verify`

    Verifying JWT token validity.

    **Require header:**
  - Authorization: Bearer {jwt_token}

  **Return:**

    Returning decoded jwt token, otherwise will returning error upon malformed JWT token.

    example success verify:

    ```json
    {
        "type": "SUCCESS",
        "message": {
            "iss": "server_address",
            "sub": "Authorization",
            "aud": "account_id",
            "iat": "2021-10-06T17:05:56.542528Z",
            "jti": "account_id",
            "name": "John Doe",
            "gender": "male",
            "picture": "default.jpg",
            "email": "john.doe@mail.com",
            "phone_number": "08123456789",
            "admin": 0
        }
    }
    ```

  example error verify:

    ```json
    {
        "type": "ERROR",
        "message": "JWT Malformed Error"
    }
    ```

#### API

- **`GET`** `/api/{resource_name}`

    Getting all resource.

    **Require header:**
  - Authorization: Bearer {jwt_token}

  **Params (Optional):**
  - `limitOffset`: {limit}-{offset}

  This will limit the data at given offset, param value separated by dash.

  - `orderBy`: {column}-{ASC/DESC}

  This will ordering data ascending or descending by given column name value, param value separated by dash.

  - `where`: {column}-{condition}-{value};

  This is where clause parameter, param value separated by dash. Support multiple where clauses separated by semicolon.
  List of conditions are as follows:
  - `is` ( = )
  - `<`
  - `>`
  - `<is` ( <= )
  - `>is` ( >= )
  - `<>`
  - `!is` ( != )
  - `LIKE`
  - `NOT`
  - `BETWEEN`
  
  List of value decorators for condition LIKE are as follows:
  - `a%`     Finds any values that start with "a"
  - `%a`     Finds any values that end with "a"
  - `%or%` Finds any values that have "or" in any position
  - `_r%`     Finds any values that have "r" in the second position
  - `a_%`     Finds any values that start with "a" and are at least 2 characters in length
  - `a__%` Finds any values that start with "a" and are at least 3 characters in length
  - `a%o`     Finds any values that start with "a" and ends with "o"
  
  **Return:**

    Returning array of resources.

- **`GET`** `/api/{resource_name}/{id}`

    Getting resource based on resource ID.
  
    **Require header:**
  - Authorization: Bearer {jwt_token}

  **Return:**

    Returning single resource.

- **`POST`** `/api/{resource_name}`

    Create new resource.
  
    **Require header:**
  - Authorization: Bearer {jwt_token}

  **Require body:**
  - {resource_body}

  **Return:**

    Returning newly created single resource.

- **`PUT`** `/api/{resource_name}/{id}`

    Updating resource based on the resource ID.
  
    **Require header:**
  - Authorization: Bearer {jwt_token}

  **Require body:**
  - {resource_body}
  
  **Return:**

    Returning current updated resource.

- **`DELETE`** `/api/{resource_name}/{id}`
  
    **Require header:**
  - Authorization: Bearer {jwt_token}
  
  **Return:**

    Returning multiple resources.
