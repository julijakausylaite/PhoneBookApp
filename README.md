# PhoneBook project with Symfony using Docker

Phone book online:
- Phone entries available to registered users
- A phonebook entry can be shared and unshared with another user (unshare function is in edit contact page)
- Phonebook entry: name, phone (full CRUD)
- Unit test
- Feature test

Prerequisites:
- Docker
- Docker Compose

## Running the Project

### 1. Clone the Repository

```
git clone https://github.com/julijakausylaite/PhoneBookApp.git
cd PhoneBookApp
```

### 2. Build fresh images

```
docker compose build --no-cache
```

### 3.  Set up and start a Symfony project

```
docker compose up --pull always -d --wait
```

### 4. Open project in browser

```
https://localhost
```

## Unit and Feature tests

### 1. Run the database migration commands

```
docker-compose exec php bin/console make:migration
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction --env=test
```

### 2. Run the tests

```
docker-compose exec php ./vendor/bin/phpunit
```

## Stop 

```
docker compose down --remove-orphans
```
