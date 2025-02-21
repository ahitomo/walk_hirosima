<?php
// データベース関連の関数を読み込む
require_once 'common/db_function.php';
// html関連の関数を読み込む
require_once 'common/html_function.php';

// 記事情報を取得する関数を呼び出して変数に代入する
$article_list = $dbm->get_article_list();
// $array = $dbm->get_article_list();
// echo '<pre>';
// print_r($article_list);
// echo '</pre>';

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
    <title>歩こう広島｜記事管理</title>

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
        <h2>記事管理</h2>
        <div id="bulletin_board">
            <div id="bulletin_board_container">
                <button id="upload_link_button" type="button" onclick="location.href='upload.php'">記事を書く</button>
                <div id="backnumber_search_container">
                    <form id="backnumber_search" action="bulletin_board.php">
                        <p>バックナンバー検索</p>
                        <ul>
                            <li class="flex align_items_center">
                                <label class="list_title" for="date">期間</label>
                                <div class="flex align_items_center margin_0">
                                    <input type="date" name="date">
                                    <p>～</p>
                                    <input type="date" name="date">
                                </div>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">カテゴリ</p>
                                <div class="radio_container flex align_items_center margin_0">
                                    <label><input type="checkbox" id="event" name="category" value="event">イベント</label>
                                    <label><input type="checkbox" id="information" name="category" value="information">情報</label>
                                    <label><input type="checkbox" id="discovery" name="category" value="discovery">発見</label>
                                    <label><input type="checkbox" id="communication" name="category" value="communication">連絡</label>
                                </div>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">季節</p>
                                <div class="radio_container flex align_items_center margin_0">
                                    <label><input type="checkbox" id="spring" name="season" value="spring">春</label>
                                    <label><input type="checkbox" id="summer" name="season" value="summer">夏</label>
                                    <label><input type="checkbox" id="autumn" name="season" value="autumn">秋</label>
                                    <label><input type="checkbox" id="winter" name="season" value="winter">冬</label>
                                </div>
                            </li>
                            <li class="flex align_items_center">
                                <p class="list_title">未読／既読</p>
                                <div class="radio_container flex align_items_center margin_0">
                                    <label><input type="checkbox" id="not_yet" name="read" value="event">未読</label>
                                    <label><input type="checkbox" id="done" name="read" value="information">既読</label>
                                </div>
                            </li>
                        </ul>
                        <button type="submit">検索</button>
                    </form>
                    <div id="current_search_requirement">
                        <p>現在の検索条件</p>
                        <ul>
                            <li class="flex align_items_center">
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
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="pagenation">
                    <p>ページ指定</p>
                    <ul class="flex space_around">
                        <li><a href="" rel="prev">前へ</li>
                        <li>1</li>
                        <li><a href="/page/2/">2</a></li>
                        <li><a href="/page/3/">3</a></li>
                        <li><a href="" rel="next">次へ</a></li>
                    </ul>
                </div>
            </div>    

            <section class="bulletin_board_view my_article">
            <!-- <?php
                // 記事情報が存在した場合、情報表示の関数を呼び出す
                if (!is_null($article_list)) {
                    show_article_list($article_list);
                } else {
                    echo "記事がありません";
                }
                ?> -->

            
                <article class="each_view cursor_pointer">
                    <a href="edit.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20240513_上大須賀町_フェンスの上に.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
                <article class="each_view cursor_pointer">
                    <a href="edit.php">
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
                    <a href="edit.php">
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
                    <a href="edit.php">
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
                    <a href="edit.php">
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
                    <a href="edit.php">
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
                    <a href="edit.php">
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
                    <a href="edit.php">
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
                    <a href="edit.php">
                        <h3>○○神社　秋まつり</h3>
                        <div class="flex">
                            <p class="autumn font_size_14px">秋</p>
                            <p class="event font_size_14px">イベント</p>
                            <p class="font_size_14px">投稿日：2024年10月13日</p>
                        </div>
                        <img src="写真/20230226_松原町_橋の手すりにはマカロンがついている.JPG" alt="神社の写真">
                        <p>今年もおまつりの季節がやってきました。おみこしに屋台、ダンスやバンド演奏などお楽しみが盛りだくさん！みんなでわいわい楽しみましょう！</p>
                    </a>
                </article>
            </section>
        </div>
    </main>
</body>
</html>