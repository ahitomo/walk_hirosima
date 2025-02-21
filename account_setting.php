<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1101px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1100px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:480px)" href="css/style_mobile.css">
    <script src="js/script.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="GIMP/歩く鳥.png">
    <title>歩こう広島｜アカウント管理</title>
</head>
<body class="background_color">
    <header class="flex">
        <a href="account_top.php">
            <img class="header_bird" src="GIMP/歩く鳥.png" alt="歩く鳥">
            <img class="header_logo" src="GIMP/ロゴ.png" alt="ロゴ">
        </a>
        <input type="checkbox" id="header_menu_guide" class="display_none">
        <label for="header_menu_guide">menu</label>
        <div id="header_menu">
            <ul class="flex wrap">
                <li><a href="bulletin_board.php">掲示板</a></li>
                <li><a href="information_map.php">情報マップ</a></li>
                <li><a href="article_management.php">記事管理</a></li>
                <li><a href="account_setting.php">アカウント管理</a></li>
                <li><a href="index.php">ログアウト</a></li>
            </ul>
        </div>
    </header>
    <main id="account_setting">
        <h2>アカウント管理</h2>
        <div class="flex space_between">
            <div class="account_setting_container background_color_blue margin_0">
                <p>アカウント編集</p>
                <form id="account_setting_form" action="index.php">
                    <ul>
                        <li class="flex align_items_center">
                            <p class="width_220px">変更</p>
                            <div class="margin_left_2vw">
                                <div class="flex margin_0">
                                    <input type="checkbox" id="user_id" name="change_type">
                                    <label for="user_id">ユーザーID</label>
                                </div>
                                <div class="flex margin_0">
                                    <input type="checkbox" id="password" name="change_type">
                                    <label for="password">パスワード</label>
                                </div>
                            </div>
                        </li>
                        <li class="flex">
                            <label for="current_user_id" class="width_220px">現在のユーザーID</label>
                            <input class="margin_left_2vw" type="text" name="current_user_id" readonly>
                        </li>
                        <li class="flex">
                            <label for="new_user_id" class="width_220px">新しいユーザーID</label>
                            <input class="margin_left_2vw" type="text" name="new_user_id">
                        </li>
                        <li class="flex">
                            <label for="current_password" class="width_220px">現在のパスワード</label>
                            <input class="margin_left_2vw" type="text" name="current_password" readonly>
                        </li>
                        <li class="flex">
                            <label for="new_password" class="width_220px">新しいパスワード</label>
                            <input class="margin_left_2vw" type="text" name="new_password">
                        </li>
                        <li class="flex">
                            <label for="confirm_password" class="width_220px">新しいパスワード（確認用）</label>
                            <input class="margin_left_2vw" type="text" name="confirm_password" style="height: 19px;">
                        </li>
                    </ul>
                    <div class="flex justify_content_flex_end">
                        <button id="save_setting_button" type="submit">変更を保存</button>
                    </div>
                </form>
            </div>
            <div class="account_setting_container background_color_blue margin_0">
                <p>アカウント削除</p>
                <div id="account_delete_container" class="flex align_items_center">
                    <button id="account_delete_button" type="button">アカウント削除</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>