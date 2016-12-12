<?php
$accessToken = getenv('LINE_CHANNEL_ACCESS_TOKEN');


//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

$type = $jsonObj->{"events"}[0]->{"message"}->{"type"};
//メッセージ取得
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

//メッセージ以外のときは何も返さず終了
if($type != "text"){
	exit;
}

//返信データ作成
if (strstr($text, 'チェッ君') ) {
  $response_format_text = [
    "type" => "template",
    "altText" => "精算申請があるのですね。",
    "template" => [
        "type" => "confirm",
        "text" => "みくりさん、精算申請があるのですね。",
        "actions" => [
            [
              "type" => "message",
              "label" => "はい",
              "text" => "はい、あるんです。"
            ],
            [
              "type" => "message",
              "label" => "いいえ",
              "text" => "いいえ、ありません。"
            ]
        ]
    ]
  ];
} else if (strstr($text, 'はい') {
  $response_format_text = [
    "type" => "template",
    "altText" => "精算項目はなんでしょうか？",
    "template" => [
      "type" => "buttons",
      "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/mikuri1.jpg",
      "title" => "みくりさんは何にお金を使ったのですか？",
      "text" => "精算項目はなんでしょうか？",
      "actions" => [
          [
            "type" => "message",
            "label" => "飲食・軽食代",
            "data" => "飲食・軽食代です！"
          ],
          [
            "type" => "message",
            "label" => "備品代",
            "data" => "備品代です！"
          ],
          [
            "type" => "message",
            "label" => "ガソリン代",
            "uri" => "備品代です！"
          ],
          [
            "type" => "message",
            "label" => "雇用主でも聞いていいことと悪いことがあると思います",
            "text" => "雇用主でも聞いていいことと悪いことがあると思います"
          ]
      ]
    ]
  ];
} else if ($text == 'いいえ、ありません。') {
  exit;
} else if ($text == '違うやつお願い') {
  $response_format_text = [
    "type" => "template",
    "altText" => "候補を３つご案内しています。",
    "template" => [
      "type" => "carousel",
      "columns" => [
          [
            "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img2-1.jpg",
            "title" => "●●レストラン",
            "text" => "こちらにしますか？",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "予約する",
                  "data" => "action=rsv&itemid=111"
              ],
              [
                  "type" => "postback",
                  "label" => "電話する",
                  "data" => "action=pcall&itemid=111"
              ],
              [
                  "type" => "uri",
                  "label" => "詳しく見る（ブラウザ起動）",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ],
          [
            "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img2-2.jpg",
            "title" => "▲▲レストラン",
            "text" => "それともこちら？（２つ目）",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "予約する",
                  "data" => "action=rsv&itemid=222"
              ],
              [
                  "type" => "postback",
                  "label" => "電話する",
                  "data" => "action=pcall&itemid=222"
              ],
              [
                  "type" => "uri",
                  "label" => "詳しく見る（ブラウザ起動）",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ],
          [
            "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img2-3.jpg",
            "title" => "■■レストラン",
            "text" => "はたまたこちら？（３つ目）",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "予約する",
                  "data" => "action=rsv&itemid=333"
              ],
              [
                  "type" => "postback",
                  "label" => "電話する",
                  "data" => "action=pcall&itemid=333"
              ],
              [
                  "type" => "uri",
                  "label" => "詳しく見る（ブラウザ起動）",
                  "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
              ]
            ]
          ]
      ]
    ]
  ];
} else {
  exit;
}

$post_data = [
	"replyToken" => $replyToken,
	"messages" => [$response_format_text]
	];

$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
    ));
$result = curl_exec($ch);
curl_close($ch);
