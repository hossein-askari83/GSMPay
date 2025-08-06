# User and Post Management System

This is a Laravel 12 application built with PHP 8.3, implementing a user and post management system with JWT authentication, file uploads, and post view tracking. It uses Domain-Driven Design (DDD) with domains for User, File, Post, and View. The project leverages Docker, Nginx, Postgres, Redis, Elasticsearch, Kafka, Kibana, and Zookeeper for scalability and performance. APIs provide post listings, user rankings by post views, and profile photo uploads.

## Project Setup

Follow these steps to set up the project locally:

1. **Build and start Docker containers**  
   ```bash
   docker-compose up -d --build
   ```

2. **Install PHP dependencies**  
   ```bash
   docker-compose exec app composer install
   ```

3. **Generate application key**  
   ```bash
   docker-compose exec app php artisan key:generate
   ```

4. **Run database migrations and seed data**  
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

5. **Index posts in Elasticsearch**  
   ```bash
   docker-compose exec app php artisan posts:index
   ```

6. **Link storage directory**  
   Create a symbolic link for file storage:  
   ```bash
   docker-compose exec app php artisan storage:link
   ```

7. **Start Kafka consumer for view tracking**  
   ```bash
   docker-compose exec app php artisan kafka:consume-viewed
   ```