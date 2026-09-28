# MySite - PHP E-Commerce Store

A clothing store built from scratch with **PHP** and **MySQL**, with a burgundy, cream and gold design.

**Live site:** https://isi.lovestoblog.com

## Built with

- **PHP** (no framework): server-side logic, sessions, forms
- **MySQL** with PDO and prepared statements: products, blog posts, orders, admin accounts
- **HTML and CSS**: responsive layout with a mobile hamburger menu
- **JavaScript**: scroll animations and the mobile menu toggle

## Features

- Product catalogue with categories and search
- Shopping cart using PHP sessions
- Checkout that saves orders to the database
- Blog / journal with images
- Admin dashboard (hashed passwords) to manage products and view orders
- Product image uploads
- Works on phones, tablets and desktops

## Project structure

```
includes/          shared header and footer, admin check
uploads/           product images uploaded by the admin
db.php             database connection
products.php       product functions
posts.php          blog post functions
index.php          shop homepage
product.php        single product page
cart.php           shopping cart
checkout.php       checkout and order placement
admin.php          manage products
admin-login.php    admin login
admin-orders.php   view orders
blog.php, post.php blog list and single post
about.php, contact.php, faq.php
style.css          all styling
```

## Run it locally

1. Install [XAMPP](https://www.apachefriends.org/)
2. Put the project in `htdocs/mysite`
3. Create a database called `mysite` in phpMyAdmin and set up the tables
4. Set your local MySQL details in `db.php`
5. Open `http://localhost/mysite/`
