<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## About Project Laravel via DDD

## The goal of this project is to separate domains using DDD principles to isolate business logic from the framework (Laravel) and place framework capabilities on the Infrastructure, Application and Interface layers.

- **Doctrine 2 is used as an entity manager**
- **Model annotations are stored as XML metafiles**
- **Domain logic stored /app/Domains**
- **Redis Stream is used as a message broker to exchange events between domains** 
- **Redis Stream broadcasts all messages, only subscribers respond with a success or failure response**
- **CQRS (command and query responsibility separation) is also used with the ability to sequentially run commands to form application logic and broadcast domain events, and the publisher-subscriber pattern to exchange events within a specific domain.**

## Setup
- **make build**
- **make install**

## Run container and inside container run event broker
- **make ssh**
- **php artisan event-source:handle deal**

## HTTP Domain entry-points, first one creates Business Account Aggregate
- **http://localhost:8080/identity-access**
- **http://localhost:8080/deal**

## Check file after message broker tasks complete
- **/app/Domains/IdentityAccess/Framework/storage/logs/laravel.log**

```
[2024-10-08 08:05:41] local.INFO: My new business  
[2024-10-08 08:05:41] local.INFO: new.business@gmail.com ]
```

