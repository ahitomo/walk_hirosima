<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';
session_start();
if (isset($_SESSION['user_id']) == false) {
    echo '<script type="text/javascript">
    alert("ログインしてください");
    window.location.href="index.php";
    </script>';
} else {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
}

// 削除ボタンから遷移した場合
if (isset($_GET["id"])) {
    $article_id = $_GET["id"];
    $dbm->delete_article($article_id);
}

$max_article_id = $dbm->get_max_article_id();
$new_article_id = $max_article_id + 1;

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
    <link rel="icon" type="image/png" sizes="32x32" href="image/walking_bird.png">
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
                <li><a id="logout" href="logout.php" onclick="return CheckLogout()">ログアウト</a></li>
            </ul>
        </div>
    </header>
    <main class="edit">
        <h2>アップロード</h2>
        <div class="form_container flex">
            <form id="upload_form" action="edit.php?id=<?php echo $new_article_id; ?>" method="POST" onsubmit="return CheckUpload()" enctype="multipart/form-data">
            <?php show_upload($date);?>
            </form>  
            <div id="upload_map_container" class="flex align_items_start">
                <div id="map3"></div>
            </div>
        </div>
    </main>

    <script>

    let displayImage = document.querySelectorAll('form img');
    // console.log(displayImage);
    let uploadImage = document.querySelectorAll('input[type="file"]');
    // console.log(uploadImage);
    let displayOrder = document.getElementsByTagName('select');
    // console.log(displayOrder);
    // let deleteImage = document.querySelectorAll('input[type="checkbox"]');
    // console.log(deleteImage);

    // 画像がセットされていない場合は表示順をdisableに
    let image_src = [];
    for (let i = 0; i < displayImage.length; i++) {
        image_src[i] = displayImage[i].src;
        if (displayImage[i].alt == "NO IMAGE") {
            // console.log("画像がありません");
            displayOrder[i].disabled = true;
            // deleteImage[i].disabled = true;
        } else {
            // console.log("画像があります");
        }
    }
    // console.log(image_src);

    // タイトルの文字数制限とカウント

    let title = document.getElementById('title');
    let charCounterTitle = document.getElementsByTagName('span')[0];
    // console.log(charCounterTitle);
    let maxLengthTitle = 30;

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
            text.style.height = "12em";
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

        // 本文を最大行数以内に制限する関数
        const truncateTextToMaxLines = (text, clientHeight) => {
        const originalValue = text.value;
        while (text.scrollHeight > clientHeight) {
            text.value = text.value.slice(0, -1); // 末尾を削除
        }
        return text.value.length > 0 ? text.value : originalValue;
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
    return countText;
}

    // ファイルを変更した場合

    for (let i = 0; i < uploadImage.length; i++) {
        uploadImage[i].addEventListener('change', (e) => {
            // プレビュー表示する
            let file = e.target.files[0];
            // 写真をアップロードした場合
            if(file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    displayImage[i].src = e.target.result;
                };
                reader.readAsDataURL(file);

                displayOrder[i].disabled = false;
                // deleteImage[i].disabled = false;

                // セットされていない領域に画像をアップロードした場合
                if (displayImage[i].alt == "NO IMAGE") {
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
                    }
                }
            // ファイルの選択をキャンセルした場合
            } else {
                displayImage[i].src = image_src[i];
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
            }
        });
    }

    // 地図の中心座標とズームレベルを指定
    var map3 = L.map('map3').setView([34.39803368302145, 132.47533146205527], 14);

    // OpenStreetMapのタイルを追加
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map3);

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

    let inputPointX = document.getElementById("lng");
    // console.log(inputPointX);
    let inputPointY = document.getElementById("lat");
    // console.log(inputPointY);

    let deletePoint = document.getElementById("delete_point");

    let dispArticlePoint = "";

    //座標取得クリックイベント
    map3.on('click', function(e) {
        //クリック位置経緯度取得
        let lat = e.latlng.lat;
        let lng = e.latlng.lng;

        // アイコンを取得
        let seasonName = "";
        let seasonInput = document.getElementsByName("season");
        // console.log(seasonInput);
        for (let i = 0; i < seasonInput.length; i++) {
            if (seasonInput[i].checked) {
                let seasonLabel = seasonInput[i].closest("label");
                seasonName = seasonLabel.textContent;
            }
        }
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
            default:
                dispIcon = NoIcon;
                break;
        }

        //経緯度表示
        if (inputPointX.value == "" && inputPointY.value == "") {
            if (window.confirm("ポイント指定Ｘ： " + lng + "\nポイント指定Ｙ： " + lat +"\nに書き替えますか？")) {
                // console.log(lat);
                // console.log(lng);
                
                inputPointX.value = lng;
                inputPointY.value = lat;
                deletePoint.disabled = false;
                
                // マーカーを追加
                dispArticlePoint = L.marker([lat, lng], { icon: dispIcon });
                dispArticlePoint.addTo(map3);
                let icon = document.getElementsByClassName('leaflet-marker-icon leaflet-zoom-animated leaflet-interactive')[0];
                // console.log(icon);

                let defaultPointX_value = inputPointX.value;
                let defaultPointY_value = inputPointY.value;

                if (defaultPointX_value !== "" && defaultPointY_value !== "") {
                    deletePoint.disabled = false;
                }
            }
        }
        else {
            //座標変更クリックイベント
            //経緯度表示
            if (window.confirm("ポイント指定Ｘ： " + lng + "\nポイント指定Ｙ： " + lat +"\nに書き替えますか？")) {
                inputPointX.value = lng;
                inputPointY.value = lat;
                deletePoint.disabled = false;
                // 地図上にポイント表示
                // console.log(dispArticlePoint);
                dispArticlePoint.setLatLng([lat, lng]);
            };
        }

        // 季節を変更したとき
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
            });
        }
    });

    // 座標削除チェックイベント
    deletePoint.addEventListener("change", (e) => {
        if (e.target.checked) {
            if (window.confirm("座標を削除します")) {
                inputPointX.value = "";
                inputPointY.value = "";
                deletePoint.disabled = true;
            }
            map3.removeLayer(dispArticlePoint);
            deletePoint.checked = false;
        }
    });

    // アップロードボタンを押したときの処理
    function CheckUpload() {
        // alert("アップロードできるかな？");
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
            for (let i = 0; i < maxLineText; i++) {
                if (textLength > maxLengthText - 80 * (i + 1) && textLength <= maxLengthText - 80 * i && enterKeyCount > i) {
                // if (textLength > maxLengthText - 80 * (i + 1) && textLength <= maxLengthText - 80 * i && enterKeyCount > 11 - i) {
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

        if(window.confirm("アップロードしますか？")) {
            // return true;
        }
        else {
            alert("キャンセルされました");
            return false;
        }
        // alert("アップロードの処理が終わりました。");
    }
    </script>
</body>
</html>