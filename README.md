# FILAMENT COURSE

## Introduction

This repository contains the code for the Filament course. The course is designed to teach the basics of Filament, for the MDS team developers.

## Classes

The course is divided into the following classes:

Diagram of the classes:

![Class Diagram](https://mds-presentations.s3.amazonaws.com/courses/ERM.png){width=250}


1. **Class 1**: Installation and setup [video](https://mdsco-my.sharepoint.com/:v:/g/personal/ftorres_mdsdigital_com/EWT7-rqrh1dBnwayGshnbucBHTimBnRWqVZ1K0RE7j8cTg?e=Sm3VF1&nav=eyJyZWZlcnJhbEluZm8iOnsicmVmZXJyYWxBcHAiOiJTdHJlYW1XZWJBcHAiLCJyZWZlcnJhbFZpZXciOiJTaGFyZURpYWxvZy1MaW5rIiwicmVmZXJyYWxBcHBQbGF0Zm9ybSI6IldlYiIsInJlZmVycmFsTW9kZSI6InZpZXcifX0%3D) [code](https://github.com/darking09/filament-class/tree/Class-1)
2. **Class 2**: Handle of relations and access roles [video](https://mdsco-my.sharepoint.com/:v:/g/personal/ftorres_mdsdigital_com/EVyVB5mYG55BlRZC6CMoOeQBUFLEsquPKdUKptYNiTFF6w?e=hV1rCH&nav=eyJyZWZlcnJhbEluZm8iOnsicmVmZXJyYWxBcHAiOiJTdHJlYW1XZWJBcHAiLCJyZWZlcnJhbFZpZXciOiJTaGFyZURpYWxvZy1MaW5rIiwicmVmZXJyYWxBcHBQbGF0Zm9ybSI6IldlYiIsInJlZmVycmFsTW9kZSI6InZpZXcifX0%3D) [code](https://github.com/darking09/filament-class/tree/class-2)
3. **Class 3**: Create a handling post [video](https://mdsco-my.sharepoint.com/:v:/g/personal/ftorres_mdsdigital_com/EUatoL7Gns5BgsAruz1E3OgBCXtq7d6JEJvcKZSgc0Mr9g?e=tEJAHe&nav=eyJyZWZlcnJhbEluZm8iOnsicmVmZXJyYWxBcHAiOiJTdHJlYW1XZWJBcHAiLCJyZWZlcnJhbFZpZXciOiJTaGFyZURpYWxvZy1MaW5rIiwicmVmZXJyYWxBcHBQbGF0Zm9ybSI6IldlYiIsInJlZmVycmFsTW9kZSI6InZpZXcifX0%3D) [code](https://github.com/darking09/filament-class/tree/Class-3)

## Installation

To install the project, you need to follow the next steps:

1. Clone the repository.
2. Run `docker compose up`.
3. To access to docker container `docker exec -it fl_php sh`.
4. Run the following commands to create the user admin `php artisan make:filament-user` into the container.
5. Go to the next ulr `http://localhost/admin`.

## handle access roles

To create a new roles you just need to follow the next steps:

1. Create the role with the next command `php artisan make:policy ModelPolicy`, where Model should be the name of the model.
2. The policy must be created in the `app/Policies` folder.
3. Access to the policy created and add the next code:

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Model;

class ModelPolicy extends AccessPolicy
{
    protected $modelClass = Model::class;
}
```

Model should be the name of the model.

and that is all, you have created a new role!

## Authors

[Darking09](https://github.com/darking09)
