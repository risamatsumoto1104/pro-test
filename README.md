# coachtech-fleamarket（coachtech フリマアプリ）

## 環境構築

**Docker ビルド**

1. `git clone git@github.com:risamatsumoto1104/coachtech-fleamarket.git`
2. DockerDesktop アプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel 環境構築**

1. PHP コンテナ内にログイン
   `docker-compose exec php bash`
2. パッケージをインストールします。

```bash
composer install
```

3. 「.env.example」ファイルをコピーして「.env」ファイルを作成します。

```bash
cp .env.example .env
```

4. .env に以下の環境変数を変更。
   strip 決済を使用しているため、.env ファイルに STRIPE_KEY=(公開可能キー)と STRIPE_SECRET=（シークレットキー）を追加してください。

```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

# Stripe keys（追加）
STRIPE_KEY=pk_test_..................
STRIPE_SECRET=sk_test_.................
```

5. 本番環境と、テスト環境の APP_KEY=にを削除。
   新たなアプリケーションキーを作成します。
   キャッシュの削除も行ってください。

```bash
php artisan key:generate
php artisan key:generate --env=testing
php artisan config:clear
```

6. マイグレーションファイルと、ダミーデータの作成を行います。

```bash
php artisan migrate --seed
```

7. storege 内のファイルを使用するため、シンボリックリンクを作成します。

```bash
php artisan storage:link
```

## 使用技術(実行環境)

- DockerCompose 3.8
- Laravel 8.x
- nginx 1.21.1
- PHP 7.4.9
- MySQL 8.0.26
- phpmyadmin
- mailhog
- selenium

## メールを用いた認証テスト（MailHog を使用）

1. ユーザーの「新規登録」または、「ログイン」を行います。
2. 以下を実行しようとすると、メール認証コードを送信します。
   - 「マイページ」を押下
   - 「出品」を押下
   - 商品詳細より「購入手続きへ」を押下
   - 「★ いいねアイコン」を押下
   - 「コメントを送信する」を押下
3. メール認証コード送信完了ページに遷移したことを確認して、MailHog にてメールを確認して下さい。
4. 「メール認証」ボタンを押下して、ブラウザにアクサスされる。
   又は、phpMyAdmin にて登録したユーザーの email_verified_at に日付が記載されていれば成功です。

## stripe 決済テスト

```text
ダミーデータの item_id は以下の通りです。
    - 1.腕時計
    - 2.HDD
    - 3.玉ねぎ3束
    - 4.革靴
    - 5.ノートPC
    - 6.マイク
    - 7.ショルダーバッグ
    - 8.タンブラー
    - 9.コーヒーミル
    - 10.メイクセット
```

1. カード支払いを選択して「購入ボタン」を押下した場合のテスト

   - カード入力画面が表示されます。以下の項目を入力してください

```text
メールアドレス：任意のアドレス
カード情報（番号）：4242 4242 4242 4242
カード情報（日付）：任意の将来の日付
カード情報（セキュリティコード）：任意の3桁の数字
カード保有者の名前：任意の氏名
```

    - 支払を押した後、http://localhost/purchase/success/{item_id}に接続します。
    - stripe 公式ページの「取引」より成功を確認してください。

1. コンビニ支払いを選択して「購入ボタン」を押下した場合のテスト

   - 入力画面が表示されます。以下の項目を入力してください

```text
メールアドレス：任意のアドレス
名前：任意の氏名
```

    - 支払を押した後、テスト環境でのコンビニ決済画面に接続します。
    - 実際の支払はできない為、http://localhost/purchase/success/{item_id}に接続して、購入を完了させて下さい。
    - stripe 公式ページの「取引」より成功を確認してください。※取引が成功するまでに3分かかります。

## PHPunit を用いたテスト

1. PHP コンテナ内にログイン
   `docker-compose exec php bash`

2.テスト用のマイグレーションファイルを作成します。

```bash
php artisan migrate --env=testing
```

3. 以下のテストを行います。テストを実行してください。

```text
- 会員登録機能
- ログイン機能
- ログアウト機能
- 商品一覧取得
- マイリスト一覧取得
- 商品検索機能
- 商品詳細情報取得
- いいね機能
- コメント送信機能
- 商品購入機能
- 配送先変更機能
- ユーザー情報取得、ユーザー情報変更
- 出品商品情報登録

- 支払方法選択機能　 ⇒ 環境設定ができなかったため、テストが実行されません。
```

```bash
vendor/bin/phpunit
```

## ER 図

![alt](er.png)

## URL

- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
- stripe：https://dashboard.stripe.com/test/dashboard
- MailHog：http://localhost:8025/
