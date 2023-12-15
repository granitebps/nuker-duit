# NukerDuit

## How to
- Clone this project
- Run `cp .env.example .env` to copy env file. Fill it for postgres credetial
- Go to api folder and run `cp .env.example .env`. Fill `.env` file
- Go to app folder and run `cp .env.example .env`. Fill `.env` file
- Run `docker-compose up` in root folder to run it using docker
- Run `docker exec api php artisan migrate` to run migration
- Run `docker exec api php artisan db:seed` to run seeder
- Access API in `http://localhost:8000`
- Access App in `http://localhost:3000`

## Default Credential
- username: admin
- password: password