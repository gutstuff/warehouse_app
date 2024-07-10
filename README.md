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

**(Opcjonalnie) uruchomić skrypt do utwardzenia ustawień bazy:**

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

**Opcjonalne przykładowe dane**

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
    "product_id": 1,
    "name": "Ołówek",
    "description": "-",
    "stock_availability": 2,
    "net_price": 2
  },
  {
    "product_id": 2,
    "name": "Długopis",
    "description": "-",
    "stock_availability": 3,
    "net_price": 5
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
      "product_id": 1,
      "count": 2
    },
    {
      "product_id": 2,
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
  "date_created": {
    "date": "2024-07-09 14:45:04.169562",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "orders": [
    {
      "count": 2,
      "name": "Ołówek",
      "sum_vat": 4.92
    },
    {
      "count": 1,
      "name": "Długopis",
      "sum_vat": 6.15
    }
  ],
  "count_all": 3,
  "sum": 9,
  "sum_vat": 11.07
}
```

### Pobranie instniejącego zamówienia

`GET /order/{id}`

`{id}` - id zamówienia

przykładowa odpowiedź:

`GET /order/7`

```json
{
  "description": "Zamówienie dla xyz",
  "date_created": {
    "date": "2024-07-09 14:45:04.000000",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "orders": [
    {
      "name": "Ołówek",
      "count": 2,
      "sum_vat": 4.92
    },
    {
      "name": "Długopis",
      "count": 1,
      "sum_vat": 6.15
    }
  ],
  "count_all": 3,
  "sum": 9,
  "sum_vat": 11.07
}
```
