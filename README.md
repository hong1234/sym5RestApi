//symfony5-rest-api

git clone https://github.com/hong1234/sym5RestApi.git

cd sym5RestApi

composer install

php bin/console make:migration

php bin/console doctrine:migrations:migrate

php -S localhost:8000 -t public/

// add books

// book 1

curl -i -X POST -H "Content-Type: application/json" -d '{"title":"Music Book", "content":"basic Techs"}' http://localhost:8000/api/books

// book 2

curl -i -X POST -H "Content-Type: application/json" -d '{"title":"Dance Book", "content":"basic dance"}' http://localhost:8000/api/books

// book 3

curl -i -X POST -H "Content-Type: application/json" -d '{"title":"Music Advanced", "content":"Advanced techs"}' http://localhost:8000/api/books

// add reviews to book 1

curl -i -X POST -H "Content-Type: application/json" -d '{"name":"john", "email":"john@yahoo.com", "content":"Good Basic tech"}' http://localhost:8000/api/books/1/reviews

curl -i -X POST -H "Content-Type: application/json" -d '{"name":"maria", "email":"maria@gmail.com", "content":"very good intro"}' http://localhost:8000/api/books/1/reviews

// add reviews to book 2

curl -i -X POST -H "Content-Type: application/json" -d '{"name":"darling", "email":"darling@gmail.com", "content":"it makes me happy"}' http://localhost:8000/api/books/2/reviews

// add reviews to book 3

curl -i -X POST -H "Content-Type: application/json" -d '{"name":"madman", "email":"madman@gmail.com", "content":"not for me"}' http://localhost:8000/api/books/3/reviews

// show all books

curl -s http://localhost:8000/api/books | jq

// show book id=3

curl -s http://localhost:8000/api/books/3 | jq

// show reviews of book 1

curl -s http://localhost:8000/api/books/1/reviews | jq

// search book with name = %Music%

curl -s http://localhost:8000/api/books/search?title=Music | jq

// update book 2

curl -i -X PUT -H "Content-Type: application/json" -d '{"title":"Dance Book Updated", "content":"basic Techs Updated"}' http://localhost:8000/api/books/2

// delete book 3

curl -X DELETE http://localhost:8000/api/books/3



