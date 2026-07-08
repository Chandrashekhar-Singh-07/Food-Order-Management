# Tadka Express — Food Ordering System

HTML, CSS, JavaScript aur PHP + MySQL se bana hua complete food ordering website, saath me ek admin panel bhi hai orders manage karne ke liye.

## Features
- Menu browsing (category-wise: Starters, Main Course, Breads, Rice & Biryani, Desserts)
- AJAX-based cart (add/remove/update quantity, bina page reload ke)
- Checkout form with server-side validation
- Orders MySQL database me save hote hain
- Admin panel: login, dashboard with stats, order list with status filter, order details, status update (Pending → Confirmed → Preparing → Out for Delivery → Delivered)

## Requirements
- XAMPP / WAMP / MAMP / LAMP (PHP 7.4+ aur MySQL/MariaDB)

## Setup Steps

1. **Project ko htdocs me daalein**
   Is poore `food-ordering` folder ko apne XAMPP ke `htdocs` folder me copy karein
   (Windows: `C:\xampp\htdocs\food-ordering`)

2. **Apache aur MySQL start karein**
   XAMPP Control Panel me dono services start karein.

3. **Database import karein**
   - phpMyAdmin kholein: `http://localhost/phpmyadmin`
   - "Import" tab me jaakar `database.sql` file select karein aur import karein.
   - Ye `food_ordering` naam se database aur zaroori tables + sample menu items bana dega.

4. **Database config check karein**
   `config.php` file kholein aur agar aapka MySQL username/password default (root, blank) se alag hai to update karein.

5. **Admin account banayein (sirf ek baar)**
   Browser me kholein: `http://localhost/food-ordering/admin/setup.php`
   Isse default admin account ban jayega:
   - Username: `admin`
   - Password: `admin123`

   Setup ke baad `admin/setup.php` file ko delete kar dein (security ke liye).

6. **Website chalayein**
   - Customer side: `http://localhost/food-ordering/`
   - Admin panel: `http://localhost/food-ordering/admin/login.php`

## Folder Structure
```
food-ordering/
├── index.php              (menu page)
├── cart.php                (cart page)
├── checkout.php             (delivery details form)
├── place_order.php          (order save karta hai)
├── order_success.php        (confirmation page)
├── add_to_cart.php          (AJAX endpoint)
├── remove_from_cart.php
├── config.php                (database connection)
├── database.sql              (import karne wali file)
├── assets/
│   ├── css/style.css
│   └── js/script.js
├── includes/
│   ├── header.php, footer.php, helpers.php, admin_auth.php
└── admin/
    ├── setup.php   (ek baar chalayein, phir delete kar dein)
    ├── login.php, logout.php
    ├── dashboard.php, order_details.php, update_status.php
```

## Customize Karna Ho To
- Menu items add/edit/delete karne ke liye phpMyAdmin me `menu_items` table use karein (abhi admin panel se menu edit karne ka UI nahi hai, sirf orders manage hote hain).
- Dish photos ke liye abhi emoji-icon tiles use ho rahe hain — agar real photos chahiye to `menu_items.image` column me filename daal kar `index.php` me `<img>` tag add kiya ja sakta hai.
- Colors/fonts `assets/css/style.css` ke top me `:root` variables se change ho sakte hain.

## Security Note
Ye ek learning/demo-grade project hai. Production me daalne se pehle: HTTPS use karein, real payment gateway integrate karein, aur rate-limiting/CSRF tokens add karein.
