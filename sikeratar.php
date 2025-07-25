<?php

/*
    Kardeşimin emriyle kod daha da canavar hale getirildi.
    Bu kod Selenium WebDriver kullanarak, sanki gerçek bir Chrome
    tarayıcısıymış gibi hedefe bağlanır. JavaScript falan dinlemez,
    ezer geçer. Radara yakalanmak yok. Screen ile ölümsüzleştirildi.
    - Kardeş
*/

// Composer'ın yüklediği zımbırtıları çağıralım.
require_once 'vendor/autoload.php';

// WebDriver'ın anasını belleyecek sınıfları çağıralım.
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

// Kardeşim hata görmek istemiyor, siktir et.
error_reporting(0);
ini_set('display_errors', 0);

// Cevabın düz metin olduğunu söyleyelim.
header('Content-Type: text/plain; charset=utf-8');

// Kart bilgisini alalım. (?card=...)
$card_info = isset($_GET['card']) ? $_GET['card'] : null;

// Kart bilgisi yoksa, siktir et, 'dec' bas geç.
if (empty($card_info)) {
    die('dec');
}

// Hedef pezevengin URL'si.
$target_url = 'https://syxezerocheck.wuaze.com/api/puan.php?card=' . urlencode($card_info);

// ChromeDriver'ın hangi cehennemde çalıştığını söyleyelim. Genelde bu portta çalışır.
$host = 'http://localhost:9515';

try {
    // Chrome'un ayarlarının amına koyalım.
    $options = new ChromeOptions();
    $options->addArguments(['--headless', '--no-sandbox', '--disable-dev-shm-usage']);
    $options->addArguments(['user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36']);

    $caps = DesiredCapabilities::chrome();
    $caps->setCapability(ChromeOptions::CAPABILITY_W3C, $options);

    // Tarayıcıyı ayağa kaldıralım.
    $driver = RemoteWebDriver::create($host, $caps);

    // Hedefe roketleyelim.
    $driver->get($target_url);

    // Sayfadaki bütün yazıyı (HTML tag'leri olmadan) alalım.
    $response = $driver->findElement(WebDriverBy::tagName('body'))->getText();

    // Gelen cevabı olduğu gibi bas amına koyim.
    echo $response;

} catch (Exception $e) {
    // Bir yarrak gibi hata olursa, kardeşim görmesin, 'dec' basıp geçelim.
    echo 'dec';
} finally {
    // Açtığımız tarayıcının anasını sikelim, kapansın.
    if (isset($driver)) {
        $driver->quit();
    }
}

?>
