<?php

//echo "Hello World!"
// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__ . '/vendor/autoload.php';

// POSTメソッドで渡される値を取得、表示
//$inputString = file_get_contents('php://input');
//error_log($inputString);

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));

// CurlHTTPClientをシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

// 署名が正当化チェック。正当であればリクエストをパースし配列へ
$events = $bot->parseEventRequest(file_get_contents('php://input'),$signature);

foreach ($events as $event) {
    // テキストを返信
    $bot->replyText($event->getReplyToken(), 'まさひろくん だよー');
    // 画像を送信
    //replyImageMessage($bot, $event->getReplyToken(),
    //'https://' . $_SERVER['HTTP_HOST'] . '/imgs/original.jpg',
    //'https://' . $_SERVER['HTTP_HOST'] . '/imgs/preview.jpg');
    //replyLocationMessage($bot, $event->getReplyToken(), 'LINE', '富山県富山市柳町1丁目1番11号',36.693650, 137.222321);

}

// テキストを返信。引数はLINEBot、返信先、テキスト
function replyTextMessage($bot, $replyToken, $text) {
    // 返信を行いレスポンスを取得
    // TextMessageBuilderの引数はテキスト
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));
    // レスポンスが異常な場合
    if (!$response->isSucceeded()) {
      // エラー内容を出力
      error_log('Failed! '. $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }

// 画像を返信。引数はLINEBot、返信先、画像URL、サムネイルURL
function replyImageMessage($bot, $replyToken, $originalImageUrl, $previewImageUrl) {
    // ImageMessageBuilderの引数は画像URL、サムネイルURL
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));
    if (!$response->isSucceeded()) {
      error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }
  
// 位置情報を返信。引数はLINEBot、返信先、タイトル、住所、
// 緯度、経度
function replyLocationMessage($bot, $replyToken, $title, $address, $lat, $lon) {
    // LocationMessageBuilderの引数はダイアログのタイトル、住所、緯度、経度
    $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\LocationMessageBuilder($title, $address, $lat, $lon));
    if (!$response->isSucceeded()) {
      error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
    }
  }
  
?>
