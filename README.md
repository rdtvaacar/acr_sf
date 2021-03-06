#  LARAVEL - Acr_sf
## Gerekli 
http://image.intervention.io/getting_started/installation
composer require acr/acr_fl
## Kurulum:
#### composer json : 
```
"acr/Acr_sf": "dev-blog"
```


#### Providers
```
 Acr\sf\Acr_sfServiceProvider::class,
```
#### Aliases
```
'Acr_sf' => \Acr\sf\Facades\Acr_sf::class,
```
#### public_html/Acr_sf/blog.blade.php
```php
@extends('index')  // default extends page
@section('acr_index') // default yield
    @yield('Acr_sf') // blog yield dont edit
@stop
```

```php
 {!! AcrFile::css() !!}  
```
CSS dosyalarını yükler.
```php 
PHP
{!! AcrFile::form() !!}
```
Formu yükler
```php 
PHP
$acr_file_id = Acr_sf::acr_file_id();
$fl_data = [
    'acr_file_id' => $acr_file_id,
]
```

```php 
{!! Acr_sf::get_file($acr_file_id, $file_name, $loc = '') !!}
```
Dosyayı basar

```php 
{!! Acr_sf::files_list($acr_file_id) !!}
```
Dosyaları Listeler
```php 
{!! Acr_sf::files_galery($acr_file_id) !!}
```
Dosyaları galeri şeklinde Listeler

acr_file_id gönderimi şarttır, ek data gönderilebilir. İlişkili tablodan gelmeli örneğin ürünler için kullanacaksanız urun tablonuzda acr_file_id stunu olmalı, acr_file_id değişkeni null ise : $acr_file_id = Acr_sf::acr_file_id() yeni bir acr_file_id oluşturmanız ve ürünler tablosundaki acr_file_id stununa eklemeniz beklenmektedir.
```php 
PHP
{!! AcrFile::js($fl_data) !!}
```
Java script dosylarını yükler.

```sql 
CREATE TABLE `acr_files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `file_dir` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

CREATE TABLE `acr_files_childs` (
  `id` int(11) NOT NULL,
  `acr_file_id` int(11) DEFAULT NULL,
  `old_id` int(11) DEFAULT NULL,
  `sira` int(11) NOT NULL DEFAULT '10',
  `goster` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 gösterilmiyor 1 gösteriliyor',
  `file_name` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `file_name_org` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `file_size` varchar(25) COLLATE utf8_turkish_ci DEFAULT NULL,
  `file_type` varchar(10) COLLATE utf8_turkish_ci DEFAULT NULL,
  `mime` varchar(200) COLLATE utf8_turkish_ci DEFAULT NULL,
  `download` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `acr_files`
--
ALTER TABLE `acr_files`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `acr_files_childs`
--
ALTER TABLE `acr_files_childs`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `acr_files`
--
ALTER TABLE `acr_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1231;

--
-- Tablo için AUTO_INCREMENT değeri `acr_files_childs`
--
ALTER TABLE `acr_files_childs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=920;
COMMIT;
```

