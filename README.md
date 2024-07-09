# Aplikcja do przetwarzania zamówień

## Konfiguracja środowiska rozwojowego

## Ubuntu 24.04 LTS
Na początku należy zainstalować podstawowe pakiety oraz bazę danych

```
sudo apt update
sudo apt install -y php-cli php-xml php-intl php-mbstring  php-mysql git mariadb-server
```

Następnie uruchamiamy skrypt do utwardzenia ustawień bazy. Hasło `root` zmieniamy na `password`

```
sudo mysql_secure_installation
```

```
git clone https://github.com/gutstuff/warehouse_app.git warehouse_app
cd warehouse_app
composer install
```

## Uruchomienie
```
cd warehouse_app
symfony serve
```

Serwer jest dostępny pod linkiem: [127.0.0.1:8000](http://127.0.0.1:8000)

## Zapytania

### Nowe zamówienie

POST /order/new
body:
```json
{
	"description": "Specjalne zamówienie xyz",
	"orders": [
		{
			"productId": 1,
			"count": 2
		},
		{
			"productId": 2,
			"count": 1
		}
	]
}
```
`description` (opjonalne) - Opis zamówienia.

`orders` - Tablica zamawianych produków. Obiekt musi zawierać
pole `productId` - id produktu oraz `count` - ilość zamawianego produktu.

odpowiedź:

Udane zamówienie zwraca format json taki sam jak format wejściowy

### Pobranie instniejącego zamówienia

GET /order/{id}

`{id}` - id zamówienia

przykładowa odpowiedź:

`GET /order/2`

```json
{
  "description": "Specjalne zamówienie xyz",
  "date_created": {
    "date": "2024-07-04 15:48:44.000000",
    "timezone_type": 3,
    "timezone": "UTC"
  },
  "orders": [
    {
      "product_id": 1,
      "count": 2,
      "net_price": 5,
      "sum": 10,
      "sum_vat": 12.3
    },
    {
      "product_id": 2,
      "count": 1,
      "net_price": 7,
      "sum": 7,
      "sum_vat": 8.61
    }
  ],
  "count_all": 3,
  "sum": 17,
  "sum_vat": 20.91
}
```
