# Symfony API recommendation system with Elasticsearch
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)
* [Usage](#usage)

## General info
<details>
  <summary>Recommendation system</summary>
This project includes a simple API designed to provide recommendations on which products users have bought together
</details>

## Technologies
### Most important technologies used in project:
* Symfony
* Elasticsearch
* Kibana
* Docker
* PostgreSQL

## Setup
To setup project, follow these steps:
1. create _**.env**_ and **.env.test**_ file along the lines of **.env.example**
2. invoke **_JWT token_** by command: `php bin/console lexik:jwt:generate-keypair`
3. create a database by command: `php bin/console doctrine:database:create`
    - if you want to create database for tests use `php bin/console doctrine:database:create --env=test`
    - if you want to create migrations: `php bin/console make:migration`
    - to invoke migrations: `php bin/console doctrine:migrations:migrate` - for testing database migrations use: `php bin/console doctrine:migrations:migrate --env=test`
4. copy cert file for Elasticsearch security: `docker cp symfony-recommendation-system-api-es01-1:/usr/share/elasticsearch/config/certs/ca/ca.crt .` - [here is a link to documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/configuring-stack-security.html)

## Usage
All endpoints are located in the path `/api`.
The recommendation endpoint can be found at `api/products/recommendations/NameOfProduct`