# フリマアプリ

## 環境構築

**Docker ビルド**

1. `git clone git@github.com:tkdisk49/flea-market.git`
2. `docker-compose up -d --build`

> Mac の M3 チップの PC で構築しているので、docker-compose.yml ファイルの「mysql」「phpmyadmin」「mailhog」内に「platform」の項目を追加しています。
> エラーが発生する場合は、「platform」の項目を削除して再度ビルドを実行してください。

```text
mysql:
    image: mysql:8.0.26
    platform: linux/amd64(この文を削除)

phpmyadmin:
    image: phpmyadmin/phpmyadmin
    platform: linux/amd64(この文を削除)

mailhog:
    image: mailhog/mailhog
    platform: linux/amd64(この文を削除)
```

**Laravel 環境構築**

1. `docker-compose exec php bash`
2. `composer install`

> composer install の際にエラーが発生する場合は、composer update を実行してから再度インストールしてください。

```bash
composer update
```

3. 「.env.example」ファイルから「.env」を作成し、環境変数を変更

```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

4. アプリケーションキーの作成

```bash
php artisan key:generate
```

5. マイグレーションの実行

```bash
php artisan migrate
```

6. シンボリックリンクの作成

```bash
php artisan storage:link
```

7. シーディングの実行

```bash
php artisan db:seed
```

## 使用技術（実行環境）

- PHP 8.3.11
- Laravel 8.83.29
- MySQL 8.0.26

## ER 図

![alt](erd.png)

## URL

- 開発環境:http://localhost/
- phpmyadmin:http://localhost:8080/
- mailhog:http://localhost:8025/
