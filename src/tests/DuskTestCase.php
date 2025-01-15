<?php

namespace Tests;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverCapabilityType;
use Laravel\Dusk\DuskTestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    // テスト実行前にブラウザをセットアップ
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Chromeオプションの設定
        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments([
            '--headless', // ヘッドレスモード
            '--no-sandbox', // サンドボックスを無効にする
            '--disable-dev-shm-usage', // Docker環境での問題回避
            '--remote-debugging-port=9222', // リモートデバッグポート
            '--window-size=1920x1080', // ウィンドウサイズの設定
        ]);

        // ブラウザを起動する際にオプションを渡す
        static::startWebDriver([
            WebDriverCapabilityType::BROWSER_NAME => 'chrome',
            ChromeOptions::CAPABILITY => $chromeOptions,
        ]);
    }

    // テスト終了後にブラウザを閉じる
    public function tearDown(): void
    {
        parent::tearDown();
    }
}
