# Aplikcja do przetwarzania zamówień

## Konfiguracja środowiska rozwojowego

## Ubuntu 24.04 LTS
Na początku należy zainstalować podstawowe pakiety oraz baza danych

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

Serwer jest dostępny pod linkiem:
[127.0.0.1:8000](http://127.0.0.1:8000)

