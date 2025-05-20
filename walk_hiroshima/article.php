<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';

$article_id = $_GET["id"];
// echo "<br><br><br>";
// echo $article_id;

$article_image = $dbm->get_article_image($article_id);
$article_info = $dbm->get_article_info($article_id);
// echo "<br><br><br>";
// print_r($article_info);

$user_name = $article_info['user_name'];
// $category_id = $article_info['category_id'];
// echo $category_id;
$category_name = $article_info['category_name'];
$season_id = $article_info['season_id'];
// echo $season_id;
$season_name = $article_info['season_name'];
$title = $article_info['title'];
$text = $article_info['text'];
$point_x = $article_info['point_x'];
// $point_x = $json_encode($point_x);
$point_y = $article_info['point_y'];
// $point_y = $json_encode($point_y);
$registration_date = $article_info['registration_date'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1031px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1030px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:600px)" href="css/style_mobile.css">
    <link rel="icon" type="image/png" sizes="32x32" href="image/walking_bird.png">
    <title>歩こう広島｜記事詳細</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="js/script.js"></script>
</head>
<body id="article">
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
    <main class="margin_bottom_3vw">
        <h2>記事詳細</h2>
        <article class="each_article align_items_center">
            <section class="each_article_container">
                <div class="each_article_left">
                    <?php
                        show_article_top($title, $season_name, $category_name, $registration_date, $user_name);
                        // 記事画像が存在した場合、画像表示の関数を呼び出す
                        if (!is_null($article_image)) {
                            show_article_image($article_image);
                        } else {
                            echo "記事がありません";
                        }
                    ?>
                </div>
                <div id="each_article_right">
                    <?php
                        show_article_text($text);
                    ?>
                    <div class="each_article_map_container">
                        <div id="map2"></div>
                    </div>

                    <div id="no_map" class="align_items_center display_none">
                        <div class="flex space_between aling_items_center">
                            <img id="no_map_flower_1" src="image/cherry_blossom.png" alt="桜">
                            <img id="no_map_flower_2" src="image/hydrangea.png" alt="紫陽花">
                            <img id="no_map_flower_3" src="image/osmanthus.png" alt="金木犀">
                            <img id="no_map_flower_4" src="image/camellia.png" alt="椿">
                        </div>
                    </div>

                    <div>
                        <button type="button" onclick="history.back()" class="cursor_pointer">戻る</button>
                    </div>
                </div>
            </section>
        </article>
    </main>

    <script>
        // サムネイル画像のクリックイベント
        let articleImageMain = document.getElementById('article_image_main');
        // console.log(articleImageMain);
        let sumbnailArray = document.getElementsByClassName('article_image_sumbnail');
        // console.log(sumbnailArray);
        // console.log(sumbnailArray.length);
        for (let i = 0; i < sumbnailArray.length; i++) {
            // console.log(sumbnailArray[i]);
            sumbnailArray[i].addEventListener('click', (e) => {
                // console.log(i + "がクリックされました");
                if (e.target.alt !== "NO IMAGE") {
                    articleImageMain.src = e.target.src;
                    articleImageMain.alt = e.target.alt;
                }
            })
        }

        // マップの表示
        
        let season = document.getElementsByClassName("season");
        // console.log(season[0]);
        let seasonName = "<?php echo $season_name; ?>";
        // console.log(seasonName);
            switch (seasonName) {
                case "春":
                    season[0].classList.add("spring");
                    break;
                case "夏":
                    season[0].classList.add("summer");
                    break;
                case "秋":
                    season[0].classList.add("autumn");
                    break;
                case "冬":
                    season[0].classList.add("winter");
                    break;
            }

        // 地図の中心座標とズームレベルを指定
        var point_x = "<?php echo $point_x; ?>";
        // console.log(point_x);
        var point_y = "<?php echo $point_y; ?>";
        // console.log(point_y);
        let mapContainer = document.getElementsByClassName("each_article_map_container")[0];
        // console.log(mapContainer);
        let noMap = document.getElementById("no_map");
        // console.log(noMap);

        if (point_x !== "" && point_y !== "") {
            var map2 = L.map('map2').setView([point_y, point_x], 16);
            // console.log("a");
        }
        else {
            mapContainer.classList.add("display_none");
            noMap.classList.remove("display_none");
            noMap.classList.add("flex");

            let eachArticleMapContainer = document.getElementsByClassName("each_article_map_container")[0];
            // console.log(eachArticleMapContainer);

            if (window.matchMedia("(max-width:1030px)").matches) {
                eachArticleMapContainer.classList.add("display_none");
                // noMap.classList.remove("flex");
                // noMap.classList.add("display_none");
            }

        }

        // OpenStreetMapのタイルを追加
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map2);

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

        var season_id = <?php echo $season_id; ?>;
        var selectedIcon = "";
        switch (season_id) {
            case 1 :
                selectedIcon = SpringIcon;
            break;

            case 2 :
                selectedIcon = SummerIcon;
            break;

            case 3 :
                selectedIcon = AutumnIcon;
            break;

            case 4 :
                selectedIcon = WinterIcon;
            break;
        }
        // console.log(selectedIcon);
        // マーカーを追加
        var ToushouguMarker = L.marker([point_y, point_x], { icon: selectedIcon }).addTo(map2);
    </script>
</body>
</html>