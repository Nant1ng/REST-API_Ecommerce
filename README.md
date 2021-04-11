# REST-API_E-commerce 

- [Description](#description)
- [Endpoints](#endpoints)
- [How to](#how-to)

---

## Description

#### This is a school assignment. The assignment was about creating a REST API for E-commerce. I have only used plain PHP and no frameworks. The RESTful API will only return results in JSON and I have used CRUD principles.

---

## Endpoints

### Users 

---

#### POST - Create user
[![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/)
>http://localhost/Skola/REST_API/V1/users/Create.php

### GET - Read all users 
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/)
>http://localhost/Skola/REST_API/V1/users/ReadAll.php 
<br>
You need to put the accesstoken provided in header: Authorization = accesstoken.

---

### Sessions

---

#### POST - Log in
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/)

>http://localhost/Skola/REST_API/V1/sessions.php

#### PATCH - Refresh accesstoken
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/)

>http://localhost/Skola/REST_API/V1/sessions.php?sessionid=(Your session id)</br>
Use the same session id you got when logged in.
You need to put the accesstoken provided in header: Authorization = accesstoken.

#### DELETE - Log out
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/sessions.php?sessionid=(Your session id)</br>
Use the same session id you got when logged in. 
You need to put the accesstoken provided in header: Authorization = accesstoken.
---

### Products
---

#### GET - Read products
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/products/ReadAll.php?(Page number)</br>
Only 2 products per page, just to show that page nation works. </br> Pagenumber can be 1 or 2 and so on.
---

#### GET - Read single product
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/products/Read.php?productid=(Product ID)</br>
Product ID need to be an existing id.
---

#### POST - Create product
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/products/Create.php <br>
You need to put the accesstoken provided in header: Authorization = accesstoken.
---

#### DELETE - Delete product
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/products/Delete.php?productid=(Product ID)</br>
Product ID need to be an existing id. 
You need to put the accesstoken provided in header: Authorization = accesstoken.
---

#### PATCH - Update product
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/products/Update.php?productid=(Product ID)</br>
Product ID need to be an existing id.
You need to put the accesstoken provided in header: Authorization = accesstoken.
---

### Cart

---

#### POST - Add product to cart
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/)

>http://localhost/Skola/REST_API/V1/carts/Create.php?productid=(Product ID)</br>
Product ID need to be an existing id.
You need to put the accesstoken provided in header: Authorization = accesstoken.
---

#### DELETE - Delete product from cart
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/carts/Delete.php?productid=(Product ID)</br>
Product ID need to be an existing id.
You need to put the accesstoken provided in header: Authorization = accesstoken.
---

#### GET - Read cart 
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/carts/Read.php <br> 
You need to put the accesstoken provided in header: Authorization = accesstoken.
---

#### POST - Checkout cart
[![made-with-python](https://img.shields.io/badge/Admin-darkred)](https://shields.io/) [![made-with-python](https://img.shields.io/badge/User-red)](https://shields.io/) 

>http://localhost/Skola/REST_API/V1/carts/Checkout.php <br>
You need to put the accesstoken provided in header: Authorization = accesstoken.
---

## How to 



<details>
<summary>Create users, sessions or products?</summary>

### Create Users: 
You need to have Content-Type: application/json in header
```html
{
    "fullname":"Your Name",
    "email":"email@gmail.com",
    "username":"Username",
    "password":"Password"
}
```

---

### Create Sessions: 
You need to have Content-Type: application/json in header
```html
{
    "username":"Username",
    "password":"Password"
}
```

---

### Create Products: 
You need to have Content-Type: application/json in header
```html
{
    "product_title":"Product Title",
    "description":"Product Description",
    "price":"Product Price",
    "stock":"Y",
    "img_url":"img-url"
}
```

---

</details>

---

## Success Response Examples



<details>
<summary>Show Examples</summary>

### User created: 
``` html 
{
    "statusCode": 201,
    "success": true,
    "message": [
        "User created, welcome Your Name"
    ],
    "data": {
        "user_id": "1",
        "fullname": "Your Name",
        "email": "email@gmail.com",
        "username": "Username"
    }
}
```

---

### Session created: 
``` html 
{
    "statusCode": 201,
    "success": true,
    "message": [
        "Logged in"
    ],
    "data": {
        "session_id": 1,
        "access_token": "MzM2MzQ5MDk2MDYwNmFmYTBkMDBjMTY2NDRjNmRiNWM0MTQxOThkZDg1NjJkNWY4MTYxODE2OTEwNA==",
        "access_token_expires_in": 3600,
        "refresh_token": "MmU2ZTk1YmRiNDYyZjc2YWUyNzc0OWM3MTcwYjBkMzQ5MDkzNTM5YTIwNGZmMmIyMTYxODE2OTEwNA==",
        "refresh_token_expires_in": 1209600
    }
}
```

---

### Product created: 
``` html 

    "statusCode": 201,
    "success": true,
    "message": [
        "Product Created"
    ],
    "data": {
        "rows_returned": 1,
        "product": [
            {
                "id": 1,
                "product_title":"Product Title",
                "description":"Product Description",
                "price":"Product Price",
                "stock":"Y",
                "img_url":"img-url"
            }
        ]
    }
}
```

</details>

---

## Error Respons Examples



<details>
<summary>Show Examples</summary>

### Creating User Error:
``` html
{
    "statusCode": 409,
    "success": false,
    "message": [
        "Username or Email already exists"
    ],
    "data": null
}
```

--- 

### Creating Sessions Error:
``` html
{
    "statusCode": 400,
    "success": false,
    "message": [
        "Username cannot be blank",
        "Password cannot be blank"
    ],
    "data": null
}
``` 

---

### Creating Product Error:
``` html
{
    "statusCode": 400,
    "success": false,
    "message": [
        "Product Price Error"
    ],
    "data": null
}
``` 
</details>

---

<br>

[Back To The Top](#REST-API_E-commerce)