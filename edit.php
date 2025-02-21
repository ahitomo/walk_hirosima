<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';

$article_id = $_GET["id"];
$article_image = $dbm->get_article_image($article_id);
echo "<br><br><br>";
print_r($article_image);
$image_id_1 = $article_image[0]['image_id'];
$image_id_2 = $article_image[1]['image_id'];
$image_id_3 = $article_image[2]['image_id'];

// echo '<br><br><br>';

// アップロード画面から遷移した場合
if (isset($_POST["upload"])) {
    // 記事を登録する
    $dbm->save_new_article($article_id);
    $max_image_id = $dbm->get_max_image_id();
    // echo($max_image_id);
    // phpinfo();

    // 画像を登録する
    if ($_FILES["image_1"]["error"] == 0) {
        // echo "image_1はあります";
        $display_order = $_POST["display_order_1"];
        // echo $display_order;
        $new_image_url = 'upload/' . $article_id . '-' . $display_order . '.jpg';
        // echo $new_image_url;
        move_uploaded_file($_FILES['image_1']['tmp_name'], $new_image_url);
        $dbm->save_new_image($image_id_1, $new_image_url);
    } else {
        // echo "image_1はありません";
    }
    if ($_FILES["image_2"]["error"] == 0) {
        // echo "image_2はあります";
        $display_order = $_POST["display_order_2"];
        // echo $display_order;
        $new_image_url = 'upload/' . $article_id . '-' . $display_order . '.jpg';
        // echo $new_image_url;
        move_uploaded_file($_FILES['image_2']['tmp_name'], $new_image_url);
        $dbm->save_new_image($image_id_2, $new_image_url);
    } else {
        // echo "image_2はありません";
    }
    if ($_FILES["image_3"]["error"] == 0) {
        // echo "image_3はあります";
        $display_order = $_POST["display_order_3"];
        // echo $display_order;
        $new_image_url = 'upload/' . $article_id . '-' . $display_order . '.jpg';
        // echo $new_image_url;
        move_uploaded_file($_FILES['image_3']['tmp_name'], $new_image_url);
        $dbm->save_new_image($image_id_3, $new_image_url);
    } else {
        // echo "image_3はありません";
    }
}

// 保存ボタンを押したときの処理
if (isset($_POST['save'])) {
    // 記事の変更を登録する
    $dbm->save_article_edit($article_id);
    $max_image_id = $dbm->get_max_image_id();

    // 画像の変更を保存する
    // 画像1がセットされている場合
    $image_id_1 = $article_image[0]['image_id'];
    if ($image_id_1) {
        echo "<br><br><br>";
        echo "image_id_1はあります";

        // 画像1の表示順を取得
        $display_order_1 = $_POST["display_order_1"];

        // 削除のチェックが入っていたら画像を削除する
        $delete_image_1 = $_POST['delete_image_1'];
        if ($delete_image_1) {
            echo "画像1を削除します";
            

        } else {
            echo "画像1は削除しません";
        }
    } else {
        echo "image_id_1はありません";
    }

    // // 画像の変更を保存する
    // // 画像1がセットされている場合
    // if (isset($_POST["display_order_1"])) {
    //     // 画像1の表示順を取得
    //     $display_order_1 = $_POST["display_order_1"];
    //     // ファイルアップロードがある場合
    //     if ($_FILES["image_1"] !== "") {
    //         $new_image_url = 'upload/' . $article_id . '-' . $display_order_1 . '.jpg';
    //         // echo $new_image_url;
    //         move_uploaded_file($_FILES['image_1']['tmp_name'], $new_image_url);        
    //         if ($article_image[0]) {
    //             echo "画像１を変更します";
    //             // // 新しく画像を追加する
    //             // $dbm->save_new_image($article_id, $display_order_1, $new_image_url);
    //             // 元の画像を変更する
    //             $dbm->update_image($image_id_1, $article_id, $display_order_1, $new_image_url);
    //         } else {
    //             echo "why";
    //         }
    //     } else if ($display_order_1 !== 1) {
    //         // 表示順のみ変更の場合
    //         echo "<br><br><br>";
    //         echo $display_order_1;
    //         echo "画像１の表示順を変更します";
    //         echo $image_id_1;
    //         $dbm->change_display_order($image_id_1, $display_order_1);
    //     } else {
    //         echo "なぜ";
    //     }
    // }else {
    //     echo "その他";
    // }


    // // 画像1の表示順がある場合
    // $display_order_1 = $_POST["display_order_1"];
    // if ($display_order_1 == "") {
    //     // 画像1の表示順を取得
    //     echo "<br><br><br>";
    //     // echo $display_order_1;
    //     // echo "FILES" . $_FILES["image_1"];
    //     // ファイルアップロードがある場合
    //     if ($_FILES["image_1"] !== "") {

    //         $new_image_url = 'upload/' . $article_id . '-' . $display_order_1 . '.jpg';
    //         // echo $new_image_url;
    //         move_uploaded_file($_FILES['image_1']['tmp_name'], $new_image_url);        
    //         if (!$image_id_1) {
    //             // 元の画像を変更する
    //             $dbm->update_image($image_id_1, $new_image_url);
    //         } else {
    //             // 新しく画像を追加する
    //             echo "新しく画像を追加する";
    //             $dbm->save_new_image($article_id, $display_order_1, $new_image_url);
    //         }
    // } else if ($display_order_1 !== 1) {
    //     // 表示順のみ変更の場合
    //     echo "<br><br><br>";
    //     echo $display_order_1;
    //     echo "画像１の表示順を変更します";
    //     echo $image_id_1;
    //     $return = $dbm->change_display_order($image_id_1, $display_order_1);
    //     echo $return;
    // } else {
    //     echo "<br><br><br>";
    //     echo "else";
    // }
    // // 画像1がセットされていない場合
    // } else {
    //     // 新しく画像を追加する
    //     $dbm->save_new_image($article_id, $display_order_1, $new_image_url);

    // }

    // 画像2がセットされている場合
    if (isset($_POST["display_order_2"])) {
        // 画像２の表示順を取得
        $display_order_2 = $_POST["display_order_2"];
        // ファイルアップロードがある場合
        if ($_FILES["image_2"] !== "") {
            $new_image_url = 'upload/' . $article_id . '-' . $display_order_2 . '.jpg';
            // echo $new_image_url;
            move_uploaded_file($_FILES['image_2']['tmp_name'], $new_image_url);        
            if ($article_image[1]) {
                echo "画像２を変更します";
                // // 新しく画像を追加する
                // $dbm->save_new_image($article_id, $display_order_1, $new_image_url);
                // 元の画像を変更する
                $dbm->update_image($image_id_2, $article_id, $display_order_2, $new_image_url);
            }
        } else if ($display_order_2 !== 2) {
            // 表示順のみ変更の場合
            echo "<br><br><br>";
            echo $display_order_1;
            echo "画像２の表示順を変更します";
            echo $image_id_2;
            $dbm->change_display_order($image_id_2, $display_order_2);
        }
    }else {
    }

    // 画像3がセットされている場合
    if (isset($_POST["display_order_3"])) {
        // 画像３の表示順を取得
        $display_order_3 = $_POST["display_order_3"];
        // ファイルアップロードがある場合
        if ($_FILES["image_3"] !== "") {
            $new_image_url = 'upload/' . $article_id . '-' . $display_order_3 . '.jpg';
            // echo $new_image_url;
            move_uploaded_file($_FILES['image_3']['tmp_name'], $new_image_url);        
            if ($article_image[2]) {
                echo "画像３を変更します";
                // // 新しく画像を追加する
                // $dbm->save_new_image($article_id, $display_order_1, $new_image_url);
                // 元の画像を変更する
                $dbm->update_image($image_id_3, $article_id, $display_order_3, $new_image_url);
            }
        } else if ($display_order_3 !== 3) {
            // 表示順のみ変更の場合
            echo "<br><br><br>";
            echo $display_order_3;
            echo "画像３の表示順を変更します";
            echo $image_id_3;
            $dbm->change_display_order($image_id_3, $display_order_3);
        }
    }else {
    }
    // jpeg以外も作る！！
}

$article_image = $dbm->get_article_image($article_id);
echo "<br><br><br>";
print_r($article_image);

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
    <link rel="icon" type="image/png" sizes="32x32" href="GIMP/歩く鳥.png">
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
        <h2>記事編集</h2>
        <div id="upload_container" class="flex">
            <form id="upload_form" action="edit.php?id=<?php echo $article_id; ?>" method="POST" onsubmit="return CheckSave()" enctype="multipart/form-data">
            <?php show_edit($date, $title, $text, $point_x, $point_y, $article_image);
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
                    <button type="submit" id="save_button" name="save">保存</button>
                </div>  
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

        // // 画像の表示順を表示
        // let displayOrderArray = <?php echo $json_display_order_array; ?>;
        // console.log(displayOrderArray);
        // let notEmptyArray = displayOrderArray.filter(Boolean);
        // console.log(notEmptyArray);
        // let selectArray = document.getElementsByTagName('select');
        // console.log(selectArray);
        // for (let i = 0; i < notEmptyArray.length; i++) {
        //     selectArray[i].disabled = false;
        //     for (let j = 0; j < notEmptyArray.length; j++) {
        //             let option = document.createElement('option');
        //             option.innerHTML = j + 1;
        //             selectArray[i].appendChild(option);
        //         }
        //     selectArray[i].value = notEmptyArray[i];
        // }

        // // 画像を増やすと表示順を表示
        // let inputImageArray = document.querySelectorAll('input[type="file"]');
        // // console.log(inputImageArray);
        // displayImageArray = [];
        // for (let i =0; i < notEmptyArray.length; i++) {
        //     displayImageArray[i] = i;
        // }
        // console.log(displayImageArray);
        // let inputChangeCount = 1;
        // // console.log(imageLength);
        // for (let i = 0; i < inputImageArray.length; i++) {
        //     // console.log(inputImageArray[i]);
        //     inputImageArray[i].addEventListener('change', (e) => {
        //         if (!notEmptyArray[i]) {
        //             if (selectArray[i].disabled == true) {
        //                 // 他の画像の表示順の選択肢を１増やす
        //                 for (let j = 0; j < displayImageArray.length; j++) {
        //                     let option = document.createElement('option');
        //                     option.innerHTML = displayImageArray.length + 1;
        //                     selectArray[Number(displayImageArray[j])].appendChild(option);
        //                 }

        //                 // 新しい画像の表示順を有効に
        //                 for (let j = 0; j < displayImageArray.length + 1; j++) {
        //                 let option = document.createElement('option');
        //                 option.innerHTML = j + 1;
        //                 selectArray[i].appendChild(option);
        //                 selectArray[i].value = displayImageArray.length + 1;
        //                 }

        //                 selectArray[i].disabled = false;
        //                 inputChangeCount += 1;
        //                 displayImageArray.push(i);
        //             }
        //         }
        //     })
        // }

        // // 画像削除のチェックボックスを表示
        // let inputDeleteImage = document.querySelectorAll('input[type="checkbox"]');
        // console.log(inputDeleteImage);
        // for (let i = 0; i < notEmptyArray.length; i++) {
        //     inputDeleteImage[i].disabled = false;
        // }

        // // 画像削除のチェックを入れると画像選択と表示順をdisabledに
        // for (let i = 0; i < notEmptyArray.length; i++) {
        //     inputDeleteImage[i].addEventListener('change', (e)=> {
        //         if (e.target.checked) {
        //             inputImageArray[i].value = "";
        //             inputImageArray[i].disabled = true;

        //             let targetSelectValue = selectArray[i].value;
        //             let targetOptionArray = selectArray[i].querySelectorAll('option');
        //             console.log(targetOptionArray.length);
        //             for (let j = 0; j < notEmptyArray.length; j++) {
        //                 if (selectArray[j].value > targetSelectValue) {
        //                     selectArray[j].value--;
        //                     let optionArray = selectArray[j].querySelectorAll('option');
        //                     console.log(optionArray);
        //                     optionArray[optionArray.length - 1].remove();
        //                 }
        //                 console.log(targetOptionArray[j]);
        //                 targetOptionArray[j].remove();
        //             }

        //             selectArray[i].value = "";
        //             selectArray[i].disabled = true;

        //         } else {
        //             let n = 0;
        //             for (let j = 0; j < displayImageArray.length; j++) {
        //                 if (inputDeleteImage[j].value) {
        //                     n++;
        //                 }
        //             }
        //             console.log(n);
        //             // 他の画像の表示順の選択肢を１増やす
        //             for (let j = 0; j < displayImageArray.length; j++) {
        //                 if (j !== i) {
        //                     let option = document.createElement('option');
        //                     option.innerHTML = displayImageArray.length - n;
        //                     selectArray[Number(displayImageArray[j])].appendChild(option);
        //                 }
        //             }

        //             // 新しい画像の表示順を有効に
        //             for (let j = 0; j < displayImageArray.length; j++) {
        //             let option = document.createElement('option');
        //             option.innerHTML = j + 1;
        //             selectArray[i].appendChild(option);
        //             selectArray[i].value = displayImageArray.length;
        //             }

        //             selectArray[i].disabled = false;
        //             inputChangeCount += 1;
        //             displayImageArray.push(i);

        //             inputImageArray[i].disabled = false;
        //             selectArray[i].disabled = false;
        //         }
        //     })
        // }

        let displayImage = document.querySelectorAll('form img');
        console.log(displayImage);
        let uploadImage = document.querySelectorAll('input[type="file"]');
        console.log(uploadImage);
        let displayOrder = document.getElementsByTagName('select');
        console.log(displayOrder);
        let deleteImage = document.querySelectorAll('input[type="checkbox"]');
        console.log(deleteImage);

        // 画像がセットされていない場合は表示順をdisableに
        let image_src = [];
        for (let i = 0; i < displayImage.length; i++) {
            image_src[i] = displayImage[i].src;
            console.log(image_src);
            if (displayImage[i].alt == "NO IMAGE") {
                console.log("ソースありまへんで");
                displayOrder[i].disabled = true;
                deleteImage[i].disabled = true;
            } else {
                console.log("ソースありまんで");
            }
        }

        // 画像がセットされている場合は表示順を表示
        let imageLength = 0;
        let jsonDisplayOrderArray = <?php echo $json_display_order_array; ?>;
        console.log(jsonDisplayOrderArray);
        let setDisplayOrderArray = jsonDisplayOrderArray.filter(Boolean);
        console.log(setDisplayOrderArray);
        for (let i = 0; i < setDisplayOrderArray.length; i++) {
            for (let j = 0; j < setDisplayOrderArray.length; j++) {
                let newOption = document.createElement('option');
                newOption.innerHTML = i + 1;
                displayOrder[j].appendChild(newOption);
            }
            displayOrder[i].value = i + 1;
        }

        // 写真をアップロードした場合
        for (let i = 0; i < uploadImage.length; i++) {
            uploadImage[i].addEventListener('change', (e) => {
                // プレビュー表示する
                let file = e.target.files[0];
                if(!file) {
                    return;
                }
                let reader = new FileReader();
                reader.onload = (e) => {
                    displayImage[i].src = e.target.result;
                };
                reader.readAsDataURL(file);

                displayOrder[i].disabled = false;
                deleteImage[i].disabled = false;

                // セットされていない領域に画像をアップロードした場合
                if (displayImage[i].alt == "NO IMAGE") {
                    console.log("初めてのアップロード");
                    for (let j = 0; j < displayOrder.length; j++) {
                        // セットされた以外の領域の表示順の選択肢を増やす
                        if (j !== i) {
                            let newOption = document.createElement('option');
                            let option = displayOrder[j].getElementsByTagName('option');
                            if (!option) {
                                newOption.innerHTML = 1;
                            }
                            newOption.innerHTML = option.length + 1;
                            displayOrder[j].appendChild(newOption);
                        } else {
                            // セットされた領域に表示順を追加
                            let option = displayOrder[i].getElementsByTagName('option');
                            for (let k = 0; k < option.length; k++) {
                                option[k].Sremove();
                            }
                            for (let k = 0; k < displayOrder.length; k++) {
                                if (!displayOrder[k].disabled) {
                                    let newOption = document.createElement('option');
                                    let option = displayOrder[j].getElementsByTagName('option');
                                    newOption.innerHTML = k + 1;
                                    displayOrder[j].appendChild(newOption);
                                    displayOrder[i].value = k + 1;
                                }
                            }
                        }
                    }
                } else {
                    console.log("elseだー");
                }
            });
        }

        // 削除のチェックの処理
        for (let i = 0; i < deleteImage.length; i++) {
            deleteImage[i].addEventListener('change', (e) => {
                // チェックを入れたとき
                if(e.target.checked) {
                    console.log("チェックされました");
                    // アップロードのファイルが選択されている場合
                    if (uploadImage[i].value !== "") {
                        if (confirm('画像の選択を解除します')) {
                            // 削除した画像より表示順が後の画像のdisplayOrder.valueを１減らす
                            for (let j = 0; j < displayOrder.length; j++) {
                                if (displayOrder[j].disabled == false && displayOrder[j].value > displayOrder[i].value) {
                                    displayOrder[j].value--;
                                }
                            }
                            // 表示順の選択肢の最大値を消す
                            for (let j = 0; j < displayOrder.length; j++) {
                                let option = displayOrder[j].getElementsByTagName('option');
                                console.log(option);
                                option[option.length - 1].remove();
                                console.log(option);
                            }
                            
                            // アップロードファイルを削除
                            uploadImage[i].value = "";
                            uploadImage[i].disabled = true;
                            displayOrder[i].disabled = true;
                            displayImage[i].src = image_src[i];
                            if (displayImage[i].alt !== "NO IMAGE") {
                                displayImage[i].classList.add('opacity');
                            }
                            image_src[i] = uploadImage[i].value;

                            uploadImage[i].disabled = false;
                            deleteImage[i].checked = false;
                            console.log("実行されました");
                            return true;
                        } else {
                            e.target.checked = false;
                            console.log("キャンセルされました");
                            return false;
                        }

                    // 元の画像がセットされている場合、画像を削除
                    } else if (displayImage[i].alt !== "NO IMAGE") {
                        if (confirm('画像を削除します')) {
                            displayImage[i].src = "image/no_image.png";
                            displayImage[i].alt = "NO IMAGE";

                            // 削除した画像より表示順が後の画像のdisplayOrder.valueを１減らす
                            for (let j = 0; j < displayOrder.length; j++) {
                                if (displayOrder[j].disabled == false && displayOrder[j].value > displayOrder[i].value) {
                                    displayOrder[j].value--;
                                }
                            }
                            // 表示順の選択肢の最大値を消す
                            for (let j = 0; j < displayOrder.length; j++) {
                                let option = displayOrder[j].getElementsByTagName('option');
                                console.log(option);
                                displayOrder[j].remove(option.length - 1);
                                console.log(option);
                            }
                            displayOrder[i].value = "";
                            displayOrder[i].disabled = true;
                            uploadImage[i].disabled = true;
                            console.log("実行されました");
                            return true;
                        } else {
                            e.target.checked = false;
                            console.log("キャンセルされました");
                            return false;
                        }
                    }

                // チェックを外したとき
                } else {
                    console.log("チェックが解除されました");
                    // if (displayImage[i].alt == "NO IMAGE") {
                    //     // 表示順の選択肢の最大値を増やす
                    //     for (let j = 0; j < displayOrder.length; j++) {
                    //         let newOption = document.createElement('option');
                    //         let option = displayOrder[j].getElementsByTagName('option');
                    //         newOption.innerHTML = option.length + 1;
                    //         displayOrder[j].appendChild(newOption);
                    //     }
                    //     // 削除を解除した画像の表示順を最大値にする
                    //     let option = displayOrder[i].getElementsByTagName('option');
                    //     displayOrder[i].value = option.length;

                    //     displayOrder[i].disabled = false;
                    //     displayImage[i].classList.remove('opacity');
                    // }
                    uploadImage[i].disabled = false;
                }
            });
        }

        // 保存ボタンを押したときの処理
        function CheckSave() {
            let displayOrderValue = [];
            for (let i = 0; i < displayOrder.length; i++) {
                if (!displayOrder[i].disabled) {
                    displayOrderValue.push(displayOrder[i].value);
                }
            }
            let setElements = new Set(displayOrderValue);

            if (setElements.size !== displayOrderValue.length) {
                alert("表示順に重複があります");
                return false;

            } else {
                if(confirm('保存しますか？')) {
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