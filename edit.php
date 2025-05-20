<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';
session_start();

$article_id = $_GET['id'];

if (isset($_SESSION['user_id']) == false) {
    echo '<script type="text/javascript">
    alert("ログインしてください");
    window.location.href="index.php";
    </script>';
} else {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    if (!isset($_POST["upload"])) {
        $return = $dbm->confirm_user_id($user_id, $article_id, $dbm);
        if (!$return) {
            echo '<script type="text/javascript">
            alert("ログインしてください");
            window.location.href="index.php";
            </script>';
        }    
    }
}

// アップロード画面から遷移した場合
if (isset($_POST["upload"])) {
    // 記事を登録する
    $dbm->save_new_article($article_id, $user_id);
    $max_image_id = $dbm->get_max_image_id();
    // echo($max_image_id);
    // phpinfo();

    // 画像を登録する
    if ($_FILES["image_1"]["error"] == 0) {
        // echo "image_1はあります";
        // echo "<br><br><br><br>";
        // echo $_FILES["image_1"]['tmp_name'];
        $display_order = $_POST["display_order_1"];
        // echo $display_order;
        $image_id_1 = $max_image_id + $display_order;
        // echo $image_id_1;
        $new_image_url = 'upload/' . $image_id_1 . '.jpg';
        // echo $new_image_url;
        move_uploaded_file($_FILES['image_1']['tmp_name'], $new_image_url);
        $dbm->save_new_image($article_id, $user_id, $display_order, $new_image_url);
    } else {
        // echo "image_1はありません";
    }
    if ($_FILES["image_2"]["error"] == 0) {
        // echo "image_2はあります";
        $display_order = $_POST["display_order_2"];
        // echo $display_order;
        $image_id_2 = $max_image_id + $display_order;
        // echo $image_id_2;
        $new_image_url = 'upload/' . $image_id_2 . '.jpg';
        // echo $new_image_url;
        move_uploaded_file($_FILES['image_2']['tmp_name'], $new_image_url);
        $dbm->save_new_image($article_id, $user_id, $display_order, $new_image_url);
    } else {
        // echo "image_2はありません";
    }
    if ($_FILES["image_3"]["error"] == 0) {
        // echo "image_3はあります";
        $display_order = $_POST["display_order_3"];
        // echo $display_order;
        $image_id_3 = $max_image_id + $display_order;
        // echo $image_id_3;
        $new_image_url = 'upload/' . $image_id_3 . '.jpg';
        // echo $new_image_url;
        move_uploaded_file($_FILES['image_3']['tmp_name'], $new_image_url);
        $dbm->save_new_image($article_id, $user_id, $display_order, $new_image_url);
    } else {
        // echo "image_3はありません";
    }
}

$article_image = $dbm->get_article_image($article_id);
// echo "<br><br><br>";
// print_r($article_image);

// 保存ボタンを押したときの処理
if (isset($_POST['save'])) {
    // 記事の変更を登録する
    $dbm->save_article_edit($article_id, $user_id);
    // $return = $dbm->save_article_edit($article_id, $user_id);
    // echo "<br><br><br>";
    // echo $user_id;
    // echo $return;
    // $max_image_id = $dbm->get_max_image_id();

    // 画像の変更を保存する
    if (isset($_POST['display_order_1'])) {
        $display_order_1 = $_POST['display_order_1'];
    }
    // 画像1がセットされている場合
    $image_id_1 = $article_image[0]['image_id'];
    if ($image_id_1) {
        // echo "<br><br><br><br>";
        // echo "image_id_1はあります";
        // print_r($_FILES['image_1']);
        $image_1_display_order = $article_image[0]['display_order'];

        // 画像1の表示順を取得
        if (isset($_POST["display_order_1"])) {
            $display_order_1 = $_POST["display_order_1"];
        }

        // 削除のチェックが入っていたら画像を削除する
        if (isset($_POST['delete_image_1'])) {
            $return = $dbm->delete_image($image_id_1, $article_id, $user_id);
            // echo "<br><br><br><br>";
            echo $return;
            // echo "画像1を削除します";
        } else {
            // echo "画像1は削除しません";

            // 画像のアップロードがある場合
            // phpinfo();
            if ($_FILES['image_1']['error'] == 0) {
                // echo "画像を変更します";
                $new_image_url = 'upload/' . $image_id_1 . '.jpg';
                // echo $new_image_url;
                move_uploaded_file($_FILES['image_1']['tmp_name'], $new_image_url);
                $dbm->update_image($image_id_1, $display_order_1, $new_image_url, $user_id, $article_id);
            // 表示順の変更がある場合
            } else if (isset($_POST['display_order_1']) AND $_POST['display_order_1'] !== $image_1_display_order) {
                // echo "表示順を変更します";
                $dbm->change_display_order($image_id_1, $display_order_1, $user_id, $article_id);
            } else {
                // echo "変更はありません";
            }
        }
    // 画像1がセットされていない場合
    } else {
        // echo "image_id_1はありません";

        // echo "<br><br><br>";
        // print_r($_FILES['image_1']);
        // 画像のアップロードがある場合
        if ($_FILES['image_1']['error'] == 0) {
            // echo "新しい画像を登録します";
            $max_image_id = $dbm->get_max_image_id();
            $image_id_1 = $max_image_id + 1;
            $new_image_url = 'upload/' . $image_id_1 . '.jpg';
            // echo $new_image_url;
            $display_order_1 = $_POST['display_order_1'];
            // echo $display_order_1;
            move_uploaded_file($_FILES['image_1']['tmp_name'], $new_image_url);
            $dbm->save_new_image($article_id, $user_id,  $display_order_1, $new_image_url);
        } else {
            // echo "アップロードはありません";
        }
    }

    // 画像2がセットされている場合
    $image_id_2 = $article_image[1]['image_id'];
    if ($image_id_2) {
        // echo "<br><br><br>";
        // echo "image_id_2はあります";
        $image_2_display_order = $article_image[1]['display_order'];

        // 画像2の表示順を取得
        if (isset($_POST["display_order_2"])) {
            $display_order_2 = $_POST["display_order_2"];
        }

        // 削除のチェックが入っていたら画像を削除する
        if (isset($_POST['delete_image_2'])) {
            $dbm->delete_image($image_id_2, $article_id, $user_id);
            // echo "画像2を削除します";
        } else {
            // echo "画像2は削除しません";

            // 画像のアップロードがある場合
            if ($_FILES['image_2']['error'] == 0) {
                // echo "画像を変更します";
                $new_image_url = 'upload/' . $image_id_2 . '.jpg';
                // echo $new_image_url;
                move_uploaded_file($_FILES['image_2']['tmp_name'], $new_image_url);
                $dbm->update_image($image_id_2, $display_order_2, $new_image_url, $user_id, $article_id);
            // 表示順の変更がある場合
            } else if ($_POST['display_order_2'] !== $image_2_display_order) {
                // echo "表示順を変更します";
                $dbm->change_display_order($image_id_2, $display_order_2, $user_id, $article_id);
            } else {
                // echo "変更はありません";
            }
        }
    // 画像2がセットされていない場合
    } else {
        // echo "image_id_2はありません";

        // 画像のアップロードがある場合
        if ($_FILES['image_2']['error'] == 0) {
            // echo "新しい画像を登録します";
            $max_image_id = $dbm->get_max_image_id();
            $image_id_2 = $max_image_id + 1;
            $new_image_url = 'upload/' . $image_id_2 . '.jpg';
            // echo $new_image_url;
            $display_order_2 = $_POST['display_order_2'];
            // echo $display_order_1;
            move_uploaded_file($_FILES['image_2']['tmp_name'], $new_image_url);
            $dbm->save_new_image($article_id, $user_id, $display_order_2, $new_image_url);
        } else {
            // echo "アップロードはありません";
        }
    }

    // 画像3がセットされている場合
    $image_id_3 = $article_image[2]['image_id'];
    if ($image_id_3) {
        // echo "<br><br><br>";
        // echo "image_id_3はあります";
        $image_3_display_order = $article_image[2]['display_order'];

        // 画像3の表示順を取得
        if (isset($_POST["display_order_3"])) {
            $display_order_3 = $_POST["display_order_3"];
        }

        // 削除のチェックが入っていたら画像を削除する
        if (isset($_POST['delete_image_3'])) {
            $dbm->delete_image($image_id_3, $article_id, $user_id);
            // echo "画像3を削除します";
        } else {
            // echo "画像3は削除しません";

            // 画像のアップロードがある場合
            if ($_FILES['image_3']['error'] == 0) {
                // echo "画像を変更します";
                $new_image_url = 'upload/' . $image_id_3 . '.jpg';
                // echo $new_image_url;
                move_uploaded_file($_FILES['image_3']['tmp_name'], $new_image_url);
                $dbm->update_image($image_id_3, $display_order_3, $new_image_url, $user_id, $article_id);
            // 表示順の変更がある場合
            } else if ($_POST['display_order_3'] !== $image_3_display_order) {
                // echo $_POST["display_order_3"];
                // echo $image_3_display_order;
                // echo "表示順を変更します";
                $dbm->change_display_order($image_id_3, $display_order_3, $user_id, $article_id);
            } else {
                // echo "変更はありません";
            }
        }
    // 画像3がセットされていない場合
    } else {
        // echo "image_id_3はありません";
        // print_r($_FILES['image_3']);
        // 画像のアップロードがある場合
        if ($_FILES['image_3']['error'] == 0) {
            // echo "新しい画像を登録します";
            $max_image_id = $dbm->get_max_image_id();
            $image_id_3 = $max_image_id + 1;
            $new_image_url = 'upload/' . $image_id_3 . '.jpg';
            // echo $new_image_url;
            $display_order_3 = $_POST['display_order_3'];
            // echo $display_order_1;
            move_uploaded_file($_FILES['image_3']['tmp_name'], $new_image_url);
            $dbm->save_new_image($article_id, $user_id, $display_order_3, $new_image_url);
        } else {
            // echo "アップロードはありません";
        }
    }
    // jpeg以外も作る！！
    echo '<script type="text/javascript">alert("変更を保存しました");</script>';
}

$article_image = $dbm->get_article_image($article_id);
// echo "<br><br><br>";
// print_r($article_image);

// 画像の表示順を取得して配列に代入
for ($i = 0; $i < count($article_image); $i++) {
$display_order_array[] = $article_image[$i]['display_order'];
}
// print_r($display_order_array);
$json_display_order_array = json_encode($display_order_array);


$article_info = $dbm->get_article_info($article_id);
// echo "<br>";
// print_r($article_info);

// $user_name = $article_info['user_name'];
$category_id = $article_info['category_id'];
// echo $category_id;
$category_length = $dbm->get_category_length();
// echo "<br>";
// echo $category_length;
// $category_name = $article_info['category_name'];
$season_id = $article_info['season_id'];
// echo $season_id;
$season_name = $article_info['season_name'];
$season_length = $dbm->get_season_length();
// $season_name = $article_info['season_name'];
$title = $article_info['title'];
$text = $article_info['text'];
$point_x = $article_info['point_x'];
// $point_x = $json_encode($point_x);
$point_y = $article_info['point_y'];
// $point_y = $json_encode($point_y);
$registration_date = $article_info['registration_date'];
// var_dump($registration_date);
$date = new DateTime($registration_date);

// phpinfo();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1101px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1100px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:600px)" href="css/style_mobile.css">
    <link rel="icon" type="image/png" sizes="32x32" href="image/walking_bird.png">
    <title>歩こう広島｜記事編集</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="js/script.js"></script>
</head>
<body class="background_color">
    <header class="flex">
        <a href="account_top.php">
            <img class="header_bird" src="image/walking_bird.png" alt="歩く鳥">
            <img class="header_logo" src="image/header_logo.png" alt="ロゴ">
        </a>
        <input type="radio" id="header_menu_guide" class="display_none">
        <label for="header_menu_guide">menu</label>
        <div id="header_menu">
            <ul class="flex wrap">
                <li><a href="bulletin_board.php">掲示板</a></li>
                <li><a href="information_map.php">情報マップ</a></li>
                <li><a href="article_management.php">記事管理</a></li>
                <li><a href="account_setting.php">アカウント管理</a></li>
                <li><a id="logout" href="index.php" onclick="return CheckLogout()">ログアウト</a></li>
            </ul>
        </div>
    </header>
    <main class="edit">
        <h2>記事編集</h2>
        <div class="form_container flex">
            <form id="edit_form" action="edit.php?id=<?php echo $article_id; ?>" method="POST" onsubmit="return CheckSave()" enctype="multipart/form-data">
            <?php show_edit($date, $title, $text, $point_x, $point_y, $article_image);?>
            </form>
            <div id="upload_map_container" class="flex align_items_start">
                <div id="map4"></div>
            </div>  
        </div>
    </main>

    <script>
        let category_id = <?php echo $category_id; ?>;
        let category_length = <?php echo $category_length; ?>;
        let radio_category_array = document.getElementsByName('category');
        // console.log(radio_category_array);
        // console.log(category_id);
        for (let i = 0; i < category_length; i++) {
            if (i + 1 == category_id) {
                radio_category_array[i].checked = true;
                // console.log(i);
            }
        }
        let season_id = <?php echo $season_id; ?>;
        let season_length = <?php echo $season_length; ?>;
        let radio_season_array = document.getElementsByName('season');
        // console.log(radio_season_array);
        // console.log(season_id);
        for (let i = 0; i < season_length; i++) {
            if (i + 1 == season_id) {
                radio_season_array[i].checked = true;
                // console.log(i);
            }
        }

        let displayImage = document.querySelectorAll('form img');
        // console.log(displayImage);
        let uploadImage = document.querySelectorAll('input[type="file"]');
        // console.log(uploadImage);
        let displayOrder = document.getElementsByTagName('select');
        // console.log(displayOrder);
        let deleteImage = document.getElementsByClassName("delete_image");
        // console.log(deleteImage);

        // 画像がセットされていない場合は表示順をdisableに
        let defaultImage_src = [];
        for (let i = 0; i < displayImage.length; i++) {
            defaultImage_src[i] = displayImage[i].src;
            if (displayImage[i].alt == "NO IMAGE") {
                // console.log("画像はありません");
                displayOrder[i].disabled = true;
                deleteImage[i].disabled = true;
            } else {
                // console.log("画像はあります");
            }
        }
        // console.log(defaultImage_src);

        // 画像がセットされている場合は表示順を表示
        let imageLength = 0;
        let jsonDisplayOrderArray = <?php echo $json_display_order_array; ?>;
        // console.log(jsonDisplayOrderArray);
        let setDisplayOrderArray = jsonDisplayOrderArray.filter(Boolean);
        // console.log(setDisplayOrderArray);
        for (let i = 0; i < setDisplayOrderArray.length; i++) {
            for (let j = 0; j < setDisplayOrderArray.length; j++) {
                let newOption = document.createElement('option');
                newOption.innerHTML = i + 1;
                displayOrder[j].appendChild(newOption);
            }
            displayOrder[i].value = i + 1;
        }

    // タイトルの文字数制限とカウント

    let title = document.getElementById('title');
    let charCounterTitle = document.getElementsByTagName('span')[0];
    // console.log(charCounterTitle);
    let maxLengthTitle = 30;

    let defaultTitleValue = title.value;
    let countTitle = 0;
    for (let i = 0; i < defaultTitleValue.length; i++) {
        // console.log(i);
        let char = defaultTitleValue.charCodeAt(i);
        // 記号数字など
        if ((char >= 0x00 && char < 0x81)

            // 私用領域
            || (char === 0xf8f0)

            // 0xff61 は「半角の ｡」
            // 0xffa0 は「半角の ﾟ」
            || (char >= 0xff61 && char < 0xffa0)

            // 私用領域
            || (char >= 0xf8f1 && char < 0xf8f4)) {

            countTitle += 1; // 半角文字
        } else {
            countTitle += 2; // 全角文字
        }
    }
    charCounterTitle.textContent = countTitle;

    // タイトルの文字数制限

    let isComposingTitle = false; //IME変換中をトラッキング
    title.addEventListener("compositionstart", () => {
        isComposingTitle = true;
    });
    title.addEventListener("compositionend", (e) => {
        isComposingTitle = false;
        handleTitleInput(e);
    });
    title.addEventListener("input", (e) => {
        handleTitleInput(e);
    });

    // タイトル入力イベント関数
    const handleTitleInput = (e) => {
        if (isComposingTitle) return; // IME入力中は処理をスキップ

        const targetTitle = e.target; // インプット要素を取得
        const targetTitleValue = targetTitle.value;
        let titleLength = titleCount(targetTitleValue);
        if (titleLength > maxLengthTitle) {
            targetTitle.value = truncateTitleToMaxLength(targetTitle, maxLengthTitle);
            titleLength = titleCount(targetTitle.value);
        }
    };

    // タイトルを最大行数以内に制限する関数
    const truncateTitleToMaxLength = (title, maxLengthTitle) => {
    const originalValue = title.value;
    while (titleCount(title.value) > maxLengthTitle) {
        title.value = title.value.slice(0, -1); // 末尾を削除
    }
    return titleCount(title.value) > 0 ? title.value : originalValue;
    };

    // タイトルのカウント
    function titleCount(titleValue) {
        let countTitle = 0;
        for (let i = 0; i < titleValue.length; i++) {
            // console.log(i);
            let char = titleValue.charCodeAt(i);
            // 記号数字など
            if ((char >= 0x00 && char < 0x81)

                // 私用領域
                || (char === 0xf8f0)

                // 0xff61 は「半角の ｡」
                // 0xffa0 は「半角の ﾟ」
                || (char >= 0xff61 && char < 0xffa0)

                // 私用領域
                || (char >= 0xf8f1 && char < 0xf8f4)) {

                countTitle += 1; // 半角文字
            } else {
                countTitle += 2; // 全角文字
            }
        }
        charCounterTitle.textContent = countTitle;
        return countTitle;
    }

    // 本文の行数制限・文字数制限とカウント

    let text = document.getElementById('text');
    let charCounterText = document.getElementsByTagName('span')[1];
    let maxLengthText = 960;
    let maxLineText = 12;
    // console.log(text);
    // console.log(charCounterText);
    // console.log(maxLength);
    
    let defaultTextValue = text.value;
    let countText = 0;
    for (let i = 0; i < defaultTextValue.length; i++) {
        let char = defaultTextValue.charCodeAt(i);
        // 記号数字など
        if ((char >= 0x00 && char < 0x81)

            // 私用領域
            || (char === 0xf8f0)

            // 0xff61 は「半角の ｡」
            // 0xffa0 は「半角の ﾟ」
            || (char >= 0xff61 && char < 0xffa0)

            // 私用領域
            || (char >= 0xf8f1 && char < 0xf8f4)) {

            countText += 1; // 半角文字
        } else {
            countText += 2; // 全角文字
        }
    }
    charCounterText.textContent = countText;

    // pc、tabletの場合
    if (window.matchMedia("(min-width: 601px)").matches) {
        text.rows = 12;
        text.cols = 80;

        // 本文の行数制限
        let isComposingText = false; //IME変換中をトラッキング
        text.addEventListener("compositionstart", () => {
            isComposingText = true;
        });
        text.addEventListener("compositionend", (e) => {
            isComposingText = false;
            handleTextInput(e);
        });
        text.addEventListener("input", (e) => {
            handleTextInput(e);
        });

        // 本文入力イベント関数
        const handleTextInput = (e) => {
            if (isComposingText) return; // IME入力中は処理をスキップ

            const targetText = e.target; // テキストエリアの要素を取得
            const {scrollHeight, clientHeight} = targetText;
            if (scrollHeight > clientHeight) {
                targetText.value = truncateTextToMaxLines(targetText, clientHeight);
            }
            textCount();
        };

        // 本文を最大行数以内に制限する関数
        const truncateTextToMaxLines = (text, clientHeight) => {
        const originalValue = text.value;
        while (text.scrollHeight > clientHeight) {
            text.value = text.value.slice(0, -1); // 末尾を削除
        }
        return text.value.length > 0 ? text.value : originalValue;
        };
    }

    // スマートフォンの場合
    if (window.matchMedia("(max-width: 601px)").matches) {
        text.style.width = "100%";
        text.rows = 12;
        let isComposingText = false; //IME変換中をトラッキング
        text.addEventListener("compositionstart", () => {
            isComposingText = true;
        });
        text.addEventListener("compositionend", (e) => {
            isComposingText = false;
            handleTextInput(e);
        });
        text.addEventListener("input", (e) => {
            handleTextInput(e);
        });

        // 本文入力イベント関数
        const handleTextInput = (e) => {
            if (isComposingText) return; // IME入力中は処理をスキップ

            const targetText = e.target; // テキストエリアの要素を取得
            const {scrollHeight, clientHeight} = targetText;
            // if (scrollHeight > clientHeight) {
            //     targetText.value = truncateTextToMaxLines(targetText, clientHeight);
            // }
            textCount();
        };
    }

    // 本文のカウント
    function textCount() {
        let textValue = text.value;
        let countText = 0;
        for (let i = 0; i < textValue.length; i++) {
            let char = textValue.charCodeAt(i);
            // console.log(char);

            // 記号数字など
            if ((char >= 0x00 && char < 0x81)

                // 私用領域
                || (char === 0xf8f0)

                // 0xff61 は「半角の ｡」
                // 0xffa0 は「半角の ﾟ」
                || (char >= 0xff61 && char < 0xffa0)

                // 私用領域
                || (char >= 0xf8f1 && char < 0xf8f4)) {

                countText += 1; // 半角文字
            } else {
                countText += 2; // 全角文字
            }
        }
        charCounterText.textContent = countText;
    }

    // 写真をアップロードした場合
    for (let i = 0; i < uploadImage.length; i++) {
        uploadImage[i].addEventListener('change', (e) => {
            // プレビュー表示する
            let file = e.target.files[0];
            if(file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    displayImage[i].src = e.target.result;
                };
                reader.readAsDataURL(file);

                displayOrder[i].disabled = false;

                // セットされていない領域に画像をアップロードした場合
                if (displayImage[i].alt == "NO IMAGE" || displayImage[i].classList.contains("opacity") == true) {
                    // console.log("初めてのアップロード");
                    for (let j = 0; j < displayOrder.length; j++) {
                        // セットされた以外の領域の表示順の選択肢を増やす
                        if (j !== i) {
                            if (displayOrder[j].value !== "") {
                                let newOption = document.createElement('option');
                                let option = displayOrder[j].getElementsByTagName('option');
                                if (!option) {
                                    newOption.innerHTML = 1;
                                } else {
                                    newOption.innerHTML = option.length + 1;
                                }
                                displayOrder[j].appendChild(newOption);
                            }
                        } else {
                            // セットされた領域に表示順を追加
                            // 表示順を消す
                            let option = displayOrder[i].getElementsByTagName('option');
                            // console.log(option);
                            // console.log("長さ" + option.length);
                            let optionLength = option.length;
                            for (let k = 0; k < optionLength; k++) {
                                // console.log(k);
                                // console.log("要素の中身" + option[k]);
                                option[0].remove();
                            }
                            // 表示順を追加
                            let displayOrderValue = 0;
                            // console.log(displayOrder.length);
                            for (let k = 0; k < displayOrder.length; k++) {
                                if (displayOrder[k].disabled == false) {
                                    // console.log(k);
                                    displayOrderValue++;
                                        let newOption = document.createElement('option');
                                    let option = displayOrder[i].getElementsByTagName('option');
                                    newOption.innerHTML = displayOrderValue;
                                    displayOrder[i].appendChild(newOption);
                                    displayOrder[i].value = displayOrderValue;
                                }
                            }
                        }
                        displayImage[i].classList.remove("opacity");
                        deleteImage[i].checked = false;
                    }
                    // console.log(deleteImage);
                    // console.log(deleteImage[i]);
                    deleteImage[i].disabled = false;
                } else {
                    // console.log("画像を変更しました");
                }
            // ファイルの選択をキャンセルした場合
            } else {
                // console.log("ファイルアップロードをキャンセルします");
                // アップロードファイルを削除
                // uploadImage[i].value = "";
                // uploadImage[i].disabled = true;
                displayImage[i].src = defaultImage_src[i];
                // 元の画像がある場合
                if (displayImage[i].alt !== "NO IMAGE") {
                    // displayImage[i].classList.add('opacity');
                }
                // uploadImage[i].disabled = false;

                // 元の画像がない場合
                if (displayImage[i].alt == "NO IMAGE") {
                    // 削除した画像より表示順が後の画像のdisplayOrder.valueを1減らす
                    for (let j = 0; j < displayOrder.length; j++) {
                        if (displayOrder[j].disabled == false && displayOrder[j].value > displayOrder[i].value) {
                            displayOrder[j].value--;
                        }
                    }
                    // 表示順の選択肢の最大値を消す
                    for (let j = 0; j < displayOrder.length; j++) {
                        let option = displayOrder[j].getElementsByTagName('option');
                        // console.log(option);
                        displayOrder[j].remove(option.length - 1);
                        // console.log(option);
                        if (displayOrder[j].disabled == true) {
                            displayOrder[j].value = "";
                        }
                    }
                    displayOrder[i].value = "";
                    displayOrder[i].disabled = true;
                    deleteImage[i].disabled = true;
                }
            }
        });
    }

    // 画像削除のチェックの処理
    for (let i = 0; i < deleteImage.length; i++) {
        deleteImage[i].addEventListener('change', (e) => {
            // チェックを入れたとき
            if(e.target.checked) {
                // console.log("チェックされました");
                // アップロードのファイルが選択されている場合
                if (uploadImage[i].value !== "") {
                    if (confirm('画像の選択を解除します')) {
                        // アップロードファイルを削除
                        uploadImage[i].value = "";
                        uploadImage[i].disabled = true;
                        displayImage[i].src = defaultImage_src[i];
                        // if (displayImage[i].alt !== "NO IMAGE") {
                        //     displayImage[i].classList.add('opacity');
                        // }
                        uploadImage[i].disabled = false;
                        deleteImage[i].checked = false;

                        // 元の画像がない場合
                        if (displayImage[i].alt == "NO IMAGE") {
                            // 削除した画像より表示順が後の画像のdisplayOrder.valueを１減らす
                            for (let j = 0; j < displayOrder.length; j++) {
                                if (displayOrder[j].disabled == false && displayOrder[j].value > displayOrder[i].value) {
                                    displayOrder[j].value--;
                                }
                            }
                            // 表示順の選択肢の最大値を消す
                            for (let j = 0; j < displayOrder.length; j++) {
                                let option = displayOrder[j].getElementsByTagName('option');
                                // console.log(option);
                                displayOrder[j].remove(option.length - 1);
                                // console.log(option);
                            }
                            displayOrder[i].value = "";
                            displayOrder[i].disabled = true;
                            deleteImage[i].disabled = true;
                        } else {
                        }

                        // console.log("実行されました１");
                        return true;
                    } else {
                        e.target.checked = false;
                        // console.log("キャンセルされました");
                        return false;
                    }

                // 元の画像がある場合、元の画像を削除
                } else if (displayImage[i].alt !== "NO IMAGE") {
                    if (confirm('画像を削除します')) {
                        // defaultImage_src[i] = "image/no_image.png";
                        // displayImage[i].src = "image/no_image.png";
                        // displayImage[i].alt = "NO IMAGE";
                        displayImage[i].classList.add("opacity");

                        // 削除した画像より表示順が後の画像のdisplayOrder.valueを１減らす
                        for (let j = 0; j < displayOrder.length; j++) {
                            if (displayOrder[j].disabled == false && displayOrder[j].value > displayOrder[i].value) {
                                displayOrder[j].value--;
                            }
                        }
                        // 表示順の選択肢の最大値を消す
                        for (let j = 0; j < displayOrder.length; j++) {
                            let option = displayOrder[j].getElementsByTagName('option');
                            // console.log(option);
                            displayOrder[j].remove(option.length - 1);
                            // console.log(option);
                        }
                        displayOrder[i].value = "";
                        displayOrder[i].disabled = true;
                        // console.log("実行されました２");
                        return true;
                    } else {
                        e.target.checked = false;
                        // console.log("キャンセルされました");
                        return false;
                    }
                }

            // チェックを外したとき
            } else {
                // console.log("チェックが解除されました");
                uploadImage[i].disabled = false;
                displayOrder[i].disabled = false;
                displayImage[i].classList.remove("opacity");

                for (let j = 0; j < displayOrder.length; j++) {
                    // セットされた以外の領域の表示順の選択肢を増やす
                    if (j !== i) {
                        if (displayOrder[j].value !== "") {
                            let newOption = document.createElement('option');
                            let option = displayOrder[j].getElementsByTagName('option');
                            if (!option) {
                                newOption.innerHTML = 1;
                            } else {
                                newOption.innerHTML = option.length + 1;
                            }
                            displayOrder[j].appendChild(newOption);
                        }
                    } else {
                        // セットされた領域に表示順を追加
                        // 表示順を消す
                        let option = displayOrder[i].getElementsByTagName('option');
                        // console.log(option);
                        // console.log("長さ" + option.length);
                        let optionLength = option.length;
                        for (let k = 0; k < optionLength; k++) {
                            // console.log(k);
                            // console.log("要素の中身" + option[k]);
                            option[0].remove();
                        }
                        // 表示順を追加
                        let displayOrderValue = 0;
                        // console.log(displayOrder.length);
                        for (let k = 0; k < displayOrder.length; k++) {
                            if (displayOrder[k].disabled == false) {
                                // console.log(k);
                                displayOrderValue++;
                                    let newOption = document.createElement('option');
                                let option = displayOrder[i].getElementsByTagName('option');
                                newOption.innerHTML = displayOrderValue;
                                displayOrder[i].appendChild(newOption);
                                displayOrder[i].value = displayOrderValue;
                            }
                        }
                    }
                }
            }
        });
    }

    // 地図の中心座標とズームレベルを指定
    let pointX =<?php if ($point_x !== "") {echo $point_x;} else {echo '""';} ?>;
    let pointY =<?php if ($point_y !== "") {echo $point_y;} else {echo '""';} ?>;
    // console.log(pointX);
    // console.log(pointY);
    if (pointX !== "" && pointY !== "") {
        var map4 = L.map('map4').setView([pointY, pointX], 16);
    }
    else {
        var map4 = L.map('map4').setView([34.39803368302145, 132.47533146205527], 14);
    }

    // OpenStreetMapのタイルを追加
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map4);

    // アイコンの設定
    var SpringIcon = L.icon({
        iconUrl: './image/spring.png',
        iconSize: [52, 48],
        iconAnchor: [26, 24],
    });

    var SummerIcon = L.icon({
        iconUrl: './image/summer.png',
        iconSize: [77, 55],
        iconAnchor: [38.5, 27.5],
    });

    var AutumnIcon = L.icon({
        iconUrl: './image/autumn.png',
        iconSize: [55, 58],
        iconAnchor: [27.5, 29],
    });

    var WinterIcon = L.icon({
        iconUrl: './image/winter.png',
        iconSize: [50, 50],
        iconAnchor: [25, 25],
    });

    // アイコンを取得
    let seasonName = "<?php echo $season_name; ?>";
    // console.log(seasonName);

    let dispIcon = "";
    switch (seasonName) {
        case "春":
            dispIcon = SpringIcon;
            break;
        case "夏":
            dispIcon = SummerIcon;
            break;
        case "秋":
            dispIcon = AutumnIcon;
            break;
        case "冬":
            dispIcon = WinterIcon;
            break;
    }
    // マーカーを追加
    let dispArticlePoint = L.marker([pointY, pointX], { icon: dispIcon });
    dispArticlePoint.addTo(map4);
    let icon = document.getElementsByClassName('leaflet-marker-icon leaflet-zoom-animated leaflet-interactive')[0];
    // console.log(icon);

    let inputPointX = document.getElementById("lng");
    // console.log(inputPointX);
    let inputPointY = document.getElementById("lat");
    // console.log(inputPointY);
    let defaultPointX_value = inputPointX.value;
    let defaultPointY_value = inputPointY.value;

    let deletePoint = document.getElementById("delete_point");
    if (defaultPointX_value !== "" && defaultPointY_value !== "") {
        deletePoint.disabled = false;
    }

    // 季節を変更したとき
    let seasonInput = document.getElementsByName("season");
    // console.log(seasonInput);
    for (let i = 0; i < seasonInput.length; i++) {
        seasonInput[i].addEventListener('change', (e) => {
            // console.log("選択されました");
            let seasonLabel = e.target.closest("label");
            let newSeasonName = seasonLabel.textContent;
            // console.log(newSeasonName);
            switch (newSeasonName) {
                case "春":
                    dispArticlePoint.setIcon(SpringIcon);
                    break;
                case "夏":
                    dispArticlePoint.setIcon(SummerIcon);
                    break;
                case "秋":
                    dispArticlePoint.setIcon(AutumnIcon);
                    break;
                case "冬":
                    dispArticlePoint.setIcon(WinterIcon);
                    break;
            }
        })
    }

    //座標取得クリックイベント
    map4.on('click', function(e) {
        //クリック位置経緯度取得
        let lat = e.latlng.lat;
        let lng = e.latlng.lng;
        //経緯度表示
        if (window.confirm("ポイント指定Ｘ： " + lng + "\nポイント指定Ｙ： " + lat +"\nに書き替えますか？")) {
            inputPointX.value = lng;
            inputPointY.value = lat;
            deletePoint.disabled = false;
            // 地図上にポイント表示
            dispArticlePoint.setLatLng([lat, lng]);
        };
    });

    // 座標削除チェックイベント
    deletePoint.addEventListener("change", (e) => {
        if (e.target.checked) {
            let icon = document.getElementsByClassName('leaflet-marker-icon leaflet-zoom-animated leaflet-interactive')[0];
            // console.log(icon);
            // 座標が指定されている場合
            if (inputPointX.value !== defaultPointX_value && inputPointY.value !== defaultPointY_value) {
                if (window.confirm("指定された座標を削除してよろしいですか？")) {
                    inputPointX.value = defaultPointX_value;
                    inputPointY.value = defaultPointY_value;
                    icon.style.display = "none";
                    if (!defaultPointX_value && !defaultPointY_value) {
                        deletePoint.disabled = true;
                    }
                    else {
                        dispArticlePoint.setLatLng([pointY, pointX]);
                        icon.style.display = "block";
                    }
                }
                deletePoint.checked = false;    
            }
            // セットされた座標を削除する場合
            else {
                if (window.confirm("座標を削除します")) {
                    inputPointX.disabled = true;
                    inputPointY.disabled = true;
                    icon.style.display = "none";
                }
            }
        }
        // チェック解除
        else {
            inputPointX.disabled = false;
            inputPointY.disabled = false;
            icon.style.display = "block";
        }
    });

    // 保存ボタンを押したときの処理
    function CheckSave() {
        
        if (charCounterTitle.textContent > maxLengthTitle) {
            alert("タイトルは半角" + maxLengthTitle + "文字以内にしてください");
            return false;
        }

        if (charCounterText.textContent > maxLengthText) {
            alert("本文は半角" + maxLengthText + "文字以内にしてください");
            return false;
        }

        // 本文の改行コードをカウントする
        let textarea = document.getElementById("text"); // テキストエリアの要素を取得
        // console.log(textarea);
        let targetChar = "\n";
        // console.log(targetChar);
        let enterKeyCount = textarea.value.split(targetChar).length - 1;
        // console.log(enterKeyCount);
        let alertTextCount = "";
        // console.log(alertTextCount);
        // console.log(maxLengthText);
        let textLength = charCounterText.textContent;
        // console.log(textLength);

        if (window.matchMedia("(max-width: 601px)").matches) {
            for (let i = 0; i <= maxLineText; i++) {
                if (textLength > maxLengthText - 80 * (i + 1) && textLength <= maxLengthText - 80 * i && enterKeyCount > i) {
                    // if (80 * i + 1 <= textLength <= 80 * (i + 1) && enterKeyCount >= 11 - i) {
                    // alert(i);
                    alertTextCount = "本文は半角80文字×12行の枠に収まるように入力してください";
                }
                // console.log(alertTextCount);
            }
            if (alertTextCount !== "") {
                alert(alertTextCount);
                return false;
            }
        }
        
        let selectedOptionValue = [];
        for (let i = 0; i < displayOrder.length; i++) {
            if (displayOrder[i].disabled == false) {
                selectedOptionValue.push(displayOrder[i].value);
            }
        }
        let setElements = new Set(selectedOptionValue);
        if (setElements.size !== selectedOptionValue.length) {
            alert("表示順に重複があります");
            return false;
        }

        if(confirm('保存しますか？')) {
            return true;
        }
        else {
            alert('キャンセルされました');
            return false;
        }
    }
    </script>
</body>
</html>