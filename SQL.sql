CREATE DATABASE IF NOT EXISTS walk_hiroshima;
USE walk_hiroshima;

CREATE TABLE user_management (
    user_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_name varchar(20) NOT NULL UNIQUE,
    user_password varchar(200) NOT NULL,
    registration_date datetime DEFAULT NULL,
    update_date datetime DEFAULT NULL,
    deletion_date datetime DEFAULT NULL,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE article_management (
    article_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    post_date datetime NOT NULL,
    user_id int(11) NOT NULL,
    category_id tinyint(1) NOT NULL,
    season_id tinyint(1) NOT NULL,
    title varchar(100) NOT NULL,
    text text NOT NULL,
    point_x varchar(20),
    point_y varchar(20),
    registration_date datetime NOT NULL,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE selected_season_management (
    selected_season_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_id int(11) NOT NULL,
    season_id tinyint(1) NOT NULL,
    selected_date datetime,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE read_status_management (
    read_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_id int(11) NOT NULL,
    user_id int(11) NOT NULL,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE image_management (
    image_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_id int(11) NOT NULL,
    display_order tinyint(1) NOT NULL,
    image_url text NOT NULL,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE category_management (
    category_id  tinyint(1) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    category_name varchar(12) NOT NULL UNIQUE,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE season_management (
    season_id tinyint(1) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    season_name char(2) NOT NULL UNIQUE,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

CREATE TABLE image_management (
    image_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    article_id int(11) NOT NULL,
    image_url varchar(200) NOT NULL UNIQUE,
    registration_date datetime,
    update_date datetime,
    deletion_date datetime,
    deletion_flag boolean DEFAULT 0
);

INSERT INTO category_management (category_id, category_name)
VALUES (1, 'イベント'), (2, '情報'), (3, '発見'), (4, '連絡');

INSERT INTO season_management (season_id, season_name)
VALUES (1, '春'), (2,'夏'), (3, '秋'), (4, '冬');

INSERT INTO article_management (
    article_id,
    post_date,
    user_id,
    category_id,
    season_id,
    title,
    text,
    point_x,
    point_y,
    registration_date,
    update_date,
    deletion_date,
    deletion_flag
) VALUES (
    1,
    '2023-11-03 23:10:43',
    1,
    2,
    1,
    '○○神社　秋まつり',
    '今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！\n日時：2024年10月23日（日）\n場所：○○神社\n所在地：広島市東区○○×ー×ー×\nお問い合わせ先：□□□－□□□－□□□□\n担当：相原',
    '132.47376482651518',
    '34.400732500965574',
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    2,
    '2023-12-16 10:38:51',
    2,
    4,
    3,
    '○○図書館前の猫',
    '昨日のお昼過ぎに散歩をしていると○○図書館の前の道で猫と出会いました。\n最初は他の歩行者の方に話しかけられていましたが、私と目が合うとこちらに走ってきて体を擦りつけてきました。\n今までこのようなことがなかったので、驚きました。\n毛並みがきれいだったので、飼い主さんがいらっしゃるのでしょうか、他に目撃された方はいらっしゃいますか？\nまた会いたいです。',
    '132.4686783758275',
    '34.39710317615621',
    '2023-12-16 10:38:51',
    NULL,
    NULL,
    0
), (
    3,
    '2024-01-26 20:56:19',
    3,
    1,
    2,
    'この花の名前は何でしょうか？',
    '見たことのない花が咲いていました。\n毎年ここに咲くのでしょうか。\nとてもきれいで花の名前が知りたいのですが、検索をしても似ている花が多くあり、判別がつきません。\nもしお分かりの方がいらっしゃれば、教えていただけると嬉しいです。',
    '132.4794736699031',
    '34.40296312981465',
    '2024-01-26 20:56:19',
    NULL,
    NULL,
    0
), (
    4,
    '2023-02-18 16:04:28',
    4,
    3,
    4,
    '○○公園でピクニック',
    '今日は暖かかったので、頂き物の紅茶と自宅で焼いたクッキーを持って、公園でピクニックをしました。\n日差しが暖かく、風もなく空気も澄んでいて、とても良いリフレッシュになりました。\n帰りに○○川沿いを歩いていると、梅の花の蕾が膨らんでいました。\n春の訪れを感じます。',
    '132.46567385405697',
    '34.402416155877596',
    '2023-02-18 16:04:28',
    NULL,
    NULL,
    0
), (
    5,
    '2023-03-04 23:10:09',
    2,
    1,
    4,
    'フリーマーケット開催',
    'フリーマーケットを開催します。\n古着や古本、手作りのお菓子や雑貨など、ここでしか出会えないものを探しに来られませんか？\nみなさまのお越しをお待ちしております！\n日時：2024年4月1日（日）　10:00～17:00\n場所：○○モール駐車場\n所在地：広島市中区○○×ー×ー×\nお問い合わせ先：□□□－□□□－□□□□\n担当：相原',
    '132.47376482651518',
    '34.400732500965574',
    '2023-03-04 23:10:09',
    NULL,
    NULL,
    0
), (
    6,
    '2023-05-17 09:03:44',
    4,
    3,
    1,
    'パン屋「○○ベーカリー」NEW OPEN',
    'はじめまして\n「○○ベーカリー」と申します。\n2024年6月から、広島市西区○○に新しくパン屋をOPENします。\nパリで3年間修行して、生まれ育った広島に戻ってまいりました。\n「和と洋の融合」をコンセプトにした新しいパン屋です。\nご来店をお待ちしております！\n開店日：2024年10月23日（日）8:00～\n営業時間：8:00～16:00¥\n定休日：金曜日r¥n場所：○○神社\n所在地：広島市東区○○×ー×ー×\nお問い合わせ先：□□□－□□□－□□□□\n担当：相原',
    '132.45133736013554',
    '34.39572404719143',
    '2023-05-17 09:03:44',
    NULL,
    NULL,
    0
), (
    7,
    '2023-11-03 23:10:43',
    1,
    2,
    3,
    '○○神社　秋まつり',
    '今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！\n日時：2024年10月23日（日）\n場所：○○神社\n所在地：広島市東区○○×ー×ー×\nお問い合わせ先：□□□－□□□－□□□□\n担当：相原',
    '132.47376482651518',
    '34.400732500965574',
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    8,
    '2023-11-03 23:10:43',
    3,
    4,
    2,
    '○○神社　秋まつり', '今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！\n日時：2024年10月23日（日）\n場所：○○神社\n所在地：広島市東区○○×ー×ー×\nお問い合わせ先：□□□－□□□－□□□□\n担当：相原',
    '132.47376482651518',
    '34.400732500965574',
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    9,
    '2023-11-03 23:10:43',
    2,
    1,
    1,
    '○○神社　秋まつり', '今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！\n日時：2024年10月23日（日）\n場所：○○神社\n所在地：広島市東区○○×ー×ー×\nお問い合わせ先：□□□－□□□－□□□□\n担当：相原',
    '132.47376482651518',
    '34.400732500965574',
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
);

INSERT INTO user_management (
    user_id,
    user_name,
    user_password,
    registration_date,
    update_date,
    deletion_date,
    deletion_flag
) VALUES (
    1,
    'purin_daisuki',
    '1111',
    '2020-01-01',
    '2020-01-01',
    NULL,
    0
), (
    2,
    'keki_no_hougasuki',
    '1111',
    '2020-01-01',
    '2020-01-01',
    NULL,
    0
), (
    3,
    'wagashi_ga_ichiban',
    '1111',
    '2020-01-01',
    '2020-01-01',
    NULL,
    0
), (
    4,
    'amai_mono_kirai',
    '1111',
    '2020-01-01',
    '2020-01-01',
    NULL,
    0
);

INSERT INTO image_management (
    image_id,
    article_id,
    image_url,
    registration_date,
    update_date,
    deletion_date,
    deletion_flag
) VALUES (
    1,
    1,
    "upload/purin_daisuki-01.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
),(
    2,
    1,
    "upload/purin_daisuki-02.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    3,
    1,
    "upload/purin_daisuki-03.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    4,
    2,
    "upload/keki_no_hougasuki-01.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    5,
    2,
    "upload/keki_no_hougasuki-02.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    6,
    3,
    "upload/wagashi_ga_ichiban-01.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    7,
    2,
    "upload/keki_no_hougasuki-03.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    8,
    4,
    "upload/amai_mono_kirai-01.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    9,
    4,
    "upload/amai_mono_kirai-02.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    10,
    4,
    "upload/amai_mono_kirai-03.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    11,
    3,
    "upload/wagashi_ga_ichiban-02.JPG",
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
);

INSERT INTO read_status_management (
    read_id,
    article_id,
    user_id,
    registration_date,
    update_date,
    deletion_date,
    deletion_flag
) VALUES (
    1,
    1,
    1,
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
), (
    2,
    5,
    1,
    '2023-11-03 23:10:43',
    NULL,
    NULL,
    0
);
