<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';

session_start();
if(isset($_SESSION['user_name']) !== "") {
    $user_name = $_SESSION['user_name'];
}

// 検索条件を削除する
if (!strpos($url, "/article_management.php") AND !strpos($url, "/article.php") AND !strpos($url, "/edit.php")) {
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1221px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1220px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:428px)" href="css/style_mobile.css">
    <script src="js/script.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="image/walking_bird.png">
    <title>歩こう広島｜アカウントトップ</title>
</head>
<body id="account_top">
    <header class="flex">
        <a href="account_top.php">
            <img class="header_bird" src="image/walking_bird.png" alt="歩く鳥">
            <img class="header_logo" src="image/header_logo.png" alt="ロゴ">
        </a>
        <input type="checkbox" id="header_menu_guide" class="display_none">
        <label for="header_menu_guide">menu</label>
        <div id="header_menu">
            <ul class="flex wrap">
                <li><a href="bulletin_board.php">掲示板</a></li>
                <li><a href="information_map.php">情報マップ</a></li>
                <li><a href="article_management.php">記事管理</a></li>
                <li><a href="account_setting.php">アカウント管理</a></li>
                <li><a id="logout" href="logout.php" onclick="return CheckLogout()">ログアウト</a></li>
            </ul>
        </div>
    </header>
    <main class="main">
        <h2>
            <span>
                <?php 
                    if (isset($user_name)) {
                        echo $user_name;
                    } else {
                        echo 'ゲスト';
                        }
                ?>
            </span>さんのページ
        </h2>
        <ul class="flex justify_content_center">
            <li><button id="bulletin_board_link" type="button" onclick="location.href='bulletin_board.php'">掲示板</button></li>
            <li><button id="information_map_link" type="button" onclick="location.href='information_map.php'">情報マップ</button></li>
            <li><button id="upload_link" type="button" onclick="location.href='article_management.php'">記事管理</button></li>
            <li><button id="account_setting_link" type="button" onclick="location.href='account_setting.php'">アカウント管理</button></li>
        </ul>
    </main>

    <script>
        let UserName = <?php if ($user_name) {echo "'" . $user_name . "'";} else {echo "''";}?>;
        // console.log(UserName);
        if (UserName == '') {
            let headerLink = document.querySelectorAll("li");
            // console.log(headerLink);
            headerLink[2].classList.add("display_none");
            headerLink[3].classList.add("display_none");
            headerLink[7].classList.add("display_none");
            headerLink[8].classList.add("display_none");
        }
    </script>
</body>
</html>