# telkom-server

 Back-end server for telkom-juara client pwa, build with Lumen version 8.0

 *Read this in other languages: [English](https://github.com/diolan12/telkom-server), [Indonesia](https://github.com/diolan12/telkom-server/blob/main/README.id.md)*

## Requirements

- Web server Apache (recommended XAMPP)
- PHP (recommended version 7)
- Browser (e.g. Chrome, Firefox, Safari, Edge)
- Gitbash (you can get it [here](https://git-scm.com/downloads)) or Github Desktop 64bit ()[here](https://desktop.github.com/).
- Visual Studio Code (you can get it [here](https://code.visualstudio.com/Download)).
- Postman (you can get it [here](https://www.postman.com/downloads/)).

## Installation (windows)

Assuming that you already install the required tools above, let's just proceed with the installation.

1. Clone this repository.
2. Launch XAMPP as administrator.
3. Start the Apache server and Mysql service.
4. Open PhpMySql in your browser (<http://localhost/phpmyadmin>).
5. Create new database (e.g. `telkom-v1`).
6. Open the application directory in VSCode.
7. Copy paste file `.env.example`, then rename to `.env`
8. Open and edit `.env`, then make sure the server configuration is correct.
    - Change the database name with your database name in mysql.
    `DB_DATABASE=your_database_name`
    - Change mysql username (xampp default username is `root`)
    `DB_USERNAME=your_mysql_username`
    - Change mysql password (xampp default password is blank)
    `DB_PASSWORD=your_mysql_password`
9. Open Command Prompt, run as administrator to avoid any error.
10. Open the directory of this application in your Command Prompt.
11. Launch this installation, type `./install` in cmd then hit enter.
    If there is an error within the installation, check again repeat step number 8.
12. Done, it's finish but don't close the Command Prompt yet, we'll be launching the server.

## Lauch The Server (Windows)

1. From your Command Prompt, type `php -S your_ip4_address:your_port` (recommended use port 8080) then hit enter.
    Make sure to replace it with your machine IP4 Address and Port e.g. `php -S 192.168.1.5:8080`
2. Done, it's running. Now open your browser and go to `http://your_ip4_address:your_port`. You should see this text in your browser `Lumen (8.2.4) (Laravel Components ^8.0)`.
To stop the server, press Ctrl+C in your Command Prompt.

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

    example success login:

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

  example error login:

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
  
  **Return:**

    Returning multiple resources.

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
