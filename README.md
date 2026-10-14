# Voltio

A Laravel application for reserving and purchasing products. Customers will be able to browse products by category, keep selected products in a shopping cart, and view their purchase history.

## Project Status

The project is in development. The Docker development environment is running, and the features below represent the planned roadmap.

## Version 1.0 — Core Features

- **Registration and Login:** Create an account and sign in.
- **Categories:** Browse products by category.
- **Products:** View available products and their details.
- **Shopping Cart:** Keep selected products in a cart and manage the selection before reserving or purchasing them.
- **Reservations and Purchases:** Reserve or purchase selected products.
- **My Purchases:** View purchase history.
- **Admin Panel:** Provide an administrative interface for managing the application.

## Version 2.0 — Additional Features

Version 2.0 will include all features from Version 1.0 and add:

- **Tracking:** Introduce tracking functionality. The exact scope will be defined during Version 2.0 planning.
- **Cron Jobs:** Add scheduled tasks to automate recurring application operations. Specific tasks will be defined during Version 2.0 planning.

## Development Environment

The application uses Laravel and PHP, with a Docker Compose development environment that includes Nginx, PHP-FPM, MySQL, Redis, phpMyAdmin, a workspace service, and a migration service.

The development configuration is defined in `compose.dev.yaml`.

### Start the Configured Development Environment

From the project root, run:

```bash
docker compose -f compose.dev.yaml up -d --build
```

Open the application at [http://localhost:8080](http://localhost:8080).

### Check Container Status

```bash
docker compose -f compose.dev.yaml ps -a
```

### Stop the Development Environment

```bash
docker compose -f compose.dev.yaml stop
```
### Refresh migration + re-seeder initialization
```bash
docker compose -f compose.dev.yaml run --rm workspace php artisan migrate:fresh --seed
```

### View all tables related to the application
```bash
docker compose -f compose.dev.yaml exec mysql   mysql -ularavel -ppassword app -e "SHOW TABLES;"
```

### Clearing the Docker build cache can free up several gigabytes (safe cleaning)
```bash
docker builder prune -af
```

### Removing stalled containers:
```bash
docker container prune

```

### Removing an image that is not being used by any active container:
```bash
docker image prune -a
```
