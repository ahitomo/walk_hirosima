<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';

// 検索条件を取得する
// 期間
$date_start = date("0000-00-00");
$date_end = date("Y-m-d");
if (isset($_POST["date_start"]) AND $_POST["date_start"] != "") {
    $date_start = $_POST["date_start"];
}
if (isset($_POST["date_end"]) AND $_POST["date_end"] != "") {
    $date_end = $_POST["date_end"];
}

// カテゴリ
$categories = "";
$category_name_array = [];
if (isset($_POST["category"]) AND is_array($_POST["category"])) {
    $category_id_array = $_POST["category"];
    $category_id_string = implode(',', $category_id_array);
    $category_name_array = $dbm->get_category_requirement($category_id_string);
    $categories = implode(',', $category_name_array);
}

// 季節
$seasons = "";
$season_name_array = [];
if (isset($_POST["season"]) AND is_array($_POST["season"])) {
    $season_id_array = $_POST["season"];
    $season_id_string = implode(',', $season_id_array);
    $season_name_array = $dbm->get_season_requirement($season_id_string);
    $seasons = implode(',', $season_name_array);
}

// 記事情報を取得する関数を呼び出して変数に代入する
$article_list = $dbm->get_article_list();

// ページの情報を取得する
$max = 6;
$max_page = 1;
$now = 1;
if (!is_null($article_list)) {
    $article_num = count($article_list);
    $max_page = ceil($article_num / $max);

    if (!isset($_GET['page_id'])) {
        $now = 1;
    } else {
        $now = $_GET['page_id'];
    }
    $start_no = ($now - 1) * $max;
    $disp_data = array_slice($article_list, $start_no, $max, true);
    
    // echo "<br><br><br>";
    // print_r($disp_data);
    
    $disp_data_length = count($disp_data);
    $disp_season_name_array = [];
    for ($i = 0; $i < $disp_data_length; $i++) {
        // echo $disp_data[$i]["season_name"];
        $disp_season_name_array[] = $disp_data[$i + $start_no]["season_name"];
    }
    
}


$json_disp_season_name_array = json_encode($disp_season_name_array);
// echo "<br><br><br>";
// print_r($json_disp_season_name_array);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1401px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1400px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:428px)" href="css/style_mobile.css">
    <script src="js/script.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="GIMP/歩く鳥.png">
    <title>歩こう広島｜記事管理</title>
</head>
<body class="position_relative background_color">
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
    <main class="position_relative">
        <h2 class="headline position_fixed background_color">記事管理</h2>
        <div id="bulletin_board" class="position_relative">
            <div class="position_fixed">
            <button id="upload_link_button" type="button" onclick="location.href='upload.php'">記事を書く</button>
                <div id="backnumber_search_container">
                    <form id="backnumber_search" method="POST" action="article_management.php">
                        <p>バックナンバー検索</p>
                        <ul>
                            <li class="flex align_items_center">
                                <label class="list_title" for="date">期間</label>
                                <div class="flex align_items_center margin_0">
                                    <input type="date" name="date_start">
                                    <p>～</p>
                                    <input type="date" name="date_end">
                                </div>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">カテゴリ</p>
                                <div class="radio_container flex align_items_center margin_0">
                                    <label><input type="checkbox" id="event" name="category[]" value="1">イベント</label>
                                    <label><input type="checkbox" id="information" name="category[]" value="2">情報</label>
                                    <label><input type="checkbox" id="discovery" name="category[]" value="3">発見</label>
                                    <label><input type="checkbox" id="communication" name="category[]" value="4">連絡</label>
                                </div>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">季節</p>
                                <div class="radio_container flex align_items_center margin_0">
                                    <label><input type="checkbox" id="spring" name="season[]" value="1">春</label>
                                    <label><input type="checkbox" id="summer" name="season[]" value="2">夏</label>
                                    <label><input type="checkbox" id="autumn" name="season[]" value="3">秋</label>
                                    <label><input type="checkbox" id="winter" name="season[]" value="4">冬</label>
                                </div>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">未読／既読</p>
                                <div class="radio_container flex align_items_center margin_0">
                                    <label><input type="checkbox" id="not_read" name="read_satus[]" value="not_read">未読</label>
                                    <label><input type="checkbox" id="read" name="read_satus[]" value="read">既読</label>
                                </div>
                            </li>
                        </ul>
                        <button type="submit">検索</button>
                    </form>
                    <div class="current_search_requirement background_color_blue">
                        <p>現在の検索条件</p>
                        <ul>
                            <?php
                                show_current_search_requirement_bb($date_start, $date_end, $categories, $seasons);
                            ?>

                            <!-- <li class="flex align_items_center">
                                <p class="list_title">期間</p>
                                <p>XXXX/XX/XX</p>
                                <p>～</p>
                                <p>XXXX/XX/XX</p>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">カテゴリ</p>
                                <p>イベント</p>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">季節</p>
                                <p>春</p>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">未読／既読</p>
                                <p>未読</p>
                            </li> -->
                        </ul>
                    </div>
                    <div id="pagenation">
                        <p>ページ指定</p>
                        <ul class="flex space_around">
                            <?php
                            // ページ数を表示
                                show_pagenation($max_page, $now);
                            ?>
                            <!-- <li><a href="" rel="prev">前へ</li>
                            <li>1</li>
                            <li><a href="/page/2/">2</a></li>
                            <li><a href="/page/3/">3</a></li>
                            <li><a href="" rel="next">次へ</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>

            <section class="bulletin_board_view">
                <?php
                // 記事情報が存在した場合、情報表示の関数を呼び出す
                if (isset($disp_data)) {
                    show_article_list_am($disp_data, $start_no, $max);
                } else {
                    echo "記事がありません";
                }
                ?>

                <!-- <article class="each_view cursor_pointer">
                    <a href="article.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230930_大須賀町_駐車場に吹き溜まる落ち葉.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
                <article class="each_view cursor_pointer">
                    <a href="article.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230723_大須賀町_道端の畳.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
                <article class="each_view cursor_pointer">
                    <a href="article.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230721_大須賀町_油の流れる川.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
                <article class="each_view cursor_pointer">
                    <a href="article.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230721_大須賀町_地の裂け目.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
                <article class="each_view cursor_pointer">
                    <a href="article.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230717_松原町_黒い宝石みたいな鳥の糞.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
                <article class="each_view cursor_pointer">
                    <a href="article.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230611_松原町_打ち上げられた魚_2.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
                <article class="each_view cursor_pointer">
                    <a href="article.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230611_松原町_ひっかかったゴミ.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
                <article class="each_view cursor_pointer">
                    <a href="article.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230226_松原町_橋の手すりにはマカロンがついている.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article> -->
            </section>  
        </div>
    </main>

    <script>
        let seasonNameArray = document.getElementsByClassName("season_name");
        console.log(seasonNameArray);
        let dispSeasonNameArray = <?php echo $json_disp_season_name_array; ?>;
        console.log(dispSeasonNameArray);
        for (let i = 0; i < <?php echo $disp_data_length; ?>; i++) {
                // console.log(dispSeasonNameArray[i]);
                let dispSeasonName = String(dispSeasonNameArray[i]);
                console.log(dispSeasonName);
                switch (dispSeasonName) {
                    case "春":
                        seasonNameArray[i].classList.add("spring");
                        break;
                    case "夏":
                        seasonNameArray[i].classList.add("summer");
                        break;
                    case "秋":
                        seasonNameArray[i].classList.add("autumn");
                        break;
                    case "冬":
                        seasonNameArray[i].classList.add("winter");
                        break;
                }
        }

    </script>
</body>
</html>