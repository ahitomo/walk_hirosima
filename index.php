<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1221px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1220px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:430px)" href="css/style_mobile.css">
    <link rel="icon" type="image/png" sizes="32x32" href="GIMP/歩く鳥.png">
    <title>歩こう広島</title>  
</head>
<body class="background_img position_relative">
    <header id="header" class="flex">
        <a href="index.php">
            <img class="header_bird" src="GIMP/歩く鳥.png" alt="歩く鳥">
            <img class="header_logo" src="GIMP/ロゴ.png" alt="ロゴ">
        </a>
        <input type="checkbox" id="header_menu_guide" class="display_none">
        <label for="header_menu_guide">menu</label>
        <div id="header_menu">
            <ul class="flex">
                <li><a id="header_login" href="#login">ログイン</a></li>
                <li><a id="header_create_account" href="#create_account">アカウント作成</a></li>
                <li><a id="header_guest" href="account_top.php">ゲスト</a></li>
            </ul>
        </div>
    </header>
    <main class="main_height flex justify_content_center">
        <div id="top_image">
            <div id="main_image" class="position_relative">
                <img id="river_sparkle" class="position_absolute" src="GIMP/川のきらめきと四季の花.png" alt="川のきらめきと四季の花">
                <img id="main_logo" src="GIMP/メインロゴ.png" alt="メインロゴ">
            </div>
            <div id="navi_menu" class="display_none">
                <ul>
                    <li><a id="main_login" href="#login">ログイン</a></li>
                    <li><a id="main_create_account" href="#create_account">アカウント作成</a></li>
                    <li><a id="main_guest" href="account_top.php">ゲスト</a></li>
                </ul>
            </div>
            <div id="login" class="display_none">
                <form action="account_top.php">
                    <div>
                        <label for="login_user_id">ユーザーID</label>
                        <input type="text" name="login_user_id" id="login_user_id">
                    </div>
                    <div>
                        <label for="login_password">パスワード</label>
                        <input type="text" name="login_password" id="login_password">
                    </div>
                    <div class="flex justify_content_center">
                        <button id="login_button" type="submit">ログイン</button>
                    </div>
                </form>
            </div>
            <div id="create_account" class="display_none">
                <form action="account_top.php">
                    <div>
                        <label for="create_account_user_id">ユーザーID</label>
                        <input type="text" name="create_account_user_id" id="create_account_user_id">
                    </div>
                    <div>
                        <label for="create_account_password">パスワード</label>
                        <input type="text" name="create_account_password" id="create_account_password">
                    </div>
                    <div class="flex justify_content_center">
                        <button id="create_account_button" type="submit">アカウント作成</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>

</body>
</html>