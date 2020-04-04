//symfony5-rest-api

git clone https://github.com/hong1234/sym5RestApi.git

cd sym5RestApi

composer install

php bin/console make:migration

php bin/console doctrine:migrations:migrate

php -S localhost:8000 -t public/

// add books
// book 1
curl -i -X POST -H "Content-Type: application/json" -d '{"name":"Music Book", "price":"19", "description":"basic Techs"}' http://localhost:8000/books

// book 2
curl -i -X POST -H "Content-Type: application/json" -d '{"name":"Dance Book", "price":"19", "description":"basic dance"}' http://localhost:8000/books

// book 3
curl -i -X POST -H "Content-Type: application/json" -d '{"name":"Music Advanced", "price":"29", "description":"Advanced techs"}' http://localhost:8000/books

// add reviews to book 1
curl -i -X POST -H "Content-Type: application/json" -d '{"name":"john@yahoo.com", "description":"Good Basic tech"}' http://localhost:8000/books/1/reviews

curl -i -X POST -H "Content-Type: application/json" -d '{"name":"maria@gmail.com", "description":"very good intro"}' http://localhost:8000/books/1/reviews

// add reviews to book 2
curl -i -X POST -H "Content-Type: application/json" -d '{"name":"darling@gmail.com", "description":"it makes me happy"}' http://localhost:8000/books/2/reviews

// add reviews to book 3
curl -i -X POST -H "Content-Type: application/json" -d '{"name":"madman@gmail.com", "description":"not for me"}' http://localhost:8000/books/3/reviews

// show all books
curl -s http://localhost:8000/books | jq

// show book id=3
curl -s http://localhost:8000/books/3 | jq

// show reviews of book 1
curl -s http://localhost:8000/books/1/reviews | jq

// search book with name = %Music%
curl -s http://localhost:8000/books/search/byname?name=Music | jq

// update book 2
curl -i -X PUT -H "Content-Type: application/json" -d '{"name":"Dance Book Updated", "price":"29", "description":"basic Techs Updated"}' http://localhost:8000/books/2

// delete book 3
curl -X DELETE http://localhost:8000/books/3



