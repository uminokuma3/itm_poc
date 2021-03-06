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
if ($text == '一覧') {
  $response_format_text = [
    "type" => "template",
    "altText" => "機能一覧",
    "template" => [
      "type" => "buttons",
      "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img1.jpg",
      "title" => "機能一覧",
      "text" => "ご希望の機能をお選びください",
      "actions" => [
          [
            "type" => "postback",
            "label" => "QAを検索する",
            "data" => "action=buy&itemid=123"
          ],
          [
            "type" => "postback",
            "label" => "連絡先を調べる",
            "data" => "action=pcall&itemid=123"
          ],
          [
            "type" => "uri",
            "label" => "サーバステータスを見る",
            "uri" => "https://" . $_SERVER['SERVER_NAME'] . "/"
          ],
          [
            "type" => "message",
            "label" => "その他",
            "text" => "_その他"
          ]
      ]
    ]
  ];
} else if ($text == '_その他') {
  $response_format_text = [
    "type" => "template",
    "altText" => "候補を３つご案内しています。",
    "template" => [
      "type" => "carousel",
      "columns" => [
          [
            "thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img2-1.jpg",
            "title" => "ダミー機能１",
            "text" => "こちらにしますか？",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "検索する",
                  "data" => "action=rsv&itemid=111"
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
            "title" => "ダミー機能２",
            "text" => "それともこちら？（２つ目）",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "検索する",
                  "data" => "action=rsv&itemid=222"
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
            "title" => "ダミー機能３",
            "text" => "はたまたこちら？（３つ目）",
            "actions" => [
              [
                  "type" => "postback",
                  "label" => "検索する",
                  "data" => "action=rsv&itemid=333"
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
} else if ($text == '_不要') {
  exit;

//QA一覧ここから
} else if ($text == '営業の連絡先') {
  $response_format_text = [
    "type" => "text",
    "text" => "営業の連絡先は46-9342です。",
  ];
} else if ($text == '営業') {
  $response_format_text = [
    "type" => "text",
    "text" => "営業の連絡先は46-9342です。",
  ];
} else if ($text == '目黒の連絡先') {
  $response_format_text = [
    "type" => "text",
    "text" => "目黒ITM席の連絡先は外線8424です。",
  ];
} else if ($text == '目黒') {
  $response_format_text = [
    "type" => "text",
    "text" => "目黒ITM席の連絡先は外線8424です。",
  ];
} else if ($text == 'ソフィア') {
  $response_format_text = [
    "type" => "text",
    "text" => "ソフィアの連絡先は03-xxxx-xxxxです。",
  ];
} else if ($text == 'こんにちは') {
  $response_format_text = [
    "type" => "text",
    "text" => "こんにちは！",
  ];
} else if ($text == 'さようなら') {
  $response_format_text = [
    "type" => "text",
    "text" => "さようなら！",
  ];
} else if ($text == 'おはよう') {
  $response_format_text = [
    "type" => "text",
    "text" => "おはようございます！",
  ];
} else if ($text == 'りそな') {
  $response_format_text = [
    "type" => "text",
    "text" => "りそなの本社は大阪府大阪市中央区備後町二丁目2番1号です。",
  ];
//QA一覧ここまで
} else {
  $response_format_text = [
    "type" => "template",
    "altText" => "こんにちは 何かご用ですか？（はい／いいえ）",
    "template" => [
        "type" => "confirm",
        "text" => "「" . $text . "」について検索しましたが回答が見つかりませんでした。一覧から選択しますか？",
        "actions" => [
            [
              "type" => "message",
              "label" => "はい",
              "text" => "一覧"
            ],
            [
              "type" => "message",
              "label" => "いいえ",
              "text" => "_不要"
            ]
        ]
    ]
  ];
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
