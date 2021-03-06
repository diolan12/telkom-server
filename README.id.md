# telkom-server

 Server back-end untuk PWA klien telkom-juara, dibangun dengan Lumen versi 8.0

 *Baca dalam bahasa lain: [English](https://github.com/diolan12/telkom-server), [Indonesia](https://github.com/diolan12/telkom-server/blob/main/README.id.md)*

## Persyaratan

- Web server Apache (rekomendasi XAMPP)
- PHP (rekomendasi version 7)
- Peramban (mis. Chrome, Firefox, Safari, Edge)
- Gitbash (download [disini](https://git-scm.com/downloads)) atau Github Desktop 64bit ()[disini](https://desktop.github.com/).
- Visual Studio Code (download [disini](https://code.visualstudio.com/Download)).
- Postman (download [disini](https://www.postman.com/downloads/)).

## Instalasi (Windows)

Dengan asumsi Anda sudah menginstal alat-alat yang diperlukan di atas, mari kita lanjutkan dengan instalasi.

1. Clone repositori ini.
2. Buka XAMPP sebagai administrator.
3. Klik start Apache server dan Mysql.
4. Buka PhpMySql di peramban anda (<http://localhost/phpmyadmin>).
5. Buat database baru (e.g. `telkom-v1`).
6. Buka direktori aplikasi di VSCode.
7. Copy paste file `.env.example`, lalu ubah nama menjadi `.env`
8. Buka and ubah `.env`, lalu pastikan konfigurasi server benar.
    - Ubah nama database dengan database yang baru anda buat di mysql.
    `DB_DATABASE=nama_database_anda`
    - Ubah mysql username (username bawaan XAMPP `root`)
    `DB_USERNAME=username_mysql_anda`
    - Uabh password mysql (password bawaan XAMPP kosong)
    `DB_PASSWORD=password_mysql_anda`
9. Buka Command Prompt, jalankan sebagai administrator untuk menghindari error.
10. Buka directori aplikasi ini di Command Prompt anda.
11. Jalankan instalasi, ketik `./install` di cmd lalu tekan enter.
    Jika ada error dalam instalasi, periksa kembali langkah nomer 8.
12. Beres, proses sudah selesai tapi jangan tutup Command Prompt dulu, kita akan menjalankan servernya.

## Menjalankan Server (Windows)

1. Dari Command Prompt, ketik `php -S ip4_address_anda:port_anda` (rekomendasi gunakan port 8080) lalu tekan enter.
    Pastikan untuk mengganti dengan IP4 Address dan Port anda misal `php -S 192.168.1.5:8080`
2. Beres, sudah berjalan. Sekarang buka peramban anda dan menuju ke `http://ip4_address_anda:port_anda`. Anda harusnya melihat tulisan ini di peramban anda `Lumen (8.2.4) (Laravel Components ^8.0)`.
Untuk menghentikan server, tekan Ctrl+C di Command Prompt anda.

## Memperbarui Server

   1. Buka Command Prompt pada folder project lalu jalankan perintah ini.

      ```bat
      git pull
      ```

   2. Server telah diperbarui.

## Dockumentasi

Ada 2 routes utama yang tersedia di server, `/auth` dan `/api`. `/auth` bertanggung jawab untuk mengotentikasi request untuk menggunakan server ini. Dan `/api` bertanggung jawab untuk mengatasi permintaan data resource yang ada di server, untuk mengakses route ini anda harus memberikan header 'Authorization' pada request anda.

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
