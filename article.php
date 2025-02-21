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
    <link rel="icon" type="image/png" sizes="32x32" href="GIMP/歩く鳥.png">
    <title>歩こう広島｜掲示板</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="js/script.js"></script>
</head>
<body>
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
    <main>
        <h2>記事詳細</h2>
        <article class="each_article align_items_center">
            <section class="each_article_container">
                <div class="each_article_left">
                    <?php
                        show_article_top($title, $season_name, $category_name, $registration_date, $user_name);
                    ?>
                    
                        <!-- <h3>○○神社　秋まつり</h3>
                        <div class="flex flex_direction_column">
                            <div class="flex margin_0">
                                <p class="autumn font_size_14px">秋</p>
                                <p class="event font_size_14px">イベント</p>
                                <p class="font_size_14px">投稿日：2024年10月1日</p>
                            </div>
                            <div class="flex margin_0">
                                <p class="font_size_14px">投稿者：purin_daisuki</p>
                            </div>
                        </div>  -->
                        
                    <?php
                    // 記事画像が存在した場合、画像表示の関数を呼び出す
                    if (!is_null($article_image)) {
                        show_article_image($article_image);
                    } else {
                        echo "記事がありません";
                    }
                    ?>

                    <!-- <div class="each_detail_visual">
                        <div class="each_detail_visual_main">
                            <img src="写真/20221128_上大須賀町_生垣にクリスマスの飾りつけ.JPG" alt="神社の写真" class="cursor_pointer">
                        </div>
                        <div class="each_detail_sumbnail_container">
                            <div class="each_detail_sumbnail">
                                <img src="写真/20221128_上大須賀町_生垣にクリスマスの飾りつけ.JPG" alt="神社の写真" class="cursor_pointer">
                            </div>
                            <div class="each_detail_sumbnail">
                                <img src="写真/20230717_松原町_黒い宝石みたいな鳥の糞.JPG" alt="神社の写真" class="cursor_pointer">
                            </div>
                            <div class="each_detail_sumbnail">
                                <img src="写真/IMG_5983_鴨と川.jpg" alt="神社の写真" class="cursor_pointer">
                            </div>
                        </div>
                    </div> -->
                </div>
                <div id="each_article_right">
                    <?php
                    show_article_text($text);
                    ?>
                    <!-- <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！<br>
                        <br>
                        日時：2024年10月23日（日）<br>
                        場所：○○神社<br>
                        所在地：広島市東区○○×ー×ー×<br>
                        お問い合わせ先：□□□－□□□－□□□□<br>
                        担当：相原
                    </p> -->
                    <div class="each_article_map_container">
                        <div id="map2"></div>
                    </div>
                    <div>
                        <button type="button" onclick="history.back()" class="cursor_pointer">戻る</button>
                    </div>
                </div>
            </section>
        </article>
    </main>

    <script>

        let season = document.getElementsByClassName("season");
        console.log(season[0]);
        let seasonName = "<?php echo $season_name; ?>";
        console.log(seasonName);
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
        var point_x = <?php echo $point_x; ?>;
        // console.log(point_x);
        var point_y = <?php echo $point_y; ?>;
        // console.log(point_y);
        var map2 = L.map('map2').setView([point_y, point_x], 16);

        // OpenStreetMapのタイルを追加
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map2);

        // アイコンの設定
        var SpringIcon = L.icon({
            iconUrl: './GIMP/春.png',
            iconSize: [52, 48],
            iconAnchor: [12, 41],
            popupAnchor: [0, -41]
        });

        var SummerIcon = L.icon({
            iconUrl: './GIMP/夏.png',
            iconSize: [77, 55],
            iconAnchor: [12, 41],
            popupAnchor: [0, -41]
        });

        var AutumnIcon = L.icon({
            iconUrl: './GIMP/秋.png',
            iconSize: [55, 58],
            iconAnchor: [12, 41],
            popupAnchor: [0, -41]
        });

        var WinterIcon = L.icon({
            iconUrl: './GIMP/冬.png',
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