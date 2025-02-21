<!-- 画像のプレビュー参考RL -->
<!-- https://www.wabiapp.com/WabiSampleSource/html5/input_file_onchange.html#google_vignette -->

<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';

$max_article_id = $dbm->get_max_article_id();
$new_article_id = $max_article_id + 1;

// echo "<br><br><br>";
// echo $new_article_id;

// $article_id = $_GET["id"];
// $article_image = $dbm->get_article_image($article_id);

// // 保存ボタンを押したときの処理
// if (isset($_POST['save'])) {
//     $dbm->save_article_edit($article_id);

//     // 画像をフォルダーに保存
//     // https://qiita.com/okdyy75/items/669dd51b432ee2c1dfbc
//     // https://gray-code.com/php/save-the-upload-file/#google_vignette
    
//     // if文で画像がもともとある場合ともともとない場合で分ける
//     // もともとある場合　データベースを上書き　id取得できる？？
//     // もともとない場合　あたらしいレコードを作る

//     // phpinfo();
//     $image_1 = $_FILES['image_1'];
//     // echo $_FILES['image_1']['tmp_name'];
//     // print_r($image_1);

//     $image_length = $dbm->get_image_length();
//     echo '<br><br><br>';
//     echo $image_length;

//     if (isset($image_1)) {
//         $new_image_url = 'upload/' . $image_length + 1 . '.jpg';
//         echo $new_image_url;
//         // phpinfo();
//         move_uploaded_file($_FILES['image_1']['tmp_name'], $new_image_url);
//         if (isset($article_image[0])) {
//             // echo '<br><br><br>';
//             print_r($article_image[0]);
//             $return = $dbm->update_image($article_id, $new_image_url);
//             echo $return;
//         } else {
//             print_r($artile_image);
//             // jpeg以外も作る！！
//         }
//     }


// $error = $_FILES["image_1"]["error"];
// $message = "";
// switch ($error ) {
// case UPLOAD_ERR_OK:
// $message = "成功";
// break;
// case UPLOAD_ERR_INI_SIZE:
// $message = "アップロードされたファイルは、php.ini のupload_max_filesizeの値を超えています。";
// break;
// case UPLOAD_ERR_FORM_SIZE:
// $message = " アップロードされたファイルは、HTML フォームで指定されたMAX_FILE_SIZEを超えています";
// break;
// case UPLOAD_ERR_PARTIAL:
// $message = "アップロードされたファイルは一部のみしかアップロードされていません。";
// break;
// case UPLOAD_ERR_NO_FILE:
// $message = "ファイルはアップロードされませんでした。";
// break;
// case UPLOAD_ERR_NO_TMP_DIR:
// $message = "テンポラリフォルダがありません。";
// break;
// case UPLOAD_ERR_CANT_WRITE:
// $message = "ィスクへの書き込みに失敗しました。";
// break;
// case UPLOAD_ERR_EXTENSION:
// $message = "PHP の拡張モジュールがファイルのアップロードを中止しました。";
// break;
// default:
// $message = "その他の例外";
// break;
// }
// echo $message;

    
    // $image_length = get_image_length();
    // move_uploaded_file($_FILES['image_2']['tmp_name'],'./upload/' . $image_length + 1 . '.jpg');
    // $image_length = get_image_length();
    // move_uploaded_file($_FILES['image_3']['tmp_name'],'./upload/' . $image_length + 1 . '.jpg');
// }

// $article_info = $dbm->get_article_info($article_id);
// // echo "<br><br><br>";
// // print_r($article_image);
// // echo "<br>";
// // print_r($article_info);

// // $user_name = $article_info['user_name'];
// $category_id = $article_info['category_id'];
// // echo $category_id;
// $category_length = $dbm->get_category_length();
// // echo "<br>";
// // echo $category_length;
// // $category_name = $article_info['category_name'];
// $season_id = $article_info['season_id'];
// // echo $season_id;
// $season_length = $dbm->get_season_length();
// // $season_name = $article_info['season_name'];
// $title = $article_info['title'];
// $text = $article_info['text'];
// $point_x = $article_info['point_x'];
// // $point_x = $json_encode($point_x);
// $point_y = $article_info['point_y'];
// // $point_y = $json_encode($point_y);
// $registration_date = $article_info['registration_date'];
// // var_dump($registration_date);
// $date = new DateTime($registration_date);
$date = date("Y-m-d");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1101px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1100px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:600px)" href="css/style_mobile.css">
    <link rel="icon" type="image/png" sizes="32x32" href="GIMP/歩く鳥.png">
    <title>歩こう広島｜アップロード</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="js/script.js"></script>
</head>
<body class="background_color">
    <header class="flex">
        <a href="account_top.php">
            <img class="header_bird" src="GIMP/歩く鳥.png" alt="歩く鳥">
            <img class="header_logo" src="GIMP/ロゴ.png" alt="ロゴ">
        </a>
        <input type="radio" id="header_menu_guide" class="display_none">
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
    <main class="edit">
        <h2>アップロード</h2>
        <div id="upload_container" class="flex">
            <form id="upload_form" action="edit.php?id=<?php echo $new_article_id; ?>" method="POST" onSubmit="return CheckUpload()" enctype="multipart/form-data">
            <?php show_upload($date, $new_article_id);
                // <ul>
                //     <li class="flex">
                //         <p class="list_title width_120px" for="date">投稿日</p>
                //         <input class="margin_0" type="date" name="registration_date" value="{$date->format('Y年m月d日')}" readonly>
                //     </li>
                //     <li class="flex">
                //         <p class="list_title width_120px">カテゴリ</p>
                //         <div class="flex radio_container margin_0 gap_1vw">
                //             <label><input type="radio" id="event" name="category[]" value="1">イベント</label>
                //             <label><input type="radio" id="information" name="category[]" value="2">情報</label>
                //             <label><input type="radio" id="discovery" name="category[]" value="3">発見</label>
                //             <label><input type="radio" id="communication" name="category[]" value="4">連絡</label>
                //         </div>
                //     </li>
                //     <li class="flex">
                //         <p class="list_title width_120px">季節</p>
                //         <div class="flex radio_container margin_0 gap_1vw">
                //             <label><input type="radio" id="spring" name="season[]" value="1">春</label>
                //             <label><input type="radio" id="summer" name="season[]" value="2">夏</label>
                //             <label><input type="radio" id="autumn" name="season[]" value="3">秋</label>
                //             <label><input type="radio" id="winter" name="season[]" value="4">冬</label>
                //         </div>
                //     </li>
                //     <li class="flex">
                //         <label class="list_title width_120px" for="title">タイトル</label>
                //         <input class="margin_0" type="text" id="title" name="title" style="width: 400px;" required>
                //     </li>
                //     <li class="flex">
                //         <label class="list_title width_120px" for="text">本文</label>
                //         <textarea class="margin_0" id="text" name="text" style="width: 400px; height: 13em;" required></textarea>
                //     </li>
                //     <li class="flex">
                //         <label class="list_title width_120px" for="image_1">画像１</label>
                //         <input class="margin_0" type="file" id="image_1" name="image_1" accept="image/*">
                //     </li>
                //     <li class="flex">
                //         <label class="list_title width_120px" for="image_2">画像２</label>
                //         <input class="margin_0" type="file" id="image_2" name="image_2" accept="image/*">
                //     </li>
                //     <li class="flex">
                //         <label class="list_title width_120px" for="image_3">画像３</label>
                //         <input class="margin_0" type="file" id="image_3" name="image_3" accept="image/*">
                //     </li>
                //     <li class="flex">
                //         <label class="list_title width_120px" for="lat">ポイント指定Ｙ</label>
                //         <input class="margin_0" type="text" id="lat" name="lat" readonly>
                //     </li>
                //     <li class="flex" for="lng">
                //         <label class="list_title width_120px">ポイント指定Ｘ</label>
                //         <input class="margin_0" type="text" id="lng" name="lng" readonly>
                //     </li>
                // </ul>
                ?>
                <div class="flex justify_content_flex_end">
                    <button type="submit" id="save_button" name="upload">アップロード</button>
                </div>  
            </form>  
            <div id="upload_map_container" class="flex align_items_start">
                <div id="map3"></div>
            </div>
        </div>
    </main>

    <script>

        // 画像を選択すると表示順を表示
        let inputImageArray = document.querySelectorAll('input[type="file"]');
        let selectArray = document.getElementsByTagName('select');
        console.log(inputImageArray);
        console.log(selectArray);
        let inputChangeCount = 1;
        for (let i = 0; i < inputImageArray.length; i++) {
            console.log(inputImageArray[i]);
            inputImageArray[i].addEventListener('change', (e) => {
                if (selectArray[i].disabled == true) {
                    for (let j = 0; j < 3; j++) {
                        let option = document.createElement('option');
                        option.innerHTML = inputChangeCount;
                        selectArray[j].appendChild(option);
                    }
                    selectArray[i].disabled = false;
                    inputChangeCount += 1;
                }
            })
        }

        // アップロードボタンを押したときの処理
        function CheckUpload() {
            
            let selectedOptionValueArray = [];
            for (let i = 0; i < selectArray.length; i++) {
                if (!selectArray[i].disabled) {
                    selectedOptionValueArray.push(selectArray[i].options[selectArray[i].options.selectedIndex].value);
                }
            }
            let setElements = new Set(selectedOptionValueArray);

            if (setElements.size !== selectedOptionValueArray.length) {
                alert("表示順に重複があります");
                return false;

            } else {
                if(confirm('アップロードしますか？')) {
                    return true;
                } else {
                    alert('キャンセルされました');
                    return false;
                }
                alert("alert");
            }
        }

    </script>
</body>
</html>