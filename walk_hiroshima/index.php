<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';
session_start();
// 検索条件を削除する

// アカウント作成
if (isset($_POST['create_account'])) {
    $create_account_user_name = $_POST['create_account_user_name'];
    // echo "<br><br><br>";
    // echo $create_account_user_name;
    if (preg_match('/\A\w{0,20}\z/', $create_account_user_name)) {
        $user_id = $dbm->get_user_id($create_account_user_name);
        // echo $user_id;
        // アカウントがすでに存在している場合
        if ($user_id) {
            // echo "<br><br><br>";
            echo '<script type="text/javascript">alert("すでに存在しているアカウントです");</script>';
        } else {
            // echo "<br><br><br>";
            // echo 'user id = ' . $user_id;
            // echo 'アカウントを作成します';
            $create_account_password = $_POST['create_account_password'];
            echo $create_account_password;
            if (preg_match('/\A[a-zA-Z0-9]{8,200}\z/', $create_account_password)) {
                $password_hash = password_hash($create_account_password, PASSWORD_DEFAULT);
                // echo $password_hash;    
                $dbm->create_account($create_account_user_name, $password_hash);
                // echo $return;
                $new_user_id = $dbm->get_user_id($create_account_user_name);
                // echo 'user id = ' . $new_user_id;
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['user_name'] = $dbm->get_user_name($new_user_id);
                // echo $_SESSION['user_id'];
                // echo $_SESSION['user_name'];
                // header('location:account_top.php?user_id=' . $new_user_id);
                header('Location:account_top.php');
            } else {
                // echo '<script type="text/javascript">alert("なぜだー");</script>';
                echo '<script type="text/javascript">alert("パスワードは8文字以上の半角英数字にしてください");</script>';

            }
        }
    } else {
        echo '<script type="text/javascript">alert("ユーザー名は最大20文字で半角英数字または_のみ使用できます");</script>';
    }
}

// ログイン
if (isset($_POST['login'])) {
    $login_user_name = $_POST['login_user_name'];
    // echo "<br><br><br>";
    // echo $login_user_name;
    if (preg_match('/\A\w{0,20}\z/', $login_user_name)) {
        $user_id = $dbm->get_user_id($login_user_name);
        // アカウントが存在しない場合
        if (!isset($user_id)) {
            // echo '<script type="text/javascript">alert("アカウントが存在しません");</script>';
            echo '<script type="text/javascript">alert("入力されたユーザー名もしくはパスワードに間違いがあります");</script>';
        } else {
            // echo "<br><br><br>";
            // echo 'user id = ' . $user_id;
            // echo 'アカウントが存在します。パスワードをチェックします';
            $login_password = $_POST['login_password'];
            // echo $login_password;
            if (preg_match('/\A[a-zA-Z0-9]{8,200}\z/', $login_password)) {
                $user_password = $dbm->get_password($user_id);
                // echo '<br>';
                // echo $user_password;
                if(password_verify($login_password, $user_password)) {
                    // echo '正しい';
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $dbm->get_user_name($user_id);
                    echo $_SESSION['user_id'];
                    echo $_SESSION['user_name'];
                    header('Location:account_top.php');    
                } else {
                    // echo '<script type="text/javascript">alert("パスワードが違います");</script>';
                    echo '<script type="text/javascript">alert("入力されたユーザー名もしくはパスワードに間違いがあります");</script>';
                }
            } else {
                // echo '<script type="text/javascript">alert("パスワードは8文字以上の半角英数字です");</script>';
                echo '<script type="text/javascript">alert("入力されたユーザー名もしくはパスワードに間違いがあります");</script>';
            }
        }
    } else {
        // echo '<script type="text/javascript">alert("ユーザー名は最大20文字の半角英数字または_の組み合わせです");</script>';
        echo '<script type="text/javascript">alert("入力されたユーザー名もしくはパスワードに間違いがあります");</script>';
    }
}

// アカウント管理から遷移した場合
if (isset($_POST['change_type'])) {
    // echo "<br><br><br>";
    // print_r($_POST['change_type']);
    echo '<script type=text/javascript>alert("アカウントの変更を保存しました");</script>';
}

if (isset($_POST['account_delete'])) {
    echo '<script type=text/javascript>alert("アカウントを削除しました");</script>';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1221px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1220px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:430px)" href="css/style_mobile.css">
    <link rel="icon" type="image/png" sizes="32x32" href="image/walking_bird.png">
    <title>歩こう広島</title>  
</head>
<body class="background_img">
    <header id="header" class="flex">
        <a href="index.php">
            <img class="header_bird" src="image/walking_bird.png" alt="歩く鳥">
            <img class="header_logo" src="image/header_logo.png" alt="ヘッダーロゴ">
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
    <main class="main_position main_height flex justify_content_center">
        <div id="top_image">
            <div id="main_image" class="position_relative">
                <img id="river_sparkle" class="position_absolute" src="image/river_sparkle_and_season_flower.png" alt="川のきらめきと四季の花">
                <img id="main_logo" src="image/main_logo.png" alt="メインロゴ">
            </div>
            <div id="navi_menu" class="display_none">
                <ul>
                    <li><a id="main_login" href="#login">ログイン</a></li>
                    <li><a id="main_create_account" href="#create_account">アカウント作成</a></li>
                    <li><a id="main_guest" href="account_top.php">ゲスト</a></li>
                </ul>
            </div>
            <div id="login" class="display_none">
                <form action="index.php" method="post">
                    <div>
                        <label for="login_user_id">ユーザー名</label>
                        <input type="text" name="login_user_name" id="login_user_name" title="20文字以内の半角英数字または_" required>
                    </div>
                    <div>
                        <label for="login_password">パスワード</label>
                        <input type="password" name="login_password" id="login_password" title="8文字以上の半角英数字" required>
                    </div>
                    <div class="flex justify_content_center">
                        <button id="login_button" type="submit" name="login">ログイン</button>
                    </div>
                </form>
            </div>
            <div id="create_account" class="display_none">
                <form action="index.php" method="post">
                    <div>
                        <label for="create_account_user_id">ユーザー名</label>
                        <input type="text" name="create_account_user_name" id="create_account_user_name" title="20文字以内の半角英数字または_" required>
                    </div>
                    <div>
                        <label for="create_account_password">パスワード</label>
                        <input type="password" name="create_account_password" id="create_account_password" title="8文字以上の半角英数字" required>
                    </div>
                    <div class="flex justify_content_center">
                        <button id="create_account_button" type="submit" name="create_account">アカウント作成</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="js/script.js"></script>

</body>
</html>