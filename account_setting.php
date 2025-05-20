<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';
session_start();
if(isset($_SESSION['user_id']) == false) {
    echo '<script type="text/javascript">
    alert("ログインしてください");
    window.location.href="index.php";
    </script>';
} else {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
}

// 検索条件を削除する
if (!strpos($url, "/article_management.php") AND !strpos($url, "/article.php") AND !strpos($url, "/edit.php")) {
}

// 保存ボタンを押したときの処理
if (isset($_POST['save_account_edit'])) {
    if (isset($_POST['change_type'])) {
        $change_type = $_POST['change_type'];
        // echo '<br><br><br>';
        // print_r($change_type);

        // ユーザー名の変更がある場合
        if ($change_type[0]) {
            $new_user_name = $_POST['new_user_name'];
            echo "<br><br><br><br>";
            echo $new_user_name;
            if ($new_user_name == $user_name) {
                echo '<script type="text/javascript">alert("新しいユーザー名を入力してください");</script>';
            }
            else if (preg_match('/^\w{0,20}$/', $new_user_name)) {
                $confirm_user_id = $dbm->get_user_id($new_user_name);
                // echo "<br><br><br>";
                // echo $confirm_user_id;
                // アカウントがすでに存在している場合
                if ($confirm_user_id) {
                    echo '<script type="text/javascript">alert("すでに存在しているアカウントです");</script>';
                } else {
                    // echo "<br><br><br>";
                    // echo 'user id = ' . $user_id;
                    // echo '<script type="text/javascript">alert("ユーザー名を変更します");</script>';
                    $confirm_password = $_POST['current_password'];
                    // echo $confirm_password;
                    if (preg_match('/\A[a-zA-Z0-9]{8,200}\z/', $confirm_password)) {
                        $user_password = $dbm->get_password($user_id);
                        // echo '<br>';
                        // echo $user_password;
                        if(password_verify($confirm_password, $user_password)) {
                            // echo '正しい';
                            $dbm->update_user_name($user_id, $new_user_name);
                            session_destroy();
                            header('Location:index.php', true, 307);    
                        } else {
                            echo '<script type="text/javascript">alert("パスワードが違います");</script>';
                        }
                    } else {
                        echo '<script type="text/javascript">alert("パスワードが違います");</script>';
                        // echo "<br><br><br><br>";
                        // echo $_POST['current_password'];
                    }
                }
            } else {
                echo '<script type="text/javascript">alert("ユーザー名は最大20文字で半角英数字または_のみ使用できます");</script>';
            }        
        }
        // パスワードの変更がある場合
        // echo $_POST['change_type'][1];
        if ($change_type[1]) {
            $confirm_password = $_POST['current_password'];
            // echo $confirm_password;
            if (preg_match('/\A[a-zA-Z0-9]{8,200}\z/', $confirm_password)) {
                $user_password = $dbm->get_password($user_id);
                // echo '<br>';
                // echo $user_password;
                if(password_verify($confirm_password, $user_password)) {
                    // echo '正しい';
                    $new_password = $_POST['new_password'];
                    // echo '<br><br><br>';
                    // echo $new_password;
                    if (preg_match('/\A[a-zA-Z0-9]{8,200}\z/', $new_password)) {
                        $user_password = $dbm->get_password($user_id);
                        // echo '<br>';
                        // echo $user_password;
                        if(password_verify($new_password, $user_password)) {
                            echo '<script type="text/javascript">alert("新しいパスワードには現在のパスワードは使えません");</script>';
                        } else {
                            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                            $return = $dbm->update_password($user_id, $new_password_hash);
                            echo $return;
                            session_destroy();
                            header('Location:index.php', true, 307);    
                        }
                    } else {
                        echo '<script type="text/javascript">alert("新しいパスワードは8文字以上で半角英数字のみ使用できます");</script>';
                    }        
                } else {
                    echo '<script type="text/javascript">alert("パスワードが違います");</script>';
                }
            } else {
                echo '<script type="text/javascript">alert("パスワードが違います");</script>';
                // echo "<br><br><br><br>";
                // echo $_POST['current_password'];
            }
        }
    }
}

// 削除ボタンを押したときの処理
if (isset($_POST['account_delete'])) {
    // echo "<br><br><br><br>";
    // echo "削除します";
    $return1 = $dbm->delete_account($user_id);
    // echo $return1;
    $return2 = $dbm->delete_all_article($user_id);
    // echo $return2;
    $return3 = $dbm->delete_all_image($user_id);
    // echo $return3;
    session_destroy();
    header('Location:index.php', true, 307);
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1101px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1100px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:480px)" href="css/style_mobile.css">
    <script src="js/script.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="image/walking_bird.png">
    <title>歩こう広島｜アカウント管理</title>
</head>
<body class="background_color">
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
    <main id="account_setting">
        <h2>アカウント管理</h2>
        <div class="account_setting_box flex align_items_center">
            <div class="account_setting_container_left margin_0">
                <img class="river_sparcle_blue" src="image/river_sparkle_blue.png" alt="川のきらめき青">
                <form id="account_setting_form" method="POST" action="account_setting.php">
                    <p id="account_edit">アカウント編集</p>
                    <ul>
                        <li class="flex align_items_center">
                            <p class="width_220px">変更</p>
                            <div class="width_220px flex space_between">
                                <div class="flex margin_0">
                                    <input type="checkbox" id="user_name_change" name="change_type[0]" value="1">
                                    <label for="user_name_change">ユーザー名</label>
                                </div>
                                <div class="flex margin_0">
                                    <input type="checkbox" id="password_change" name="change_type[1]" value="1">
                                    <label for="password_change">パスワード</label>
                                </div>
                            </div>
                        </li>
                        <li class="flex">
                            <label for="current_user_id" class="width_220px">現在のユーザー名</label>
                            <input class="account_input width_220px text_align_center" type="text" name="current_user_id" value=" <?php if (isset($user_name)) {echo $user_name;}?>" readonly disabled>
                        </li>
                        <li class="flex">
                            <label for="new_user_id" class="width_220px">新しいユーザー名</label>
                            <input class="account_input width_220px text_align_center" type="text" name="new_user_name" disabled>
                        </li>
                        <li class="flex">
                            <label for="current_password" class="width_220px">現在のパスワード</label>
                            <input class="account_input width_220px text_align_center" type="password" name="current_password" disabled>
                        </li>
                        <li class="flex">
                            <label for="new_password" class="width_220px">新しいパスワード</label>
                            <input class="account_input width_220px text_align_center" type="password" name="new_password" disabled>
                        </li>
                        <li class="flex">
                            <label for="confirm_password" class="width_220px">新しいパスワード（確認用）</label>
                            <input class="account_input width_220px text_align_center" type="password" name="confirm_password" disabled>
                        </li>
                    </ul>
                    <div class="flex justify_content_center">
                        <button id="save_setting_button" type="submit" name="save_account_edit" onclick="return CheckAccountChange()">変更を保存</button>
                    </div>
                </form>
            </div>
            <div class="account_setting_container_right margin_0">
                <div id="account_delete_container" class="flex align_items_center">
                    <img class="river_sparcle_blue_mini" src="image/river_sparkle_blue.png" alt="川のきらめき青">
                    <form id="account_delete_form" method="POST" action="account_setting.php">
                        <p id="account_delete">アカウント削除</p>
                        <button id="account_delete_button" type="submit" name="account_delete" onclick="return CheckAccountDelete()">アカウント削除</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        let userNameChange = document.getElementById("user_name_change");
        let passwordChange = document.getElementById("password_change");
        // console.log(userNameChange);
        // console.log(passwordChange);
        let accountInput = document.getElementsByClassName('account_input');
        // console.log(accountInput);

        // ユーザー名変更のチェックを変えた場合
        userNameChange.addEventListener('click', (e) => {
            if (e.target.checked) {
                // console.log("チェックされました");
                // 2番目と3番目のテキストボックスの入力を有効にする
                for (let i = 0; i < 2; i++) {
                    accountInput[i + 1].disabled = false;
                }
            } else {
                // console.log("チェックが外れました");
                if (passwordChange.checked) {
                    // console.log("パスワード変更のチェックが入っています");
                    // 2番目のテキストボックスの入力を無効にする
                    accountInput[1].disabled = true;
                } else {
                    // console.log("パスワード変更のチェックは入っていません");
                    // 2番目と3番目のテキストボックスの入力を無効にする
                    for (let i = 0; i < 2; i++) {
                    accountInput[i + 1].disabled = true;
                    }
                }
            }
        });

        // パスワード変更のチェックを変えた場合
        passwordChange.addEventListener('click', (e) => {
            if (e.target.checked) {
                // console.log("チェックされました");
                // 3番目～5番目のテキストボックスの入力を有効にする
                for (let i = 0; i < 3; i++) {
                    accountInput[i + 2].disabled = false;
                }
            } else {
                // console.log("チェックが外れました");
                if (userNameChange.checked) {
                    // console.log("ユーザー名変更のチェックが入っています");
                    // 4番目と5番目のテキストボックスの入力を無効にする
                    for (let i = 0; i < 2; i++) {
                    accountInput[i + 3].disabled = true;
                    }
                } else {
                    // console.log("ユーザー名変更のチェックは入っていません");
                    // 3番目～5番目のテキストボックスの入力を無効にする
                    for (let i = 0; i < 3; i++) {
                    accountInput[i + 2].disabled = true;
                    }
                }
            }
        });

        // 保存ボタンを押したときの処理
        function CheckAccountChange() {
            if (!userNameChange.checked && !passwordChange.checked) {
                alert("変更する項目を選択してください");
                return false;
            } else {
                let notEmpty = true;
                for (let i = 0; i < accountInput.length; i++) {
                    if (accountInput[i].disabled == false) {
                        let notDisabled = accountInput[i];
                        // console.log(notDisabled);
                        if (notDisabled.value == "") {
                            notEmpty = false;
                        }
                    }
                }
                // console.log(notEmpty);
                if (!notEmpty) {
                    alert("空欄の箇所があります");
                    return false;
                } else {
                    if (userNameChange.checked && passwordChange.checked) {
                        if (confirm("ユーザー名とパスワードを変更します")) {
                            return true;
                        } else {
                            return false;
                        }
                    } else if (userNameChange.checked) {
                        if (confirm("ユーザー名を変更します")) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        if (accountInput[2].value == accountInput[3].value) {
                            alert("新しいパスワードには現在のパスワードは使用できません");
                            return false;
                        } else if (accountInput[3].value !== accountInput[4].value) {
                            alert("新しいパスワードと確認用パスワードが一致しません");
                            return false;
                        } else if (confirm("パスワードを変更します")) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
        }

        function CheckAccountDelete() {
            if(confirm("アカウントと作成した全ての記事を削除してログアウトします")) {
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>
</html>