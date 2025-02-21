<?php

    function show_current_search_requirement_bb($date_start, $date_end, $categories, $seasons) {
        echo <<<CURRENT_SEARCH_REQUIREMENT
        <li class="flex align_items_center">
            <p class="list_title">期間</p>
            <p>{$date_start}</p>
            <p>～</p>
            <p>{$date_end}</p>
        </li>
        <li class="flex align_items_center">
            <p class="list_title">カテゴリ</p>
            <p>{$categories}</p>
        </li>
        <li class="flex align_items_center">
            <p class="list_title">季節</p>
            <p>{$seasons}</p>
        </li>
        <li class="flex align_items_center">
            <p class="list_title">未読／既読</p>
            <p>未読</p>
        </li>
        CURRENT_SEARCH_REQUIREMENT;
    }

    function show_pagenation($max_page, $now) {

        if ($now > 1) {
            echo '<li><a href="article_management.php?page_id=' . ($now - 1) . '">前へ</a></li>';
        }

        for ($i = 1; $i <= $max_page; $i++) {
            if ($i === $now) {
                echo '<li>' . $now . '</li>';
            } else {
                echo '<li><a href="article_management.php?page_id=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($now < $max_page) {
            echo '<li><a href="article_management.php?page_id=' . ($now + 1) . '">次へ</a></li>';
        }

        // echo <<<PAGENATION
        // <li><a href="" rel="prev">前へ</li>
        // <li>1</li>
        // <li><a href="/page/2/">2</a></li>
        // <li><a href="/page/3/">3</a></li>
        // <li><a href="" rel="next">次へ</a></li>
        // PAGENATION;
    }

    function show_article_list($disp_data, $start_no, $max) {
        for ($i = 0; $i < $max; $i++) {

            if (isset($disp_data[$i + $start_no])) {
            
            // 上部分の表示
            echo <<<ARTICLE_FRAME
                <article class="each_view cursor_pointer">
            ARTICLE_FRAME;

            $date[$i + $start_no] = new DateTime($disp_data[$i + $start_no]["registration_date"]);

            // データを表示
            echo <<<ARTICLE_TOP
                <a href="article.php?id={$disp_data[$i + $start_no]["id"]}">
                    <h3>{$disp_data[$i + $start_no]["title"]}</h3>
                    <div class="flex">
                        <p class="season_name font_size_14px">{$disp_data[$i + $start_no]["season_name"]}</p>
                        <p class="category font_size_14px">{$disp_data[$i + $start_no]["category_name"]}</p>
                        <p class="font_size_14px">投稿日：{$date[$i + $start_no]->format('Y年m月d日')}</p>
                    </div>
            ARTICLE_TOP;

            if ($disp_data[$i + $start_no]['image_url']) {
                echo <<<ARTICLE_IMAGE
                    <img class="each_view_image" src="{$disp_data[$i + $start_no]["image_url"]}" alt="{$disp_data[$i + $start_no]["title"]}">
                ARTICLE_IMAGE;
            } else {
                echo '<img class="each_view_image" src="image/no_image.png" alt="NO IMAGE">';
            }

            $article_view_text = mb_substr($disp_data[$i + $start_no]["text"], 0, 100);
            // echo "<br><br><br>";
            // echo strlen($article_view_text);
            if (strlen($article_view_text) > 100) {
                $article_view_text .= "…";
            }

            // 下部分の表示
            echo <<<ARTICLE_BOTTOM
                    <br>
                    <p>{$article_view_text}</p>
                </a>
                </article>
            ARTICLE_BOTTOM;
            }
            // foreach ($article_list as $loop) {

            //     $date = new DateTime($loop["registration_date"]);

            //     // データを表示
            //     echo <<<END
            //         <a href="article.php">
            //             <h3>{$loop["title"]}</h3>
            //             <div class="flex">
            //                 <p class="autumn font_size_14px">{$loop["season_name"]}</p>
            //                 <p class="event font_size_14px">{$loop["category_name"]}</p>
            //                 <p class="font_size_14px">投稿日：{$date->format('Y年m月d日')}</p>
            //             </div>
            //             <img src="{$loop["image_url"]}" alt="{$loop["title"]}">
            //             <p>{$loop["text"]}</p>
            //         </a>
            //     END;

            //     // 下部分の表示
            //     echo <<< ARTICLE_BOTTOM
            //         </article>
            //     ARTICLE_BOTTOM;
            // }
        }
    }
    

    function show_article_top($title, $season_name, $category_name, $registration_date, $user_name) {
        // データを表示
        echo <<<ARTICLE_TOP
        <h3>{$title}</h3>
        <div class="flex flex_direction_column">
            <div class="flex margin_0">
                <p class="season font_size_14px">{$season_name}</p>
                <p class="category font_size_14px">{$category_name}</p>
                <p class="font_size_14px">投稿日時：{$registration_date}</p>
            </div>
            <div class="flex margin_0">
                <p class="font_size_14px">投稿者：{$user_name}</p>
            </div>
        </div>
        ARTICLE_TOP;
    }

    function show_article_image($article_image) {
        // データを表示
        echo <<<IMAGE_TOP
        <div class="each_detail_visual">
            <div class="each_detail_visual_main">
        IMAGE_TOP;
        
        echo <<<IMAGE
                <img src="{$article_image[0]['image_url']}" alt="{$article_image[0]['title']}" class="cursor_pointer">
            </div>
            <div class="each_detail_sumbnail_container">
                <div class="each_detail_sumbnail">
                    <img src="{$article_image[0]['image_url']}" alt="{$article_image[0]['title']}" class="cursor_pointer">
                </div>
                <div class="each_detail_sumbnail">
                    <img src="{$article_image[1]['image_url']}" alt="{$article_image[1]['title']}" class="cursor_pointer">
                </div>
                <div class="each_detail_sumbnail">
                    <img src="{$article_image[2]['image_url']}" alt="{$article_image[2]['title']}" class="cursor_pointer">
                </div>
            </div>
        </div>
        IMAGE;
    }

    function show_article_text($text) {
        // echo <<<TEXT
        // <p>{$text}</p>
        // TEXT;

        $text = nl2br($text);
        echo "<p>{$text}</p>";
    }

    function show_current_search_requirement_im($date_start, $date_end, $categories, $seasons) {
        echo <<<CURRENT_SEARCH_REQUIREMENT
        <li class="flex wrap align_items_center">
            <p class="list_title">期間</p>
            <div class="flex wrap">
                <p>{$date_start}</p>
                <p>～</p>
                <p>{$date_end}</p>
            </div>
        </li>
        <li class="flex align_items_center">
            <p class="list_title">カテゴリ</p>
            <p>{$categories}</p>
        </li>
        <li class="flex align_items_center">
            <p class="list_title">季節</p>
            <p>{$seasons}</p>
        </li>
        <li class="flex align_items_center">
            <p class="list_title">未読／既読</p>
            <p>未読</p>
        </li>
        CURRENT_SEARCH_REQUIREMENT;
    }

    function show_article_list_am($disp_data, $start_no, $max) {
        for ($i = 0; $i < $max; $i++) {

            if (isset($disp_data[$i + $start_no])) {

                // 上部分の表示
                echo <<<ARTICLE_FRAME
                    <article class="each_view cursor_pointer">
                ARTICLE_FRAME;

                $article_view_title = mb_substr($disp_data[$i + $start_no]["title"], 0, 14);
                $date = new DateTime($disp_data[$i + $start_no]["registration_date"]);

                // データを表示
                echo <<<ARTICLE_TOP
                    <a href="article.php?id={$disp_data[$i + $start_no]["id"]}">
                        <div class="flex space_between">
                            <h3>{$article_view_title}
                ARTICLE_TOP;

                // echo "<br><br><br>";
                // echo mb_strlen($article_view_title);
                // echo mb_strlen($disp_data[$i + $start_no]["title"]);
                
                if (mb_strlen($article_view_title) < mb_strlen($disp_data[$i + $start_no]["title"])) {
                    echo "…";
                }

                echo <<<ARTICLE_TOP
                            </h3>
                            <div class="margin_0 flex justify_content_flex_start">
                                <object><a href="edit.php?id={$disp_data[$i + $start_no]["id"]}"><img class="edit_icon" src="./image/edit.svg" alt="編集"></a></object>
                                <object><a href="article_management.php?id={$disp_data[$i + $start_no]["id"]}"><img class="delete_icon margin_left_10px" src="./image/delete.svg" alt="削除"></a></object>
                            </div>
                        </div>
                        <div class="flex">
                            <p class="season_name font_size_14px">{$disp_data[$i + $start_no]["season_name"]}</p>
                            <p class="category font_size_14px">{$disp_data[$i + $start_no]["category_name"]}</p>
                            <p class="font_size_14px">投稿日：{$date->format('Y年m月d日')}</p>
                        </div>
                ARTICLE_TOP;

                // トップ画像の表示
                if ($disp_data[$i + $start_no]['image_url']) {
                    echo <<<ARTICLE_IMAGE
                    <img class="each_view_image" src="{$disp_data[$i + $start_no]['image_url']}" alt="{$disp_data[$i + $start_no]["title"]}">
                    ARTICLE_IMAGE;
                } else {
                    echo '<img class="each_view_image" src="image/no_image.png" alt="NO IMAGE">';
                }

                $article_view_text = mb_substr($disp_data[$i + $start_no]["text"], 0, 100);

                // 下部分の表示
                echo <<<ARTICLE_MIDDLE
                        <br>
                        <p>{$article_view_text}
                ARTICLE_MIDDLE;

                // echo "<br><br><br>";
                // echo mb_strlen($article_view_text);
                // echo mb_strlen($disp_data[$i + $start_no]["text"]);
                
                if (mb_strlen($article_view_text) < mb_strlen($disp_data[$i + $start_no]["text"])) {
                        echo "…";
                }

                echo <<<ARTICLE_BOTTOM
                        </p>
                    </a>
                    </article>
                ARTICLE_BOTTOM;

            // foreach ($article_list as $loop) {

            //     $date = new DateTime($loop["registration_date"]);

            //     // データを表示
            //     echo <<<END
            //         <a href="article.php">
            //             <h3>{$loop["title"]}</h3>
            //             <div class="flex">
            //                 <p class="autumn font_size_14px">{$loop["season_name"]}</p>
            //                 <p class="event font_size_14px">{$loop["category_name"]}</p>
            //                 <p class="font_size_14px">投稿日：{$date->format('Y年m月d日')}</p>
            //             </div>
            //             <img src="{$loop["image_url"]}" alt="{$loop["title"]}">
            //             <p>{$loop["text"]}</p>
            //         </a>
            //     END;

            //     // 下部分の表示
            //     echo <<< ARTICLE_BOTTOM
            //         </article>
            //     ARTICLE_BOTTOM;
            // }

            }
        }
    }

    function show_edit($date, $title, $text, $point_x, $point_y, $article_image) {
        echo <<<EDIT
        <ul>
            <li class="flex">
                <p class="list_title width_120px" for="date">投稿日</p>
                <input class="margin_0" type="date" name="registration_date" value="{$date->format('Y-m-d')}" readonly>
            </li>
            <li class="flex">
                <p class="list_title width_120px">カテゴリ</p>
                <div class="flex radio_container margin_0 gap_1vw">
                    <label><input type="radio" id="event" name="category" value="1">イベント</label>
                    <label><input type="radio" id="information" name="category" value="2">情報</label>
                    <label><input type="radio" id="discovery" name="category" value="3">発見</label>
                    <label><input type="radio" id="communication" name="category" value="4">連絡</label>
                </div>
            </li>
            <li class="flex">
                <p class="list_title width_120px">季節</p>
                <div class="flex radio_container margin_0 gap_1vw">
                    <label><input type="radio" id="spring" name="season" value="1">春</label>
                    <label><input type="radio" id="summer" name="season" value="2">夏</label>
                    <label><input type="radio" id="autumn" name="season" value="3">秋</label>
                    <label><input type="radio" id="winter" name="season" value="4">冬</label>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="title">タイトル</label>
                <input class="margin_0" type="text" id="title" name="title" style="width: 400px;" value="{$title}" required>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="text">本文</label>
                <textarea class="margin_0" id="text" name="text" style="width: 400px; height: 13em;" required>{$text}</textarea>
            </li>

            <li class="flex">
                <label class="list_title width_120px" for="image_1">画像１</label>
                <div class="image_container">
                    <img class="edit_image" src="{$article_image[0]['image_url']}" alt="{$article_image[0]['title']}">
                </div>
                <div class="flex flex_column">
                    <input class="margin_0" type="file" id="image_1" name="image_1" accept="image/*">
                    <div class="flex space_between width_160px margin_0">
                        <label class="font_size_14px margin_0 display_block" for="display_order_1">表示順
                            <select class="margin_0" name="display_order_1">
                            </select>
                        </label>
                        <label class="font_size_14px flex align_items_center" for="delete_image_1">削除
                            <input type="checkbox" id="delete_image_1" name="delete_image_1" class="margin_left_4px">
                        </label>
                    </div>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="image_2">画像２</label>
                <div class="image_container">
                    <img class="edit_image" src="{$article_image[1]['image_url']}" alt="{$article_image[1]['title']}">
                </div>
                <div class="flex flex_column">
                    <input class="margin_0" type="file" id="image_2" name="image_2" accept="image/*">
                    <div class="flex space_between width_160px margin_0">
                        <label class="font_size_14px margin_0" for="display_order_2">表示順
                            <select class="margin_0" name="display_order_2">
                            </select>
                        </label>
                        <label class="font_size_14px flex align_items_center" for="delete_image_2">削除
                            <input type="checkbox" id="delete_image_2" name="delete_image_2" class="margin_left_4px">
                        </label>
                    </div>

                </div>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="image_3">画像３</label>
                <div class="image_container">
                    <img class="edit_image" src="{$article_image[2]['image_url']}" alt="{$article_image[2]['title']}">
                </div>
                <div class="flex flex_column">
                    <input class="margin_0" type="file" id="image_3" name="image_3" accept="image/*">
                    <div class="flex space_between width_160px margin_0">
                        <label class="font_size_14px margin_0" for="display_order_3">表示順
                            <select class="margin_0" name="display_order_3">
                            </select>
                        </label>
                        <label class="font_size_14px flex align_items_center" for="delete_image_3">削除
                            <input type="checkbox" id="delete_image_3" name="delete_image_3" class="margin_left_4px">
                        </label>
                    </div>
                </div>
            </li>
            <li class="flex" for="lng">
                <label class="list_title width_120px">ポイント指定Ｘ</label>
                <input class="margin_0" type="text" id="lng" name="point_x" value="{$point_x}" readonly>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="lat">ポイント指定Ｙ</label>
                <input class="margin_0" type="text" id="lat" name="point_y" value="{$point_y}" readonly>
            </li>
        </ul>
        EDIT;
    }

    function show_upload($date, $new_article_id) {
        // $image_length_start = ($new_article_id - 1) * 3;
        echo <<<UPLOAD
        <ul>
            <li class="flex">
                <p class="list_title width_120px" for="date">投稿日</p>
                <input class="margin_0" type="date" name="registration_date" value="$date" readonly>
            </li>
            <li class="flex">
                <p class="list_title width_120px">カテゴリ</p>
                <div class="flex radio_container margin_0 gap_1vw">
                    <label><input type="radio" id="event" name="category" value="1" required>イベント</label>
                    <label><input type="radio" id="information" name="category" value="2">情報</label>
                    <label><input type="radio" id="discovery" name="category" value="3">発見</label>
                    <label><input type="radio" id="communication" name="category" value="4">連絡</label>
                </div>
            </li>
            <li class="flex">
                <p class="list_title width_120px">季節</p>
                <div class="flex radio_container margin_0 gap_1vw">
                    <label><input type="radio" id="spring" name="season" value="1" required>春</label>
                    <label><input type="radio" id="summer" name="season" value="2">夏</label>
                    <label><input type="radio" id="autumn" name="season" value="3">秋</label>
                    <label><input type="radio" id="winter" name="season" value="4">冬</label>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="title">タイトル</label>
                <input class="margin_0" type="text" id="title" name="title" style="width: 400px;" required>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="text">本文</label>
                <textarea class="margin_0" id="text" name="text" style="width: 400px; height: 13em;" required></textarea>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="image_1">画像１</label>
                <div class="image_container">
                    <img class="edit_image" src="image/no_image.png" alt="NO IMAGE">
                </div>
                <div class="flex flex_column">
                    <input class="margin_0" type="file" id="image_1" name="image_1" accept="image/*">
                    <label class="font_size_14px margin_0" for="display_order_1">表示順
                        <select class="margin_0" name="display_order_1"  disabled>
                        </select>
                    </label>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="image_2">画像２</label>
                <div class="image_container">
                    <img class="edit_image" src="image/no_image.png" alt="NO IMAGE">
                </div>
                <div class="flex flex_column">
                    <input class="margin_0" type="file" id="image_2" name="image_2" accept="image/*">
                    <label class="font_size_14px margin_0" for="display_order_2">表示順
                        <select class="margin_0" name="display_order_2"  disabled>
                        </select>
                    </label>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="image_3">画像３</label>
                <div class="image_container">
                    <img class="edit_image" src="image/no_image.png" alt="NO IMAGE">
                </div>
                <div class="flex flex_column">
                    <input class="margin_0" type="file" id="image_3" name="image_3" accept="image/*">
                    <label class="font_size_14px margin_0" for="display_order_3">表示順
                        <select class="margin_0" name="display_order_3"  disabled>
                        </select>
                    </label>
                </div>
            </li>
            <li class="flex" for="lng">
                <label class="list_title width_120px">ポイント指定Ｘ</label>
                <input class="margin_0" type="text" id="lng" name="point_x">
            </li>
            <li class="flex">
                <label class="list_title width_120px" for="lat">ポイント指定Ｙ</label>
                <input class="margin_0" type="text" id="lat" name="point_y">
            </li>
        </ul>
        UPLOAD;
    }

?>
