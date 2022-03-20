

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Alternative installation is possible without local dependencies relying on [Docker](#docker). 

Clone the repository

    git clone https://github.com/bojankostadinov93/EasyTra.git

Switch to the repo folder

    cd EasyTra

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

Set up "FIXER_KEY in .env file"

    FIXER_KEY=5d60325ed4748a369281e7315353b629
    

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone https://github.com/bojankostadinov93/EasyTra.git
     cd EasyTra
    composer install
    cp .env.example .env
    php artisan key:generate
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

## Database seeding

**Populate the database with seed data with relationships which includes Api_key . This can help you to quickly start testing the api .**

Run the database seeder and you're done

    php artisan db:seed --class=ApiKeySeeder

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh
    

The api can be accessed at (http://127.0.0.1:8000/api/currency-converter) as a POST request.

## API Specification

The endpoint requires 4 parameters:
- <b>key</b> - Valid key in order to use this API. Find one valid key from the api_keys table
- <b>source_currency</b> - Currency that user wants to convert from
- <b>target_currency</b> - Currency that user wants to convert to
- <b>value</b> - Amount of source currency that should be converted

----------

# Code overview

## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

----------

# Testing API

Run the laravel development server

    php artisan serve

Create one POST request with following endpoint

    http://127.0.0.1:8000/api/currency-converter?key=YOUR_KEY_FROM_API_KEYS_TABLE&source_currency=EUR&target_currency=USD&value=200


----------
 
