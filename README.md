## Docker Ortamına Kurulum İçin Gerekenler

### 0. Gereksinimler

* Docker
* Git
* Composer
* Supervisor

### 1. Projeyi gerekli kurulumlar

Swoole default olarak sail paketi ile gelir.  <br>
PHP kütüphanelerinin kurulması için:

```console
$ ./vendor/bin/sail up -d
$ ./vendor/bin/sail composer require laravel/octane spiral/roadrunner
```

### 2. Projeyi çalıştırmak için

Projeyi octane ile başlatmak için

```console
$ php artisan octane:start
```

## Local Ortama Kurulum İçin Gerekenler

### 0. Gereksinimler

* PHP ^8.0 veya üzeri
* MySQL
* Composer
* Git
* Redis
* Elasticsearch
* Supervisor
* Mailtrap Hesabı
  * https://mailtrap.io/
* Swoole
  * Sisteminize uygun olan uzantıyı buradan indirebilirsiniz. 
  * https://pecl.php.net/package/swoole
* Slack Entegrasyonu
  * Hata loglarını görebilmek için Slack'e mesaj gönderiyoruz. Test ortamında görebilmek için .env dosyasında SLACK_HOST değerini güncellememiz gerekiyor
  * https://webhook.site/ sitesinden Your Unique Url kısmındaki adresi kopyalayıp .env dosyasındaki SLACK_HOST ortam değişkenine aktarmak gerekiyo
   örnek: https://webhook.site/2f416b62-093d-4d73-80b3-f3d1ca72bec7

### 1. Gerekli kütüphanelerin kurulumu

PHP kütüphanelerinin kurulması için:

```console
$ composer install
```

### 2. Konfigürasyonlar

Ortam değişkenlerini ayarlamak için `.env.example` dosyasını `.env` olarak yeniden isimlendirin ve `DB_` ile başlayan
değişkenleri düzenleyin.

```console
$ cp .env.example .env
```

Aşağıdaki komutla uygulama anahtarını yeniden ürettirin.

```console
$ php artisan key:generate
```

Gerekli veritabanı tablolarının oluşturulması için:

```console
$ php artisan migrate
```

Redis kurulumundan sonra Redis'i sunucunu başlatmak için

```console
$ redis-server
```

Redis'i konfigürasyonu için

```console
CACHE_DRIVER=redis

REDIS_CLIENT=predis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Laravel Octane için swoole uzantısına  ihtiyacımız var. Bunun için local ortamımıza kurmamız gerekiyor.
```console
$ pecl install swoole
```

### 3. Uygulamayı çalıştırmak

Uygulamayı çalıştırmak için aşağıdaki komutla basit bir web sunucusu oluşturabilirsiniz:

```console
$ php artisan serve
```

Laravel Octane kütüphanesiyle projeyi ayağa kaldırmak için
```console
$ php artisan octane:start
$ php artisan octane:start --server=swoole
$ php artisan octane:start --server=roadrunner
```
uygulama şu an **127.0.0.1:8000** adresinde çalışır.

Varsayılan verileri veritabanına eklemek için:

```console
$ php artisan db:seed
```

İleri tarih için zamanlanmış senkronizasyon için arkada sürekli açık olması gereken komut:

```console
$ php artisan schedule:work
```

Komut dosyasını çalıştırarak bir job tetiklemiş oluruz ve de job redis ile belirtilen senkronizasyon işlemlerini yapar.

```console
$ php artisan sync:automobile
$ php artisan queue:work --queue=redis --timeout=60
```

## Test

Birim testleri çalıştırmak için:

```console
$ ./vendor/bin/phpunit
$ php artisan test
```

Tarayıcı üzerinde çalışan testleri çalıştırmak için öncelikle bir terminal'de uygulamayı ayağa kaldırın:

```console
$ php artisan serve
```

Selenium ile otomasyon kullanmak için gerekli driver'ları kurmak ve testleri çalıştırmak için (bu testler `.env` dosyasında belirttiğiniz
veritabanını kullanır ve bu testlerin çalışması uzun sürebilir):

```console
$ php artisan dusk:chrome-driver
$ php artisan dusk
```

**NOT:** Bu komutun çalışabilmesi için bilgisayarınızda Google Chrome tarayıcısının yüklü olması gerekir. Olası bir
sorunda
`vendor/laravel/dusk/bin/` dizini altındaki dosyaların 755 izinlerinin olduğundan emin olun:

```console
$ chmod 755 -R ./vendor/laravel/dusk/bin/
```


## RESTful API Kaynakları

#### Örnekler auth ve brands için oluşturulmuştur.

### [POST] `/api/auth/login?version=1`

`email` ve `password` kabul eder. Bilgiler doğrusa, kullanının bilgilerini JSON formatında geri döner.

**Örnek kullanım (varsayılan kullanıcıların `seed` edilmiş olması gerekir):**

Örnek Kullanımı: 

```console
curl --location --request POST 'http://127.0.0.1:8000/api/auth/login?version=1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 9|al1e1c88IJ3cRCULzA46bjk3rfVWestsH4ocRZMS' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email":"umituz998@gmail.com",
    "password":"123456789"
}'
```

Cevap:

```json
{
    "code": 200,
    "message": "Success",
    "user_balance": "100.00",
    "version": "api-version-1",
    "data": {
        "id": 1,
        "name": "Ümit UZ",
        "email": "umituz998@gmail.com",
        "balance": "100.00",
        "access_token": "8|XyEFTCqhRyrab9SUw1rlCbuUvXNGRKKTLuJOnD7h"
    }
}
```
### [POST] `/api/auth/register?version=1`

Örnek Kullanımı: 

```console
curl --location --request POST 'http://127.0.0.1:8000/api/auth/register?version=1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 11|EGyvxIbWslWBDzOGnesF7i623nAOjFjKuBZNeAbV' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Ümit Kenan UZ",
    "email": "umituz999@gmail.com",
    "password": 123456789,
    "password_confirmation" : 123456789
}'
```

Cevap:

```json
{
    "code": 200,
    "message": "Success",
    "user_balance": "0.00",
    "version": "api-version-1",
    "data": {
        "id": 11,
        "name": "Ümit Kenan UZ",
        "email": "umituz999@gmail.com",
        "balance": 0,
        "access_token": "1|WMlEsYQTBMObVdPbFiK4LblNQAJFzbr2x0GHAUxO"
    }
}
``````

`access_token` kısmı sonraki istekler için gerekli olacak.


### [POST] `/api/auth/logout?version=1`

Örnek Kullanımı: 

```console
curl --location --request POST 'http://127.0.0.1:8000/api/auth/logout?version=1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 2|LFyLTbAIbiO3kYo13rQBPXqBD8VdBor29U25hh9L'
```

Cevap:

```json
{
    "code": 200,
    "message": "Success",
    "user_balance": "100.00",
    "version": "api-version-1",
    "data": {
        "message": "Logged Out"
    }
}
```

### [GET] `/api/brands?version=1`

Tüm araba markalarını JSON formatında geri döner.

**Örnek kullanım:**

```console
curl --location --request GET 'http://127.0.0.1:8000/api/brands?version%3D1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 4|0l9exOV7dZOfy1vf3dR0RyaAtO97DQxYu7HnZQb4'
```

**Cevap:**

```json
{
    "code": 200,
    "message": "Success",
    "user_balance": "100.00",
    "version": "api-version-1",
    "data": {
        "total": 3,
        "data": [
            {
                "id": 3,
                "name": "aliquid",
                "model": "et",
                "url": "aliquam",
                "year": "quia",
                "deleted_at": null,
                "created_at": "2022-08-30 15:14:24",
                "updated_at": "2022-08-30 15:14:24"
            },
            {
                "id": 2,
                "name": "praesentium",
                "model": "temporibus",
                "url": "error",
                "year": "soluta",
                "deleted_at": null,
                "created_at": "2022-08-30 15:14:24",
                "updated_at": "2022-08-30 15:14:24"
            }
        ]
    }
}
```

### [GET] `/api/brands/{id}?version=1`

Belirli bir markayı JSON formatında geri döner.

**Örnek Kullanımı:**

```console
curl --location --request GET 'http://127.0.0.1:8000/api/brands/2?version=1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 4|0l9exOV7dZOfy1vf3dR0RyaAtO97DQxYu7HnZQb4' \
--data-raw ''
```

**Cevap**:

```json
{
    "code": 200,
    "message": "Success",
    "user_balance": "100.00",
    "version": "api-version-1",
    "data": {
        "id": 2,
        "name": "praesentium",
        "model": "temporibus",
        "url": "error",
        "year": "soluta",
        "deleted_at": null,
        "created_at": "2022-08-30 15:14:24",
        "updated_at": "2022-08-30 15:14:24"
    }
}
```

### [POST] `/api/brands?version=1`

Yeni marka oluşturur. Oluşturulan markayı JSON formatında geri döner.

* Gerekli alanlar: `name`, `model`, `url`, `year`

**Örnek kullanım:**

```console
curl --location --request POST 'http://127.0.0.1:8000/api/brands?version%3D1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 1|uZbQqOUqn2aH9R2UMbdaMoALRGNBSoynQIe20psB' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "test-name",
    "model":"test-model",
    "url":"http://test-url.com",
    "year":"test-year"
}'
```

Cevap:

```json
{
    "code": 200,
    "message": "Success",
    "user_balance": "100.00",
    "version": "api-version-1",
    "data": {
        "name": "test-name",
        "model": "test-model",
        "url": "http://test-url.com",
        "year": "test-year",
        "updated_at": "2022-08-30 15:18:34",
        "created_at": "2022-08-30 15:18:34",
        "id": 4
    }
}
```

### [PUT] `/api/brands/{id}`

`id`si verilen markayı günceller. Güncellenen markanın içeriği geriye JSON olarak gönderilir.

**Örnek Kullanım:**

```console
curl --location --request PUT 'http://127.0.0.1:8000/api/brands/1?version%3D1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 1|uZbQqOUqn2aH9R2UMbdaMoALRGNBSoynQIe20psB' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "test-name-2",
    "model":"test-model-22",
    "url":"http://test-url-2.com",
    "year":"test-year-2"
}'
```

Cevap:

```json
{
    "code": 200,
    "message": "Success",
    "user_balance": "100.00",
    "version": "api-version-1",
    "data": {
        "id": 1,
        "name": "test-name-2",
        "model": "test-model-22",
        "url": "http://test-url-2.com",
        "year": "test-year-2",
        "deleted_at": null,
        "created_at": "2022-08-30 15:14:24",
        "updated_at": "2022-08-30 15:21:03"
    }
}
```


### [DELETE] `/api/brands/{id}?version=1`

`id`si verilen markayı geçici olarak siler.

**Örnek Kullanım:**

```console
curl --location --request DELETE 'http://127.0.0.1:8000/api/brands/2?version%3D1' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer 1|uZbQqOUqn2aH9R2UMbdaMoALRGNBSoynQIe20psB' \
--data-raw ''
```

**Cevap:**

```json
{
    "code": 200,
    "message": "Success",
    "user_balance": "100.00",
    "version": "api-version-1",
    "data": {
        "message": "Deleted"
    }
}
```
