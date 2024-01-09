# Building and running the Project


- Go to project folder and run:
    - <b>Copy env.example to .env</b>
    - <b>docker-compose up --build</b>
- Bash into the container:
    - <b>docker exec -it1000:1000 * container_name_here * bash </b>
- Run the following: 
    - <b> composer install </b>
    - <b> php artisan key:generate </b>
    - <b> php artisan migrate </b>
  
#### I have a postman collection attached in the project folder called: tech-test.postman_collection.json

    I have PHPUnit tests, please make sure you use the Interperator from the docker app container
    or each test!
