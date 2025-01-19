# Symfony Docker Project

This is a Symfony project running inside Docker containers.

## Prerequisites

- Docker
- Docker Compose

## Running the Project

### 1. Clone the Repository
```bash
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
