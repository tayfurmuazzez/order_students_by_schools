
# Set Order Number of Student By School

This project is a test project that organizes the sequence number of students according to their schools.

In this project has that migration,factory,seed and soft delete processes of students and schools.

At the same time, students and schools CRUDs (Create, Get, Update, Delete) operations are performed on the Admin side.

On the API side, CRUDs (Create, Get, Update, Delete) operations are performed for only Students. All API processes have unit tests.

Finally, there is a structure that allows editing the sequence numbers of students who have been deleted or confused with Commad. When the command is finished, sending e-mails is done automatically. 
There are unit test codes of this Command operation.

## Skills

**-** PHP 8

**-** Laravel 9

**-** MySql

**-** Apache

  
## SetUp Project

Please clone the project

```bash
  git clone https://github.com/tayfurmuazzez/order_students_by_schools.git
```

Go to project

```bash
  cd test-order-students-project
```

Running Compesser

```bash
  composer update
```

Fixed you .env file for database

```bash
  DB_DATABASE=your_db
  DB_USERNAME=your_db_username
  DB_PASSWORD=your_db_password
```
Start Projects

```bash
  php artisan serve
```

Running Migration and Seeder

```bash
  php artisan migrate
  php artisan db:seed
```
  
## Unit Tests

Run the following command to run the tests

```bash
  php artisan test
```

  
## Use API

#### Authorization - Bearer Token

```http
  POST /api/signin/
```

| Parameter | Type     | Definition                |
| :-------- | :------- | :------------------------- |
| `email` | `string` | **required**. User email. |
| `password` | `string` | **required**. User password. |

Tokens are checked in all request operations. Tokens must be obtained before requests are made.

####  Create New Student

```http
  POST /api/student/create
```

####  Get All Student List

```http
  GET /api/student/get
```

####  Update Student

```http
  POST /api/student/update
```


####  Delete Student

```http
  POST /api/student/delete
```

####  Get Only One Student

```http
  GET /api/student/read/{id}
```

  
## Postman

You can fork and test the collection related to the Postman link.

 [To Postman](https://www.postman.com/muazzeztayfur/workspace/mgssoft/collection/7837070-7df956e6-2479-4221-b687-d7144ee02829?action=share&creator=7837070)