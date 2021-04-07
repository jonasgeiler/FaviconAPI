# Fat-Free Framework Boilerplate

This is a simple boilerplate for Projects using the [Fat-Free Framework](https://fatfreeframework.com/).

## Getting Started

### With `composer create-project`

Replace `my-project` with your project name:
```console
composer create-project skayo/f3-boilerplate my-project
```

### With `composer install`

Clone this repository and then run:
```console
composer install
```
Then copy `.env.example` to `.env`

## Directory Structure

The directory structure is heavily inspired by Laravel/Lumen and can be customized however you want:
```
.
├── .env               # environment variables
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Helpers/       # helper functions and classes
├── config/
│   ├── bootstrap.php  # initializes the whole application
│   ├── globals.php    # framework variables and other globals
│   └── routes.php     # routes, maps and redirects
├── public/            # public web root
│   └── index.php      # entry point of the whole application
├── resources/
│   ├── langs/         # localization files
│   └── views/         # views/layouts
├── storage/           # storage for the application (needs chmod 0777)
│   ├── cache/
│   ├── logs/ 
│   ├── tmp/           # temporary files  
│   └── uploads/
└── lib/               # composer install directory
```
