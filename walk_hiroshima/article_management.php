<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';
session_cache_limiter('private_no_expire');
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

// 削除ボタンから遷移した場合
if (isset($_GET["id"])) {
    $article_id = $_GET["id"];
    $dbm->delete_article($article_id);
}

// echo "<br><br><br><br>";
// print_r($_SESSION['category']);
// echo "<br>";
// print_r($_SESSION['season']);

// 検索条件を削除する
$url = $_SERVER['HTTP_REFERER'];
// echo "<br>";
// echo $url;
if (!strpos($url, "/article_management.php") AND !strpos($url, "/article.php") AND !strpos($url, "/edit.php")) {
    unset($_SESSION['date_start'], $_SESSION['date_end'], $_SESSION['categories'], $_SESSION['categry_id_string'], $_SESSION['seasons'], $_SESSION['season_id_string']);
}

if (isset($_POST['search'])) {
    unset($_SESSION['date_start'], $_SESSION['date_end'], $_SESSION['categories'], $_SESSION['categry_id_string'], $_SESSION['seasons'], $_SESSION['season_id_string']);
}

// echo "<br>";
// print_r($_SESSION['category']);
// echo "<br>";
// print_r($_SESSION['season']);

// 検索条件を取得する
// 期間
$date_start = date("0000-00-00");
$date_end = date("Y-m-d");

if (isset($_POST["date_start"]) AND $_POST["date_start"] !== "") {
    $date_start = $_POST["date_start"];
    $_SESSION['date_start'] = $date_start;
}
else if (isset($_SESSION["date_start"])) {
    $date_start = $_SESSION["date_start"];
}
if (isset($_POST["date_end"]) AND $_POST["date_end"] !== "") {
    $date_end = $_POST["date_end"];
    $_SESSION['date_end'] = $date_end;
}
else if (isset($_SESSION["date_end"])) {
    $date_end = $_SESSION["date_end"];
}

// カテゴリ
$category_name_array = [];
if (isset($_POST["category"])) {
    // echo "<br>";
    // print_r($_POST['category']);
    $category_id_array = $_POST["category"];
    $category_id_string = implode(',', $category_id_array);
    $category_name_array = $dbm->get_category_requirement($category_id_string);
    $categories = implode(',', $category_name_array);
    $_SESSION["categories"] = $categories;
    $_SESSION['categry_id_string'] = $category_id_string;
}
else if (isset($_SESSION["categories"])) {
    $categories = $_SESSION["categories"];
    $category_id_string = $_SESSION['categry_id_string'];
}
else {
    $categories = "";
}

// 季節
$season_name_array = [];
if (isset($_POST["season"])) {
    // echo "<br>";
    // print_r($_POST['season']);
    $season_id_array = $_POST["season"];
    $season_id_string = implode(',', $season_id_array);
    $season_name_array = $dbm->get_season_requirement($season_id_string);
    $seasons = implode(',', $season_name_array);
    $_SESSION["seasons"] = $seasons;
    $_SESSION['season_id_string'] = $season_id_string;
}
else if (isset($_SESSION["seasons"])) {
    $seasons = $_SESSION["seasons"];
    $season_id_string = $_SESSION['season_id_string'];
}
else {
    $seasons = "";
}

// 記事情報を取得する関数を呼び出して変数に代入する
$article_list = $dbm->get_article_list_am($user_id, $category_id_string, $season_id_string);
// echo "<br><br><br><br>";
// print_r($article_list);

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
    $json_disp_season_name_array = json_encode($disp_season_name_array);
    // echo "<br><br><br><br><br>";
    // print_r($json_disp_season_name_array);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen and (min-width:1430px)" href="css/style_pc.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:1430px)" href="css/style_tablet.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-width:685px)" href="css/style_mobile.css">
    <script src="js/script.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="image/walking_bird.png">
    <title>歩こう広島｜記事管理</title>
</head>
<body class="position_relative background_color overflow_x_hidden">
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
    <main class="position_relative">
        <h2 class="headline position_fixed background_color">記事管理</h2>
        <div id="bulletin_board" class="position_relative">
            <div class="position_fixed">
                <button id="upload_link_button" type="button" onclick="location.href='upload.php'">記事を書く</button>
                <div id="backnumber_search_container">
                    <div id="backnumber_search_box">
                        <form id="backnumber_search" method="POST" action="article_management.php">
                            <p id="backnumber_search_toggle">バックナンバー検索</p>
                            <div id="backnumber_search_display">
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
                                            <label><input type="checkbox" id="not_read" name="read_satus[]" value="not_read" disabled>未読</label>
                                            <label><input type="checkbox" id="read" name="read_satus[]" value="read" disabled>既読</label>
                                        </div>
                                    </li>
                                </ul>
                                <button type="submit" name="search">検索</button>
                            </div>
                        </form>
                        <div class="current_search_requirement background_color_blue">
                            <p id="current_search_requirement_toggle">現在の検索条件</p>
                            <ul id="current_search_requirement_display">
                                <?php
                                    show_current_search_requirement_bb($date_start, $date_end, $categories, $seasons);
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div id="pagenation">
                        <p>ページ指定</p>
                        <ul class="flex space_around wrap">
                            <?php
                            // ページ数を表示
                                show_pagenation($max_page, $now);
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bulletin_board_view">
                <?php
                // 記事情報が存在した場合、情報表示の関数を呼び出す
                if (isset($disp_data)) {
                    show_article_list_am($disp_data, $start_no, $max);
                } else {
                    echo "記事がありません";
                }
                ?>
            </div>  
        </div>
    </main>
    <footer class="flex position_relative">
        <a id="back_to_top" class="display_none" href="#">ページ上部へ戻る</a>
    </footer>

    <script>
        // 検索欄と絞り込み条件の表示／非表示
        let BacknumberSearchToggle = document.getElementById("backnumber_search_toggle");
        let CurrentSearchRequirementToggle = document.getElementById("current_search_requirement_toggle");
        let BacknumberSearchDisplay = document.getElementById("backnumber_search_display");
        let CurrentSearchRequirementDisplay = document.getElementById("current_search_requirement_display");

        if (window.matchMedia("(max-width:760px)").matches) {
            BacknumberSearchDisplay.classList.add("display_none");
            CurrentSearchRequirementDisplay.classList.add("display_none");

            BacknumberSearchToggle.addEventListener('click', (e) => {
                BacknumberSearchDisplay.classList.toggle("display_none");
            })
            CurrentSearchRequirementToggle.addEventListener('click', (e) => {
                CurrentSearchRequirementDisplay.classList.toggle("display_none");
            })
        }

        // 画面幅の調整
        const setScrollbarWidth = () => {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        // カスタムプロパティの値を更新する
        document.documentElement.style.setProperty('--scrollbar', `${scrollbarWidth}px`);
        };
        // 表示されたとき
        window.addEventListener('load', setScrollbarWidth);
        // リサイズしたとき
        window.addEventListener('resize', setScrollbarWidth)

        let seasonNameArray = document.getElementsByClassName("season_name");
        // console.log(seasonNameArray);
        let dispSeasonNameArray = <?php if ($json_disp_season_name_array) {echo $json_disp_season_name_array;} else {echo "''";}; ?>;
        // console.log(dispSeasonNameArray);
        if (dispSeasonNameArray !== "") {
            for (let i = 0; i < <?php if ($disp_data_length) {echo $disp_data_length;} else {echo 0;}; ?>; i++) {
                // console.log(dispSeasonNameArray[i]);
                let dispSeasonName = String(dispSeasonNameArray[i]);
                // console.log(dispSeasonName);
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
        }

        // 「ページ上部へ戻る」ボタンの表示
        let backToPageTop = document.getElementById("back_to_top");
        // console.log(backToPageTop);
        if (window.matchMedia("(max-width:1430px)").matches) {
            backToPageTop.classList.remove("display_none");
        }

        CheckDelete = function(delete_icon) {
            // console.log(delete_icon);
            let object = delete_icon.parentElement;
            // console.log(parent);
            let div = object.parentElement;
            // console.log(div);
            let title = div.previousElementSibling.innerHTML.trim();
            // console.log(title);
            if (confirm("記事「" + title + "」を削除します")) {
                    return true;
            } else {
                alert ("キャンセルされました");
                return false;
            }
        };
    </script>
</body>
</html>