<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';
session_cache_limiter('private_no_expire');
session_start();
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
}

// 検索条件を削除する
$url = $_SERVER['HTTP_REFERER'];
// echo "<br>";
// echo $url;
if (!strpos($url, "/information_map.php") AND !strpos($url, "/article.php")) {
    unset($_SESSION['date_start'], $_SESSION['date_end'], $_SESSION['categories'], $_SESSION['seasons']);
}

if (isset($_POST['search'])) {
    unset($_SESSION['date_start'], $_SESSION['date_end'], $_SESSION['categories'], $_SESSION['seasons']);
}

// 検索条件を取得する
// 期間
$date_start = date("0000-00-00");
$date_end = date("Y-m-d");
if (isset($_POST["date_start"])) {
    if ($_POST["date_start"] != "") {
        $date_start = $_POST["date_start"];
    }
    else {
        $date_start = date("0000-00-00");        
    }
}
if (isset($_POST["date_end"])) {
    if ($_POST["date_end"] != "") {
        $date_end = $_POST["date_end"];
    }
    else {
        $date_end = date("Y-m-d");
    }
}

// カテゴリ
$categories = "";
$category_name_array = [];
if (isset($_POST["category"])) {
    $category_id_array = $_POST["category"];
    $category_id_string = implode(',', $category_id_array);
    $category_name_array = $dbm->get_category_requirement($category_id_string);
    $categories = implode(',', $category_name_array);
}

// 季節
$seasons = "";
$season_name_array = [];
if (isset($_POST["season"])) {
    $season_id_array = $_POST["season"];
    $season_id_string = implode(',', $season_id_array);
    $season_name_array = $dbm->get_season_requirement($season_id_string);
    $seasons = implode(',', $season_name_array);
}

// 記事情報を取得する関数を呼び出して変数に代入する
$article_list_im = $dbm->get_article_list_im();
// echo "<br><br><br><br>";
// print_r($article_list_im);

if (!is_null($article_list_im)) {
    $article_num = count($article_list_im);

    for ($i = 0; $i < count($article_list_im); $i++) {
        $disp_article_point_x_array[] = $article_list_im[$i]["point_x"];
        $disp_article_point_y_array[] = $article_list_im[$i]["point_y"];
        $disp_season_name_array[] = $article_list_im[$i]["season_name"];
        $disp_article_title_array[] = $article_list_im[$i]["title"];
        $disp_category_name_array[] = $article_list_im[$i]["category_name"];
        $disp_registration_date_array[] = $article_list_im[$i]["registration_date"];
        $disp_text_array[] = mb_substr($article_list_im[$i]["text"], 0, 150);
        if (strlen($article_list_im[$i]["text"]) > 150) {
            $disp_text_array[$i] .= "…";
        }
        $disp_image_url_array[] = $article_list_im[$i]["image_url"];
        $disp_id_array[] = $article_list_im[$i]["id"];
    }

    // echo "<br><br><br><br>";
    // print_r($disp_image_url_array);

    $json_article_list_im = json_encode($article_list_im);
    $json_disp_article_point_x_array = json_encode($disp_article_point_x_array);
    $json_disp_article_point_y_array = json_encode($disp_article_point_y_array);
    $json_disp_article_title_array = json_encode($disp_article_title_array);
    $json_disp_season_name_array = json_encode($disp_season_name_array);
    $json_disp_category_name_array = json_encode($disp_category_name_array);
    $json_disp_registration_date_array = json_encode($disp_registration_date_array);
    $json_disp_text_array = json_encode($disp_text_array);
    $json_disp_image_url_array = json_encode($disp_image_url_array);
    $json_disp_id_array = json_encode($disp_id_array);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1221px), screen and (orientaion: landscape)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1220px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:625px)" href="css/style_mobile.css">
    <link rel="icon" type="image/png" sizes="32x32" href="image/walking_bird.png">
    <title>歩こう広島｜情報マップ</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="js/script.js"></script>
</head>
<body id="information_map">
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
    <main>
        <h2>情報マップ</h2>
        <div id="map_container" class="align_items_center">
            <div id="map"></div>
            <div id="map_content">
                <div id="flex_container" class="margin_0">
                    <form id="map_search" method="POST" action="information_map.php">
                        <p id="map_search_toggle">絞り込み検索</p>
                        <div id="map_search_display">
                            <ul>
                                <li class="flex align_items_center">
                                    <p class="list_title" for="date">期間</p>
                                    <div class="flex wrap margin_0 width">
                                        <input class="margin_0" type="date" name="date_start">
                                        <p>～</p>
                                        <input class="margin_0" type="date" name="date_end">
                                    </div>
                                </li>
                                <li class="flex align_items_center">
                                    <p class="list_title">カテゴリ</p>
                                    <div class="radio_container flex wrap align_items_center margin_0 width">
                                        <label><input type="checkbox" id="event" name="category[]" value="1">イベント</label>
                                        <label><input type="checkbox" id="information" name="category[]" value="2">情報</label>
                                        <label><input type="checkbox" id="discovery" name="category[]" value="3">発見</label>
                                        <label><input type="checkbox" id="communication" name="category[]" value="4">連絡</label>
                                    </div>
                                </li>
                                <li class="flex align_items_center">
                                    <p class="list_title">季節</p>
                                    <div class="radio_container flex wrap align_items_center margin_0 width">
                                        <label><input type="checkbox" id="spring" name="season[]" value="1">春</label>
                                        <label><input type="checkbox" id="summer" name="season[]" value="2">夏</label>
                                        <label><input type="checkbox" id="autumn" name="season[]" value="3">秋</label>
                                        <label><input type="checkbox" id="winter" name="season[]" value="4">冬</label>
                                    </div>
                                </li>
                                <li class="flex align_items_center">
                                    <p class="list_title">未読／既読</p>
                                    <div class="radio_container flex align_items_center margin_0 width">
                                        <label><input type="checkbox" id="not_yet" name="read" value="event" disabled>未読</label>
                                        <label><input type="checkbox" id="done" name="read" value="information" disabled>既読</label>
                                    </div>
                                </li>
                            </ul>
                            <button type="submit" name="search">検索</button>
                        </div>
                    </form>
                    <div id="map_current_search_requirement">
                        <p id="map_current_search_requirement_toggle">現在の検索条件</p>
                        <ul id="map_current_search_requirement_display">
                            <?php
                            show_current_search_requirement_im($date_start, $date_end, $categories, $seasons);
                            ?>
                        </ul>
                    </div>
                </div>
                <div id="no_view" class="flex align_items_center">
                    <div class="flex space_between aling_items_center">
                        <img id="no_view_flower_1" src="image/cherry_blossom.png" alt="桜">
                        <img id="no_view_flower_2" src="image/hydrangea.png" alt="紫陽花">
                        <img id="no_view_flower_3" src="image/osmanthus.png" alt="金木犀">
                        <img id="no_view_flower_4" src="image/camellia.png" alt="椿">
                    </div>
                </div>

                <article class="each_view cursor_pointer display_none">
                    <a class="article_link">
                        <div class="preview">
                            <div id="preview_top">
                                <h3></h3>
                                <div id="preview_info" class="flex margin_bottom_8px">
                                    <p class="season font_size_14px"></p>
                                    <p class="category font_size_14px"></p>
                                    <p class="registration_date font_size_14px">投稿日：</p>
                                </div>
                            </div>
                            <div class="preview_bottom">
                                <div class="image_container">
                                    <img class="preview_left">
                                </div>
                                <p class="preview_text"></p>                    
                            </div>    
                        </div>
                    </a>
                </article>
            </div>
        </div>
    </main>

    <script>
        let UserName = "<?php if ($user_name) {echo $user_name;} else {echo '';}?>";
            // console.log(UserName);
            if (UserName == "") {
                let headerLink = document.querySelectorAll("li");
                // console.log(headerLink);
                headerLink[2].classList.add("display_none");
                headerLink[3].classList.add("display_none");
                headerLink[7].classList.add("display_none");
                headerLink[8].classList.add("display_none");
            }

        // 地図の中心座標とズームレベルを指定
        var map = L.map('map').setView([34.39803368302145, 132.47533146205527], 14);

        // OpenStreetMapのタイルを追加
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let articleListIm = <?php if ($json_article_list_im) {echo $json_article_list_im;} else {echo "''";}?>;
        if (articleListIm !== '') {
            // アイコンの設定
            var SpringIcon = L.icon({
                iconUrl: './image/spring.png',
                iconSize: [52, 48],
                iconAnchor: [12, 41],
                popupAnchor: [0, -41]
            });

            var SummerIcon = L.icon({
                iconUrl: './image/summer.png',
                iconSize: [77, 55],
                iconAnchor: [12, 41],
                popupAnchor: [0, -41]
            });

            var AutumnIcon = L.icon({
                iconUrl: './image/autumn.png',
                iconSize: [55, 58],
                iconAnchor: [12, 41],
                popupAnchor: [0, -41]
            });

            var WinterIcon = L.icon({
                iconUrl: './image/winter.png',
                iconSize: [50, 50],
                iconAnchor: [12, 41],
                popupAnchor: [0, -41]
            });

            // 各記事の座標とアイコンを取得
            let dispArticlePointxArray = <?php if ($json_disp_article_point_x_array) {echo $json_disp_article_point_x_array;} else {echo "''";} ?>;
            let dispArticlePointyArray = <?php if ($json_disp_article_point_y_array) {echo $json_disp_article_point_y_array;} else {echo "''";} ?>;
            let dispArticleTitleArray = <?php if ($json_disp_article_title_array) {echo $json_disp_article_title_array;} else {echo "''";} ?>;
            // console.log(dispArticlePointxArray);
            // console.log(dispArticlePointyArray);
            // console.log(dispArticleTitleArray);

            let dispSeasonNameArray = <?php if ($json_disp_season_name_array) {echo $json_disp_season_name_array;} else {echo "''";} ?>;
            // console.log(dispSeasonNameArray);

            let articleNum = <?php if ($article_num) {echo $article_num;} else {echo "0";} ?>;
            // console.log(articleNum);
            let dispArticlePointArray = [];

            for (let i = 0; i < articleNum; i++) {
                let dispArticlePointX = dispArticlePointxArray[i];
                let dispArticlePointY = dispArticlePointyArray[i];
                // console.log(dispArticlePointX);
                // console.log(dispArticlePointY);

                let dispSeasonName = dispSeasonNameArray[i];
                // console.log(dispSeasonName);

                let dispIcon = "";
                switch (dispSeasonName) {
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
                let dispArticlePoint = L.marker([dispArticlePointY, dispArticlePointX], { icon: dispIcon }).addTo(map);
                dispArticlePointArray[i] = dispArticlePoint;

                // ポップアップの追加
                let dispArticleTitle = dispArticleTitleArray[i];
                // console.log(dispArticleTitle);
                dispArticlePoint.bindPopup(dispArticleTitle);
                // console.log(dispArticlePoint);
            }

            let disp_category_name_array = <?php if ($json_disp_category_name_array) {echo $json_disp_category_name_array;} else {echo "''";} ?>;
            let disp_registration_date_array = <?php if ($json_disp_registration_date_array) {echo $json_disp_registration_date_array;} else {echo "''";} ?>;
            let disp_text_array = <?php if ($json_disp_text_array) {echo $json_disp_text_array;} else {echo "''";} ?>;
            let disp_image_url_array = <?php if ($json_disp_image_url_array) {echo $json_disp_image_url_array;} else {echo "''";} ?>;
            let disp_id_array = <?php if ($json_disp_id_array) {echo $json_disp_id_array;} else {echo "''";} ?>;

            // console.log(disp_category_name_array);
            // console.log(disp_registration_date_array);
            // console.log(disp_text_array);
            // console.log(disp_image_url_array);
            // console.log(disp_id_array);

            // console.log(dispArticlePointArray);
            for (let i = 0; i < dispArticlePointArray.length; i++) {
                let dispArticlePointSelected = dispArticlePointArray[i];
                // console.log(dispArticlePointSelected);

                dispArticlePointSelected.on('click', function (e) {
                // クリックされた時の処理
                // 記事の概要を表示
                    const noView = document.getElementById('no_view');
                    // console.log(noView);
                    noView.classList.remove("flex");
                    noView.classList.add("display_none");
                    const eachView = document.getElementsByClassName("each_view")[0];
                    eachView.classList.remove("display_none");
                    
                    const articleLink = document.getElementsByClassName("article_link")[0];
                    articleLink.href= "article.php?id=" + disp_id_array[i];

                    
                    const h3 = document.getElementsByTagName("h3")[0];
                    h3.innerHTML = dispArticleTitleArray[i];

                    const season = document.getElementsByClassName("season")[0];
                    season.innerHTML = dispSeasonNameArray[i];
                    season.classList.remove("spring");
                    season.classList.remove("summer");
                    season.classList.remove("autumn");
                    season.classList.remove("winter");

                    switch (season.innerHTML) {
                        case "春":
                            season.classList.add("spring");
                            break;
                        case "夏":
                            season.classList.add("summer");
                            break;
                        case "秋":
                            season.classList.add("autumn");
                            break;
                        case "冬":
                            season.classList.add("winter");
                            break;
                    }

                    const category = document.getElementsByClassName("category")[0];
                    category.innerHTML = disp_category_name_array[i];

                    const registrationDate = document.getElementsByClassName('registration_date')[0];
                    const date = new Date(disp_registration_date_array[i]);
                    // console.log(date);
                    const year = date.getFullYear().toString().padStart(4, '0');
                    const month = (date.getMonth() + 1).toString().padStart(2, '0');
                    const day = date.getDate().toString().padStart(2, '0');

                    const dateText = year + '年' + month + '月' + day + '日';
                    // console.log(dateText);

                    registrationDate.innerHTML = "投稿日：" + dateText;

                    const image = document.getElementsByClassName("preview_left")[0];
                    if (!disp_image_url_array[i]) {
                        image.src = 'image/no_image.png';
                        image.alt = 'NO IMAGE';
                    } else {
                        image.src = disp_image_url_array[i];
                        image.alt = dispArticleTitleArray[i];
                    }

                    const text = document.getElementsByClassName("preview_text")[0];
                    // console.log(text);
                    text.innerHTML = disp_text_array[i];
                });
            }
        }

        // 検索欄と絞り込み条件の表示／非表示
        let mapSearchToggle = document.getElementById("map_search_toggle");
        let mapCurrentSearchRequirementToggle = document.getElementById("map_current_search_requirement_toggle");
        let mapSearchDisplay = document.getElementById("map_search_display");
        let mapCurrentSearchRequirementDisplay = document.getElementById("map_current_search_requirement_display");
        let previewTop = document.getElementById("preview_top");
        let noView = document.getElementById("no_view");
        // console.log(previewTop);
        // console.log(mapSearchDisplay);
        // console.log(mapCurrentSearchRequirementDisplay);

        if (window.matchMedia("(max-width:1220px)").matches) {
            noView.classList.remove("flex");
            noView.classList.add("display_none");

            mapSearchDisplay.classList.add("display_none");
            mapCurrentSearchRequirementDisplay.classList.add("display_none");

            mapSearchToggle.addEventListener('click', (e) => {
                mapSearchDisplay.classList.toggle("display_none");
                mapCurrentSearchRequirementDisplay.classList.toggle("display_none");
            })
            mapCurrentSearchRequirementToggle.addEventListener('click', (e) => {
                mapSearchDisplay.classList.toggle("display_none");
                mapCurrentSearchRequirementDisplay.classList.toggle("display_none");
            })

            previewTop.classList.add("flex");
        }

        if (window.matchMedia("(max-width:625px)").matches) {
            mapSearchDisplay.classList.add("display_none");
            mapCurrentSearchRequirementDisplay.classList.add("display_none");

            mapSearchToggle.addEventListener('click', (e) => {
                mapCurrentSearchRequirementDisplay.classList.toggle("display_none");
            })
            mapCurrentSearchRequirementToggle.addEventListener('click', (e) => {
                mapSearchDisplay.classList.toggle("display_none");
            })

            previewTop.classList.remove("flex");
        }
    </script>
</body>
</html>