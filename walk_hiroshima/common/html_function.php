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
            <p></p>
        </li>
        CURRENT_SEARCH_REQUIREMENT;
    }

    function show_pagenation_bb($max_page, $now) {

        if ($now > 1) {
            echo '<li><a id="previous_page" href="bulletin_board.php?page_id=' . ($now - 1) . '">前へ</a></li>';
        }

        for ($i = 1; $i <= $max_page; $i++) {
            if ($i === $now) {
                echo '<li>' . $now . '</li>';
            } else {
                echo '<li><a href="bulletin_board.php?page_id=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($now < $max_page) {
            echo '<li><a id="next_page" href="bulletin_board.php?page_id=' . ($now + 1) . '">次へ</a></li>';
        }

    }

    function show_pagenation($max_page, $now) {

        if ($now > 1) {
            echo '<li><a id="previous_page" href="article_management.php?page_id=' . ($now - 1) . '">前へ</a></li>';
        }

        for ($i = 1; $i <= $max_page; $i++) {
            if ($i === $now) {
                echo '<li>' . $now . '</li>';
            } else {
                echo '<li><a href="article_management.php?page_id=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($now < $max_page) {
            echo '<li><a id="next_page" href="article_management.php?page_id=' . ($now + 1) . '">次へ</a></li>';
        }

    }

    function show_article_list_bb($disp_data, $start_no, $max) {
        for ($i = 0; $i < $max; $i++) {

            if (isset($disp_data[$i + $start_no])) {
                $title = $disp_data[$i + $start_no]["title"];
                $registration_date[$i + $start_no] = $disp_data[$i + $start_no]["registration_date"];
            
            // 上部分の表示
            echo <<<ARTICLE_FRAME
            <section class="each_view_frame">
                <a href="article.php?id={$disp_data[$i + $start_no]["id"]}">
                <article class="each_view cursor_pointer">
                    <h3>{$title}
            ARTICLE_FRAME;

            // データを表示
            echo <<<ARTICLE_TOP
                    </h3>
                    <div id="each_article_info">
                        <div class="flex margin_0">
                            <p class="season_name font_size_14px">{$disp_data[$i + $start_no]["season_name"]}</p>
                            <p class="category font_size_14px">{$disp_data[$i + $start_no]["category_name"]}</p>
                            <p id="registration_date" class="font_size_14px">投稿日時：{$registration_date[$i + $start_no]}</p>
                        </div>
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
            if (mb_strlen($article_view_text) < mb_strlen($disp_data[$i + $start_no]["text"])) {
                $article_view_text .= "…";
            }

            // 下部分の表示
            echo <<<ARTICLE_BOTTOM
                    <br>
                    <p class="article_view_text">{$article_view_text}</p>
                </article>
                </a>
            </section>
            ARTICLE_BOTTOM;
            }
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
                <img id="article_image_main" src="{$article_image[0]['image_url']}" alt="{$article_image[0]['title']}">
            </div>
            <div class="each_detail_sumbnail_container">
                <div class="each_detail_sumbnail">
                    <img class="article_image_sumbnail cursor_pointer" src="{$article_image[0]['image_url']}" alt="{$article_image[0]['title']}">
                </div>
                <div class="each_detail_sumbnail">
                    <img class="article_image_sumbnail cursor_pointer" src="{$article_image[1]['image_url']}" alt="{$article_image[1]['title']}">
                </div>
                <div class="each_detail_sumbnail">
                    <img class="article_image_sumbnail cursor_pointer" src="{$article_image[2]['image_url']}" alt="{$article_image[2]['title']}">
                </div>
            </div>
        </div>
        IMAGE;
    }

    function show_article_text($text) {
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
            <p></p>
        </li>
        CURRENT_SEARCH_REQUIREMENT;
    }

    function show_article_list_am($disp_data, $start_no, $max) {
        for ($i = 0; $i < $max; $i++) {

            if (isset($disp_data[$i + $start_no])) {

                // 上部分の表示
                echo <<<ARTICLE_FRAME
                <section class="each_view_frame">
                    <a href="article.php?id={$disp_data[$i + $start_no]["id"]}">
                ARTICLE_FRAME;

                $article_view_title = mb_substr($disp_data[$i + $start_no]["title"], 0, 14);
                $registration_date = $disp_data[$i + $start_no]["registration_date"];

                // データを表示
                echo <<<ARTICLE_TOP
                    <article class="each_view cursor_pointer">
                        <div class="article_top flex space_between">
                            <h3>{$article_view_title}
                ARTICLE_TOP;
                
                if (mb_strlen($article_view_title) < mb_strlen($disp_data[$i + $start_no]["title"])) {
                    echo "…";
                }

                // 上部分の表示
                echo <<<ARTICLE_TOP
                            </h3>
                            <div class="margin_0 flex justify_content_flex_start">
                                <object><a href="edit.php?id={$disp_data[$i + $start_no]["id"]}"><img class="edit_icon" src="./image/edit.svg" alt="編集"></a></object>
                                <object><a href="article_management.php?id={$disp_data[$i + $start_no]["id"]}" onClick="return CheckDelete(this);"><img class="delete_icon margin_left_10px" src="./image/delete.svg" alt="削除"></a></object>
                            </div>
                        </div>
                        <div id="each_article_info">
                            <div class="flex margin_0">
                                <p class="season_name font_size_14px">{$disp_data[$i + $start_no]["season_name"]}</p>
                                <p class="category font_size_14px">{$disp_data[$i + $start_no]["category_name"]}</p>
                                <p class="font_size_14px">投稿日時：{$registration_date}</p>
                            </div>
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
                echo <<<ARTICLE_BOTTOM
                <br>
                <p class="article_view_text">{$article_view_text}
                ARTICLE_BOTTOM;
                
                if (mb_strlen($article_view_text) < mb_strlen($disp_data[$i + $start_no]["text"])) {
                        echo "…";
                }

                echo <<<ARTICLE_BOTTOM
                        </p>
                    </article>
                    </a>
                </section>
                ARTICLE_BOTTOM;
            }
        }
    }

    function show_edit($date, $title, $text, $point_x, $point_y, $article_image) {
        echo <<<EDIT
        <ul class="form_ul">
            <li class="flex">
                <p class="list_title width_110px" for="date">投稿日</p>
                <input class="margin_0" type="date" name="registration_date" value="{$date->format('Y-m-d')}" readonly>
            </li>
            <li class="flex">
                <p class="list_title width_110px">カテゴリ</p>
                <div class="flex radio_container margin_0">
                    <label><input type="radio" id="event" name="category" value="1">イベント</label>
                    <label><input type="radio" id="information" name="category" value="2">情報</label>
                    <label><input type="radio" id="discovery" name="category" value="3">発見</label>
                    <label><input type="radio" id="communication" name="category" value="4">連絡</label>
                </div>
            </li>
            <li class="flex">
                <p class="list_title width_110px">季節</p>
                <div class="flex radio_container margin_0">
                    <label><input type="radio" id="spring" name="season" value="1">春</label>
                    <label><input type="radio" id="summer" name="season" value="2">夏</label>
                    <label><input type="radio" id="autumn" name="season" value="3">秋</label>
                    <label><input type="radio" id="winter" name="season" value="4">冬</label>
                </div>
            </li>
            <li id="title_li" class="flex position_relative">
                <label class="list_title width_110px" for="title">タイトル</label>
                <div class="flex margin_0">
                    <input class="margin_0" type="text" id="title" name="title" cols="30" value="{$title}" title="半角30文字以内" required>
                    <p class="char_counter_title"><span>0</span> / 30</p>
                </div>
            </li>
            <li class="flex position_relative">
                <label class="list_title width_110px" for="text">本文</label>
                <div class="char_counter_box">
                    <textarea class="margin_0" id="text" name="text" rows="12" cols="80" title="1行あたり半角80文字12行以内" required>{$text}</textarea>
                    <p class="char_counter_text"><span></span> / 960</p>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_110px" for="image_1">画像１</label>
                <div class="flex margin_0">
                    <div class="image_container margin_0">
                        <img class="edit_image" src="{$article_image[0]['image_url']}" alt="{$article_image[0]['title']}">
                    </div>
                    <div class="flex flex_column margin_left_2vw">
                        <input class="margin_0" type="file" id="image_1" name="image_1" accept="image/*">
                        <div class="flex space_between width_160px margin_0">
                            <label class="font_size_14px margin_0" for="display_order_1">表示順
                                <select class="margin_0" name="display_order_1">
                                </select>
                            </label>
                            <label class="font_size_14px flex align_items_center" for="delete_image_1">削除
                                <input type="checkbox" id="delete_image_1" name="delete_image_1" class="margin_left_4px delete_image">
                            </label>
                        </div>
                    </div>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_110px" for="image_2">画像２</label>
                <div class="flex margin_0">
                    <div class="image_container margin_0">
                        <img class="edit_image" src="{$article_image[1]['image_url']}" alt="{$article_image[1]['title']}">
                    </div>
                    <div class="flex flex_column margin_left_2vw">
                        <input class="margin_0" type="file" id="image_2" name="image_2" accept="image/*">
                        <div class="flex space_between width_160px margin_0">
                            <label class="font_size_14px margin_0" for="display_order_2">表示順
                                <select class="margin_0" name="display_order_2">
                                </select>
                            </label>
                            <label class="font_size_14px flex align_items_center" for="delete_image_2">削除
                                <input type="checkbox" id="delete_image_2" name="delete_image_2" class="margin_left_4px delete_image">
                            </label>
                        </div>
                    </div>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_110px" for="image_3">画像３</label>
                <div class="flex margin_0">
                    <div class="image_container margin_0">
                        <img class="edit_image" src="{$article_image[2]['image_url']}" alt="{$article_image[2]['title']}">
                    </div>
                    <div class="flex flex_column margin_left_2vw">
                        <input class="margin_0" type="file" id="image_3" name="image_3" accept="image/*">
                        <div class="flex space_between width_160px margin_0">
                            <label class="font_size_14px margin_0" for="display_order_3">表示順
                                <select class="margin_0" name="display_order_3">
                                </select>
                            </label>
                            <label class="font_size_14px flex align_items_center" for="delete_image_3">削除
                                <input type="checkbox" id="delete_image_3" name="delete_image_3" class="margin_left_4px delete_image">
                            </label>
                        </div>
                    </div>
                </div>
            </li>
            <li class="flex">
                <p class="list_title width_110px">ポイント指定
                <ul id="map_point_container" class="flex margin_0">
                    <li class="flex map_point">
                        <label class="list_title" for="lng">Ｘ：</label>
                        <input class="margin_0" type="text" id="lng" name="point_x" style="width: 9rem" value="{$point_x}" readonly>
                    </li>
                    <li class="flex map_point">
                        <label class="list_title" for="lat">Ｙ：</label>
                        <input class="margin_0" type="text" id="lat" name="point_y" style="width: 9rem" value="{$point_y}" readonly>
                    </li>
                    <li class="flex map_point">
                        <label id="delete_point_label" class="font_size_14px flex align_items_center padding_0" for="delete_point">削除
                        <input type="checkbox" id="delete_point" name="delete_point" class="margin_left_4px" disabled>
                        </label>
                    </li>
                </ul>
            </li>
            <li class="flex justify_content_flex_end align_items_end">
                <button type="submit" id="save_button" name="save">保存</button>
            </li>
        </ul>
        EDIT;
    }

    function show_upload($date) {
        echo <<<UPLOAD
        <ul class="form_ul">
            <li class="flex">
                <p class="list_title width_110px" for="date">投稿日</p>
                <input class="margin_0" type="date" name="registration_date" value="$date" readonly>
            </li>
            <li class="flex">
                <p class="list_title width_110px">カテゴリ</p>
                <div class="flex radio_container margin_0">
                    <label><input type="radio" id="event" name="category" value="1" required>イベント</label>
                    <label><input type="radio" id="information" name="category" value="2">情報</label>
                    <label><input type="radio" id="discovery" name="category" value="3">発見</label>
                    <label><input type="radio" id="communication" name="category" value="4">連絡</label>
                </div>
            </li>
            <li class="flex">
                <p class="list_title width_110px">季節</p>
                <div class="flex radio_container margin_0">
                    <label><input type="radio" id="spring" name="season" value="1" required>春</label>
                    <label><input type="radio" id="summer" name="season" value="2">夏</label>
                    <label><input type="radio" id="autumn" name="season" value="3">秋</label>
                    <label><input type="radio" id="winter" name="season" value="4">冬</label>
                </div>
            </li>
            <li id="title_li" class="flex position_relative">
                <label class="list_title width_110px" for="title">タイトル</label>
                <div class="flex margin_0">
                    <input class="margin_0" type="text" id="title" name="title" cols="30" title="半角30文字以内" required>
                    <p class="char_counter_title"><span>0</span> / 30</p>
                </div>
            </li>
            <li class="flex position_relative">
                <label class="list_title width_110px" for="text">本文</label>
                <div class="char_counter_box">
                    <textarea class="margin_0" id="text" name="text" title="1行あたり半角80文字12行以内" required></textarea>
                    <p class="char_counter_text"><span>0</span> / 960</p>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_110px" for="image_1">画像１</label>
                <div class="flex margin_0">
                    <div class="image_container margin_0">
                        <img class="edit_image" src="image/no_image.png" alt="NO IMAGE">
                    </div>
                    <div class="flex flex_column margin_left_2vw">
                        <input class="margin_0" type="file" id="image_1" name="image_1" accept="image/*">
                        <label class="font_size_14px margin_0" for="display_order_1">表示順
                            <select class="margin_0" name="display_order_1">
                            </select>
                        </label>
                    </div>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_110px" for="image_2">画像２</label>
                <div class="flex margin_0">
                    <div class="image_container margin_0">
                        <img class="edit_image" src="image/no_image.png" alt="NO IMAGE">
                    </div>
                    <div class="flex flex_column margin_left_2vw">
                        <input class="margin_0" type="file" id="image_2" name="image_2" accept="image/*">
                        <label class="font_size_14px margin_0" for="display_order_2">表示順
                            <select class="margin_0" name="display_order_2">
                            </select>
                        </label>
                    </div>
                </div>
            </li>
            <li class="flex">
                <label class="list_title width_110px" for="image_3">画像３</label>
                <div class="flex margin_0">
                    <div class="image_container margin_0">
                        <img class="edit_image" src="image/no_image.png" alt="NO IMAGE">
                    </div>
                    <div class="flex flex_column margin_left_2vw">
                        <input class="margin_0" type="file" id="image_3" name="image_3" accept="image/*">
                        <label class="font_size_14px margin_0" for="display_order_3">表示順
                            <select class="margin_0" name="display_order_3">
                            </select>
                        </label>
                    </div>
                </div>
            </li>
            <li class="flex">
                <p class="list_title width_110px">ポイント指定
                <ul id="map_point_container" class="flex margin_0">
                    <li class="flex map_point">
                        <label class="list_title" for="lng">Ｘ：</label>
                        <input class="margin_0" type="text" id="lng" name="point_x" style="width: 9rem" readonly>
                    </li>
                    <li class="flex map_point">
                        <label class="list_title" for="lat">Ｙ：</label>
                        <input class="margin_0" type="text" id="lat" name="point_y" style="width: 9rem" readonly>
                    </li>
                    <li class="flex map_point">
                        <label id="delete_point_label" class="font_size_14px flex align_items_center padding_0" for="delete_point">削除
                        <input type="checkbox" id="delete_point" name="delete_point" class="margin_left_4px" disabled>
                        </label>
                    </li>
                </ul>
            </li>
            <li class="flex justify_content_flex_end align_items_end">
                <button type="submit" id="upload_button" name="upload">アップロード</button>
            </li>
        </ul>
        UPLOAD;
    }

?>
