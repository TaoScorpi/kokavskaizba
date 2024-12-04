# Gatsby Sound Orchestra

# Docker

Docker must be installed on your machina. For windows use download from this link https://www.docker.com/get-started Docker Desktop version 
and for otherwise os version see more info on website https://www.docker.com.

## Bring up local development enviroment via Docker

Create enviroment config file:

```shell
composer run-script create-env-config-file
```

Edit enviroment (.env) config file:

```shell
APP_NAME=GatsbySoundsOrchestra
DB_DATABASE=gatsbysoundsorchestra

EMAIL_USERNAME=no-reply@taoscorpi.sk
EMAIL_PASSWORD=<password>
```

## Run

Use command line (powershell for windows, git bash and otherwise),
go to project folder and run command.

run like deamon (automatic run after start os) - parameter [ -d ]

```bash
docker compose up -d
```

run in console

```bash
docker compose up
```

Install composer packages:

```shell
docker compose exec app composer install
```

Install npm packages:

```shell
npm i --save
```

Build tailwindcss output css file:

```shell
npm run build:tailwindcss
```

Build webpack for development enviroment:

```shell
npm run build:webpack:dev
```

Then you should be able to access the application on:

```shell
http://localhost:8000
```
