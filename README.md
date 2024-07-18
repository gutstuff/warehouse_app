# Aplikcja do przetwarzania zamówień

## Konfiguracja środowiska rozwojowego

## Ubuntu 24.04 LTS
Na początku należy zainstalować podstawowe pakiety oraz bazę danych:

```
sudo apt update
sudo apt install -y php-cli php-xml php-intl php-mbstring  php-mysql git mariadb-server
```

Instalujemy symfony-cli oraz composer:

symfony-cli: https://symfony.com/download

composer: https://getcomposer.org/download/

*(Opcjonalnie) uruchomić skrypt do utwardzenia ustawień bazy:*

```
sudo mysql_secure_installation
```

Do bazy należy dodać użytkownika `app` oraz bazę danych `app`:

```sql
CREATE USER 'app'@'localhost' IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES ON app.* TO app@localhost IDENTIFIED BY "password";
FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS app;
```

```
git clone https://github.com/gutstuff/warehouse_app.git warehouse_app
cd warehouse_app
composer install
# #lub polecenie:
# symfony composer install
bin/console doc:mi:mi
```

*(Opcjonalne) przykładowe dane (z dokumentacji):*

```sql
INSERT INTO app.product (name, net_price, stock_availability) VALUES ('Ołówek', 2.00, 2);
INSERT INTO app.product (name, net_price, stock_availability) VALUES ('Długopis', 5.00, 3);
```

## Uruchomienie
```
cd warehouse_app
symfony serve
```

Serwer jest dostępny pod linkiem: [127.0.0.1:8000](http://127.0.0.1:8000)

## Zapytania

### Lista produków

`GET /product/list`

przykładowa odpowiedź:

```json
[
  {
    "id": 1,
    "name": "Ołówek",
    "description": "-",
    "stockAvailability": 2,
    "netPrice": 2
  },
  {
    "id": 2,
    "name": "Długopis",
    "description": "-",
    "stockAvailability": 3,
    "netPrice": 5
  }
]
```

### Nowe zamówienie

`POST /order/new`

body:

```json
{
  "description": "Zamówienie dla xyz",
  "orders": [
    {
      "id": 1,
      "count": 2
    },
    {
      "id": 2,
      "count": 1
    }
  ]
}
```
`description` (opjonalne) - Opis zamówienia.

`orders` - Tablica zamawianych produków. Obiekt musi zawierać
pole `product_id` - id produktu oraz `count` - ilość zamawianego produktu.

przykładowa odpowiedź:

```json
{
  "id": 7,
  "description": "Zamówienie dla xyz",
  "dateCreated": "2024-07-09T21:38:05+00:00",
  "orders": [
    {
      "name": "Ołówek",
      "count": 2,
      "sumVat": 4.92
    },
    {
      "name": "Długopis",
      "count": 1,
      "sumVat": 6.15
    }
  ],
  "countAll": 0,
  "sum": 9,
  "sumVat": 11.07
}
```

### Pobranie instniejącego zamówienia

`GET /order/{id}`

`{id}` - id zamówienia

przykładowa odpowiedź:

`GET /order/7`

```json
{
  "id": 7,
  "description": "Zamówienie dla xyz",
  "dateCreated": "2024-07-09T14:45:04+00:00",
  "orders": [
    {
      "name": "Ołówek",
      "count": 2,
      "sumVat": 4.92
    },
    {
      "name": "Długopis",
      "count": 1,
      "sumVat": 6.15
    }
  ],
  "countAll": 0,
  "sum": 9,
  "sumVat": 11.07
}
```
