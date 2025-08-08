# User and Post Management System

This is a Laravel 12 application built with PHP 8.3, implementing a user and post management system with JWT authentication, file uploads, and post view tracking. It uses Domain-Driven Design (DDD) with domains for User, File, Post, and View. The project leverages Docker, Nginx, Postgres, Redis, Elasticsearch, Kafka, Kibana, and Zookeeper for scalability and performance. APIs provide post listings, user rankings by post views, and profile photo uploads.

## Project Setup

### Automated Setup via Entrypoint

The Docker container uses an `entrypoint.sh` script that automatically performs initial setup tasks such as:

- Copying `.env.example` to `.env` if missing
- Installing PHP dependencies (`composer install`)
- Generating application keys (`php artisan key:generate`)
- Running database migrations and seeding
- Generating JWT secrets
- Creating storage symlinks
- Running tests

This setup runs **once** when the container starts. You should check the container logs to confirm the setup completed successfully and no errors occurred before proceeding.

To view logs and verify setup completion, run:

```bash
docker-compose logs -f app
```

Wait until you see a message like:

**Laravel setup complete!**

### Custom Commands

**Index posts in Elasticsearch**  
   ```bash
   docker-compose exec app php artisan posts:index
   ```

**Start Kafka consumer for view tracking**  
   ```bash
   docker-compose exec app php artisan kafka:consume-viewed
   ```