
Instalacja zadania
========================


Aby aplikacja działała należy wykonać poniższe kroki:

  * Pliki aplikacji należy skopiować do folderu docelowego, folder web powinien być serwowany przez serwer apache lub inny. Aplikację moża również uruchamiać wykonujc z katalogu głównego polecenie "symfony server:start"

  * Uruchamiamy composer install

  * Tworzymy plik ".env" w katalogu głównym aplikacji i wklejamy dane połączenia do serwera mysql/bazy np. : "DATABASE_URL="mysql://login:haslo@127.0.0.1:3306/tm"

  * Uruchamiamy polecenia "php bin/console doctrine:database:create" oraz "php bin/console doctrine:schema:create"



Uruchamianie testów
========================

Testy funkcjonalne można uruchomić za pomocą polecenia: "php bin/phpunit tests". W przypadku pojawienia się problemów z odpaleniem testów z poziomu netbeansa, należy wykonać polecenie "composer dump-env test".


Api
========================

Api wyposażone jest w metody:

    * rejestracja POST /api/user {"usermail":"login", "plainPassword": "password"} zwraca id utworonego obiektu
    * aktualizacja PUT /api/user/1 {"usermail":"login", "plainPassword": "password"} zwraca "OK"
    * usunięcie DELETE /api/user/1 zwraca "OK"
    * pobranie danych użytkownika GET /api/user/1 zwraca {"usermail":"login", "plainPassword": "password"}
    
