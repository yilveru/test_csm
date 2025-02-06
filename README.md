Project Setup and Task Solutions
Requirements:

PHP Version: 8.2.27
WordPress Version: 6.7.1
MySQL Version: 5.7

Setup Instructions

To run the project, you need to set up the environment with Docker. Follow these steps:

1. Run the project with Docker:
   docker-compose up -d

2. Enable friendly URLs in Apache:
   docker exec -it wordpress bash
   a2enmod rewrite
   service apache2 restart

This will set up the environment with PHP, MySQL, Apache, and WordPress.

Task Solutions

- Task 1: Menu Configuration
  The menu has been configured according to the task requirements, with Root A and Root B submenus.
  The system also determines which banner will be shown based on the selected submenu.

- Task 2: CRUD for Featured Products
  A CRUD system has been created to manage the featured products or Product of the Day.
  This allows the admin to add, edit, and delete products that can be highlighted as the product of the day.

# test_csm
