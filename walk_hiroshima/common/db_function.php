<?php
class DBManager
{
    //  データベースアクセス情報
    private $access_info;
    //  データベースのユーザー名
    private $user;
    //  データベースのパスワード
    private $password;
    //  データベース
    private $db = null;
    //  コンストラクタ
    function __construct()
    {
        $this->access_info = "mysql:host=localhost:3306;dbname=sample"; // ※データ保護のため実際の情報とは異なります。
        $this->user = "sample"; // ※データ保護のため実際の情報とは異なります。
        $this->password = "sample"; // ※データ保護のため実際の情報とは異なります。
    }
    //  データベースへの接続
    private function connect()
    {
        $this->db = new PDO($this->access_info, $this->user, $this->password);
    }
    //  データベースへの接続解除
    private function disconnect()
    {
        $this->db = null;
    }

    //検索条件のカテゴリを取得
    function get_category_requirement($category_id_string) {
        try {
            // 接続
            $this->connect();

            // category_array取得用のSQLを作成する
            $category_name_array = 'SELECT';
            $category_name_array .= ' category_name';
            $category_name_array .= '  FROM category_management';
            $category_name_array .= ' WHERE category_id IN ( ' . $category_id_string . ' ) ';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($category_name_array);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合

            if ($res) {
                // 配列を宣言する
                $category_name_array = [];

                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
                    $category_name_array[] = $row;
                }
                
                // データが存在した場合は配列を返す。
                if (count($category_name_array) != 0) {
                    return $category_name_array;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                // return "データが存在しません";

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
                // return "SQL実行失敗";
            }

        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー発生";
            // return $e;
        }
    }

    //検索条件の季節を取得
    function get_season_requirement($season_id_string) {
        try {
            // 接続
            $this->connect();

            // season_array取得用のSQLを作成する
            $season_name_array = 'SELECT';
            $season_name_array .= ' season_name';
            $season_name_array .= '  FROM season_management';
            $season_name_array .= ' WHERE season_id IN ( ' . $season_id_string . ' ) ';
            $season_name_array .= ' ORDER BY season_id';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($season_name_array);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合

            if ($res) {
                // 配列を宣言する
                $season_name_array = [];

                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
                    $season_name_array[] = $row;
                }
                
                // データが存在した場合は配列を返す。
                if (count($season_name_array) != 0) {
                    return $season_name_array;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                // return "データが存在しません";

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
                // return "SQL実行失敗";
            }

        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー発生";
            // return $e;
        }
    }

    //  記事リストの取得
    function get_article_list_bb($user_id, $category_id_string, $season_id_string) {
        try {
            // 接続
            $this->connect();

            // image_url取得用のSQLを作成する
            $image_url = '(SELECT';
            $image_url .= ' image_url';
            $image_url .= '  FROM article_management';
            $image_url .= '   LEFT JOIN image_management';
            $image_url .= '    ON article_management.article_id = image_management.article_id';
            $image_url .= ' WHERE article_management.article_id = id';
            $image_url .= ' AND image_management.deletion_flag = 0';
            $image_url .= ' ORDER BY display_order ASC LIMIT 1) AS image_url';

            // SQLを定義する
            $sql = 'SELECT';
            // image_url取得用のSQL用に名前をidに変更する
            $sql .= ' article_id AS id,';
            $sql .= ' user_management.user_name AS user_name,';
            $sql .= ' category_name,';
            $sql .= ' season_name,';
            $sql .= ' title,';
            $sql .= ' text,';
            $sql .= ' article_management.registration_date AS registration_date,';
            // image_url取得用のSQLを組み込む
            $sql .= $image_url;
            $sql .= '  FROM article_management';
            $sql .= '   LEFT JOIN user_management';
            $sql .= '    ON article_management.user_id = user_management.user_id';
            $sql .= '   INNER JOIN category_management';
            $sql .= '    ON article_management.category_id = category_management.category_id';
            $sql .= '   INNER JOIN season_management';
            $sql .= '    ON article_management.season_id = season_management.season_id';
            $sql .= ' WHERE article_management.deletion_flag = 0';
            // バックナンバー検索の条件
            // 期間
            $date_start = date("0000-00-00");
            $date_end = date("Y-m-d");

            if (isset($_SESSION["date_start"]) AND $_SESSION["date_start"] != "") {
                $date_start = $_SESSION["date_start"];
            }
            if (isset($_SESSION["date_end"]) AND $_SESSION["date_end"] != "") {
                $date_end = $_SESSION["date_end"];
            }

            $sql .= ' AND DATE(article_management.registration_date) BETWEEN "' . $date_start .'" AND "' . $date_end.'"';

            // カテゴリ
            if (isset($category_id_string) AND $category_id_string !== "") {
                $sql .= ' AND category_management.category_id IN (' . $category_id_string . ')';
            }

            // 季節
            if (isset($season_id_string) AND $season_id_string !== "") {
                $sql .= ' AND season_management.season_id IN (' . $season_id_string . ')';
            }

            $sql .= ' ORDER BY article_id DESC;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // 配列を宣言する
                $article_list = [];
                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $article_list[] = $row;
                }
                
                // データが存在した場合は配列を返す。
                if (count($article_list) != 0) {
                    return $article_list;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                
            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
            }

        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
        }
    }

    //  記事画像の取得
    function get_article_image($article_id)
    {
        try {
            // 接続
            $this->connect();

            // image_url取得用のSQLを作成する
            $image_url = 'SELECT';
            $image_url .= ' image_id,';
            $image_url .= ' display_order,';
            $image_url .= ' image_url,';
            $image_url .= ' title';
            $image_url .= '  FROM image_management';
            $image_url .= '   LEFT JOIN article_management';
            $image_url .= '    ON image_management.article_id = article_management.article_id';
            $image_url .= ' WHERE image_management.article_id = ' . $article_id;
            $image_url .= ' AND image_management.deletion_flag = 0';
            $image_url .= ' ORDER BY display_order ASC';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($image_url);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // 配列を宣言する
                $no_image = array('image_id'=>'', 'display_order'=>'', 'image_url'=>'image/no_image.png', 'title'=>'NO IMAGE');
                $article_image = array_fill(0, 3, $no_image);

                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                for ($i = 0; $i < count($row); $i++) {
                    $article_image[$i] = $row[$i];
                }
                
                // 配列を返す。
                return $article_image;

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
            }

        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
        }
    }

    //  記事情報の取得
    function get_article_info($article_id)
    {
        try {
            // 接続
            $this->connect();

            // article_info取得用のSQLを作成する
            $article_info = 'SELECT';
            $article_info .= ' user_management.user_name AS user_name,';
            $article_info .= ' article_management.category_id AS category_id,';
            $article_info .= ' category_management.category_name AS category_name,';
            $article_info .= ' article_management.season_id AS season_id,';
            $article_info .= ' season_management.season_name AS season_name,';
            $article_info .= ' title,';
            $article_info .= ' text,';
            $article_info .= ' point_x,';
            $article_info .= ' point_y,';
            $article_info .= ' article_management.registration_date AS registration_date';
            $article_info .= '  FROM article_management';
            $article_info .= ' LEFT JOIN user_management';
            $article_info .= '  ON article_management.user_id = user_management.user_id';
            $article_info .= ' JOIN category_management';
            $article_info .= '  ON article_management.category_id = category_management.category_id';
            $article_info .= ' JOIN season_management';
            $article_info .= '  ON article_management.season_id = season_management.season_id';
            $article_info .= ' WHERE article_id = ' . $article_id;

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($article_info);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合

            if ($res) {
                // 配列を宣言する
                $article_info = [];

                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $article_info = $row;
                }
                
                // データが存在した場合は配列を返す。
                if (count($article_info) != 0) {
                    return $article_info;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                // return "データが存在しません";

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
                // return "SQL実行失敗";
            }

        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー発生";
            // return $e;
        }
    }

    // 地図情報を取得
    function get_article_list_im() {
        try {
            // 接続
            $this->connect();

            // image_url取得用のSQLを作成する
            $image_url = '(SELECT';
            $image_url .= ' image_url';
            $image_url .= '  FROM article_management';
            $image_url .= '   LEFT JOIN image_management';
            $image_url .= '    ON article_management.article_id = image_management.article_id';
            $image_url .= ' WHERE article_management.article_id = id';
            $image_url .= ' AND image_management.deletion_flag = 0';
            $image_url .= ' AND image_management.display_order = 1) AS image_url';

            // SQLを定義する
            $sql = 'SELECT';
            // image_url取得用のSQL用に名前をidに変更する
            $sql .= ' article_id AS id,';
            $sql .= ' category_name,';
            $sql .= ' season_name,';
            $sql .= ' title,';
            $sql .= ' text,';
            $sql .= ' point_x,';
            $sql .= ' point_y,';
            $sql .= ' article_management.registration_date AS registration_date,';
            // image_url取得用のSQLを組み込む
            $sql .=  $image_url;
            $sql .= '  FROM article_management';
            $sql .= '   LEFT JOIN user_management';
            $sql .= '    ON article_management.user_id = user_management.user_id';
            $sql .= '   INNER JOIN category_management';
            $sql .= '    ON article_management.category_id = category_management.category_id';
            $sql .= '   INNER JOIN season_management';
            $sql .= '    ON article_management.season_id = season_management.season_id';
            $sql .= ' WHERE article_management.deletion_flag = 0';
            // バックナンバー検索の条件
            // 期間
            $date_start = date("0000-00-00");
            $date_end = date("Y-m-d");

            if (isset($_POST["date_start"]) AND $_POST["date_start"] != "") {
                $date_start = $_POST["date_start"];
            }
            if (isset($_POST["date_end"]) AND $_POST["date_end"] != "") {
                $date_end = $_POST["date_end"];
            }

            $sql .= ' AND DATE(article_management.registration_date) BETWEEN "' . $date_start .'" AND "' . $date_end.'"';

            // カテゴリ
            if (isset($_POST["category"]) AND is_array($_POST['category'])) {
                $category = $_POST["category"];

                $sql .= ' AND category_management.category_id IN (';
                $category_length = count($category);
                for ($i = 0; $i < $category_length; $i++) {
                    if ($category_length == 1) {
                        $sql .= $category[$i];
                    }
                    else if ($i != $category_length - 1) {
                        $sql .= $category[$i] . ', ';
                    }
                    else if ($i = $category_length - 1) {
                        $sql .= $category[$i];
                    }
                }
                $sql .= ')';
            }

            // 季節
            if (isset($_POST["season"]) AND is_array($_POST['season'])) {
                $season = $_POST["season"];

                $sql .= ' AND season_management.season_id IN (';
                $season_length = count($season);
                for ($i = 0; $i < $season_length; $i++) {
                    if ($season_length == 1) {
                        $sql .= $season[$i];
                    }
                    else if ($i != $season_length - 1) {
                        $sql .= $season[$i] . ', ';
                    }
                    else if ($i = $season_length - 1) {
                        $sql .= $season[$i];
                    }
                }
                $sql .= ')';
            }

            $sql .= ' ORDER BY article_id ASC;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // 配列を宣言する
                $article_list = [];
                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $article_list[] = $row;
                }
                
                // データが存在した場合は配列を返す。
                if (count($article_list) != 0) {
                    return $article_list;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                
            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
            }

        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
        }
    }

    function get_category_length() {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'SELECT category_id from category_management';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {

                // SQLの実行結果から、行数を取得して変数へ代入する。
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $num_rows = count($row);
                
                // データが存在した場合は配列を返す。
                if ($num_rows != 0) {
                    return $num_rows;
                }

                // データが存在しない場合はNULLを返す。
                return null;

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
            }

            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
        }
    }

    function get_season_length() {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'SELECT season_id from season_management';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {

                // SQLの実行結果から、行数を取得して変数へ代入する。
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $num_rows = count($row);
                
                // データが存在した場合は配列を返す。
                if ($num_rows != 0) {
                    return $num_rows;
                }

                // データが存在しない場合はNULLを返す。
                return null;

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
            }

            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
        }
    }

    //データベースのarticle_idの最大値を取得
    
    function get_max_article_id() {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'SELECT MAX(article_id) from article_management';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL文の実行が成功した場合
            if ($res) {

                // SQLの実行結果から、行数を取得して変数へ代入する。
                while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
                    $max_article_id = $row;
                }
                
                // データが存在した場合
                if ($max_article_id) {
                    return $max_article_id;
                }

                // データが存在しない場合はNULLを返す。
                return 0;

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
            }

            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
        }
    }

    // 新しい記事を保存する
    function save_new_article($article_id, $user_id) {

        if (isset($user_id)) {
            $category_id = $_POST["category"];
            $season_id =  $_POST["season"];
            $title =  $_POST["title"];
            $text =  $_POST["text"];
            if (isset($_POST['point_x'])) {
                $point_x =  $_POST["point_x"];
            }
            if (isset($_POST['point_y'])) {
                $point_y =  $_POST["point_y"];
            }

            try {
                // 接続
                $this->connect();

                // SQLを定義する
                $sql = 'INSERT';
                $sql .= ' INTO';
                $sql .= ' article_management';
                $sql .= ' (article_id,';
                $sql .= ' post_date,';
                $sql .= ' user_id,';
                $sql .= ' category_id,';
                $sql .= ' season_id,';
                $sql .= ' title,';
                $sql .= ' text,';
                $sql .= ' point_x,';
                $sql .= ' point_y,';
                $sql .= ' registration_date)';
                $sql .= ' VALUES';
                $sql .= ' (?, now(), ?, ?, ?, ?, ?, ?, ?, now());';

                // SQLをデータベースサーバーに送信し、実行の準備を整える。
                $stmt = $this->db->prepare($sql);

                $stmt->bindParam(1, $article_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $user_id, PDO::PARAM_INT);
                $stmt->bindParam(3, $category_id, PDO::PARAM_INT);
                $stmt->bindParam(4, $season_id, PDO::PARAM_INT);
                $stmt->bindParam(5, $title, PDO::PARAM_STR);
                $stmt->bindParam(6, $text, PDO::PARAM_STR);
                $stmt->bindParam(7, $point_x, PDO::PARAM_STR);
                $stmt->bindParam(8, $point_y, PDO::PARAM_STR);

                // SQLの実行
                $res = $stmt->execute();

                // 接続解除
                $this->disconnect();

                // SQL分の実行が成功した場合
                if ($res) {
                    // echo "<br><br><br>";
                    // return "大成功";
                }
                
                // エラーが発生した場合は接続解除をしてNULLを返す。
            } catch (PDOException $e) {
                $this->disconnect();
                // return null;
                // return "エラー";
                // echo "<br><br><br>";
                // return $e;
                // return $new_point_y;
            }
        }
    }

    // 新しい画像を保存する
    function save_new_image($article_id, $user_id, $display_order, $new_image_url) {

        $return = $this->confirm_user_id($user_id, $article_id, $this);

        if ($return) {
            try {
                // 接続
                $this->connect();

                // SQLを定義する
                $sql = 'INSERT';
                $sql .= ' INTO';
                $sql .= ' image_management';
                $sql .= ' (article_id,';
                $sql .= ' display_order,';
                $sql .= ' image_url,';
                $sql .= ' registration_date)';
                $sql .= ' VALUES';
                $sql .= ' (?, ?, ?, now());';

                // SQLをデータベースサーバーに送信し、実行の準備を整える。
                $stmt = $this->db->prepare($sql);

                $stmt->bindParam(1, $article_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $display_order, PDO::PARAM_INT);
                $stmt->bindParam(3, $new_image_url, PDO::PARAM_STR);

                // SQLの実行
                $res = $stmt->execute();

                // 接続解除
                $this->disconnect();

                // SQL分の実行が成功した場合
                if ($res) {
                    // echo "<br><br><br>";
                    // return "大成功";
                }
                
                // エラーが発生した場合は接続解除をしてNULLを返す。
            } catch (PDOException $e) {
                $this->disconnect();
                // return null;
                // return "エラー";
                // echo "<br><br><br>";
                // return $e;
            }
        }
    }

    // 記事の編集を保存する
    function save_article_edit($article_id, $user_id) {
        
        $return = $this->confirm_user_id($user_id, $article_id, $this);
        
        if ($return) {
            $new_category_id = $_POST['category'];
            $new_season_id = $_POST['season'];
            $new_title = $_POST['title'];
            $new_text = $_POST['text'];
            $new_point_x = $_POST['point_x'];
            $new_point_y = $_POST['point_y'];
            $update_date = date("Y-m-d H:i:s");

            try {
                // 接続
                $this->connect();

                // SQLを定義する
                $sql = 'UPDATE';
                $sql .= ' article_management';
                $sql .= ' SET';
                $sql .= ' category_id = ?,';
                $sql .= ' season_id = ?,';
                $sql .= ' title = ?,';
                $sql .= ' text = ?,';
                $sql .= ' point_x = ?,';
                $sql .= ' point_y = ?,';
                $sql .= ' update_date = ?';
                $sql .= ' WHERE article_id = ?;';

                // SQLをデータベースサーバーに送信し、実行の準備を整える。
                $stmt = $this->db->prepare($sql);

                $stmt->bindParam(1, $new_category_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $new_season_id, PDO::PARAM_INT);
                $stmt->bindParam(3, $new_title, PDO::PARAM_STR);
                $stmt->bindParam(4, $new_text, PDO::PARAM_STR);
                $stmt->bindParam(5, $new_point_x, PDO::PARAM_STR);
                $stmt->bindParam(6, $new_point_y, PDO::PARAM_STR);
                $stmt->bindParam(7, $update_date, PDO::PARAM_STR);
                $stmt->bindParam(8, $article_id, PDO::PARAM_INT);

                // SQLの実行
                $res = $stmt->execute();

                // 接続解除
                $this->disconnect();

                // SQL分の実行が成功した場合
                if ($res) {
                    // echo "<br><br><br>";
                    // return "成功";
                }

                // データが存在しない場合はNULLを返す。
                return "データなし";
                
            // エラーが発生した場合は接続解除をしてNULLを返す。
            } catch (PDOException $e) {
                $this->disconnect();
                // return null;
                // return "エラー";
                return $e;
            }
        }
    }

    //データベースのimage_idの最大値を取得
    
    function get_max_image_id() {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'SELECT MAX(image_id) from image_management';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL文の実行が成功した場合
            if ($res) {

                // SQLの実行結果から、行数を取得して変数へ代入する。
                while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
                    $max_image_id = $row;
                }
                
                // データが存在した場合
                if ($max_image_id) {
                    return $max_image_id;
                }

                // データが存在しない場合はNULLを返す。
                return 0;

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
            }

            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
        }
    }

    // 画像を削除する
    function delete_image($image_id, $article_id, $user_id) {

        $return = $this->confirm_user_id($user_id, $article_id, $this);

        if ($return) {
            try {
                // 接続
                $this->connect();

                // SQLを定義する
                $sql = 'UPDATE';
                $sql .= ' image_management';
                $sql .= ' SET';
                $sql .= ' display_order = 0,';
                $sql .= ' deletion_date = now(),';
                $sql .= ' deletion_flag = 1';
                $sql .= ' WHERE image_id = ?;';

                // SQLをデータベースサーバーに送信し、実行の準備を整える。
                $stmt = $this->db->prepare($sql);

                $stmt->bindParam(1, $image_id, PDO::PARAM_INT);

                // SQLの実行
                $res = $stmt->execute();

                // 接続解除
                $this->disconnect();

                // SQL分の実行が成功した場合
                if ($res) {
                    // echo "<br><br><br>";
                    // return "成功";
                }
                
                // エラーが発生した場合は接続解除をしてNULLを返す。
            } catch (PDOException $e) {
                $this->disconnect();
                // return null;
                // return "エラー";
                return $e;
                // return $new_point_y;
            }
        }
    }

    // 記事の画像を変更する
    function update_image($image_id, $display_order, $new_image_url, $user_id, $article_id) {

        $return = $this->confirm_user_id($user_id, $article_id, $this);

        if ($return) {
            try {
                // 接続
                $this->connect();

                // SQLを定義する
                $sql = 'UPDATE';
                $sql .= ' image_management';
                $sql .= ' SET';
                $sql .= ' display_order = ?,';
                $sql .= ' image_url = ?,';
                $sql .= ' update_date = now()';
                $sql .= ' WHERE image_id = ?;';

                // SQLをデータベースサーバーに送信し、実行の準備を整える。
                $stmt = $this->db->prepare($sql);

                $stmt->bindParam(1, $display_order, PDO::PARAM_INT);
                $stmt->bindParam(2, $new_image_url, PDO::PARAM_STR);
                $stmt->bindParam(3, $image_id, PDO::PARAM_INT);

                // echo '<br>';
                // echo $stmt;

                // SQLの実行
                $res = $stmt->execute();

                // 接続解除
                $this->disconnect();

                // SQL分の実行が成功した場合
                if ($res) {
                    // echo "<br><br><br>";
                    // return "成功";
                }
                
                // エラーが発生した場合は接続解除をしてNULLを返す。
            } catch (PDOException $e) {
                $this->disconnect();
                return null;
                // return "エラー";
                // return $e;
                // return $new_point_y;
            }
        }
    }

    function change_display_order($image_id, $new_display_order, $user_id, $article_id) {

        $return = $this->confirm_user_id($user_id, $article_id, $this);

        if ($return) {
            try {
                // 接続
                $this->connect();

                // SQLを定義する
                $sql = 'UPDATE';
                $sql .= ' image_management';
                $sql .= ' SET';
                $sql .= ' display_order = ?,';
                $sql .= ' update_date = now()';
                $sql .= ' WHERE image_id = ?;';

                // SQLをデータベースサーバーに送信し、実行の準備を整える。
                $stmt = $this->db->prepare($sql);

                $stmt->bindParam(1, $new_display_order, PDO::PARAM_INT);
                $stmt->bindParam(2, $image_id, PDO::PARAM_INT);

                // SQLの実行
                $res = $stmt->execute();

                // 接続解除
                $this->disconnect();

                // SQL分の実行が成功した場合
                if ($res) {
                    // echo "<br><br><br>";
                    // return "成功";
                }
                
                // エラーが発生した場合は接続解除をしてNULLを返す。
            } catch (PDOException $e) {
                $this->disconnect();
                return null;
                // return "エラー";
                // return $e;
                // return $new_point_y;
            }
        }
    }

    function delete_article($article_id) {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'DELETE FROM';
            $sql .= ' article_management';
            $sql .= ' WHERE article_id = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $article_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // echo "<br><br><br>";
                // return "成功";
            }
            
            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー";
            // return $e;
        }
    }

    function get_user_id($user_name) {
        try {
            // 接続
            $this->connect();

            // user_id取得用のSQLを作成する
            $sql = 'SELECT';
            $sql .= ' user_id';
            $sql .= '  FROM user_management';
            $sql .= ' WHERE BINARY user_name = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $user_name, PDO::PARAM_STR);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合

            if ($res) {
                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
                    $user_id = $row;
                }
                
                // データが存在した場合
                if (isset($user_id)) {
                    return $user_id;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                // return "データが存在しません";

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
                // return "SQL実行失敗";
            }    
        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー";
            // return $e;
            // return $new_point_y;
        }
    }

    function create_account($user_name, $password) {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'INSERT';
            $sql .= ' INTO';
            $sql .= ' user_management';
            $sql .= ' (user_name,';
            $sql .= ' user_password,';
            $sql .= ' registration_date)';
            $sql .= ' VALUES';
            $sql .= ' (?, ?, now());';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $user_name, PDO::PARAM_STR);
            $stmt->bindParam(2, $password, PDO::PARAM_STR);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // echo "<br><br><br>";
                // return "成功";
            }
            
            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー";
            // echo "<br><br><br>";
            // return $e;
        }
    }

    function get_user_name($user_id) {
        try {
            // 接続
            $this->connect();

            // user_name取得用のSQLを作成する
            $sql = 'SELECT';
            $sql .= ' user_name';
            $sql .= '  FROM user_management';
            $sql .= ' WHERE user_id = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合

            if ($res) {
                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
                    $user_name = $row;
                }
                
                // データが存在した場合
                if (isset($user_name)) {
                    return $user_name;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                // return "データが存在しません";

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
                // return "SQL実行失敗";
            }    
        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー";
            // return $e;
            // return $new_point_y;
        }
    }

    function get_password($user_id) {
        try {
            // 接続
            $this->connect();

            // password取得用のSQLを作成する
            $sql = 'SELECT';
            $sql .= ' user_password';
            $sql .= '  FROM user_management';
            $sql .= ' WHERE user_id = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合

            if ($res) {
                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
                    $user_password = $row;
                }
                
                // データが存在した場合
                if (isset($user_password)) {
                    return $user_password;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                // return "データが存在しません";

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
                // return "SQL実行失敗";
            }    
        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー";
            // return $e;
            // return $new_point_y;
        }
    }

    function update_user_name($user_id, $new_user_name) {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'UPDATE';
            $sql .= ' user_management';
            $sql .= ' SET';
            $sql .= ' user_name = ?,';
            $sql .= ' update_date = now()';
            $sql .= ' WHERE user_id = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $new_user_name, PDO::PARAM_STR);
            $stmt->bindParam(2, $user_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // echo "<br><br><br>";
                // return "成功";
            }
            
            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー";
            // return $e;
            // return $new_point_y;
        }
    }

    function update_password($user_id, $new_password_hash) {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'UPDATE';
            $sql .= ' user_management';
            $sql .= ' SET';
            $sql .= ' user_password = ?,';
            $sql .= ' update_date = now()';
            $sql .= ' WHERE user_id = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $new_password_hash, PDO::PARAM_STR);
            $stmt->bindParam(2, $user_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // echo "<br><br><br><br>";
                // return "成功";
            }
            
            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー";
            // echo "<br><br><br><br>";
            // return $e;
            // return $new_point_y;
        }
    }

    function delete_account($user_id) {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'UPDATE';
            $sql .= ' user_management';
            $sql .= ' SET';
            $sql .= ' deletion_date = now(),';
            $sql .= ' deletion_flag = 1';
            $sql .= ' WHERE user_id = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // echo "<br><br><br>";
                // return "アカウント削除成功";
            }
            
            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "アカウント削除エラー";
            // return $e;
            // return $new_point_y;
        }
    }

    function delete_all_article($user_id) {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'UPDATE';
            $sql .= ' article_management';
            $sql .= ' SET';
            $sql .= ' deletion_date = now(),';
            $sql .= ' deletion_flag = 1';
            $sql .= ' WHERE user_id = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // echo "<br><br><br>";
                // return "記事削除成功";
            }
            
            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "記事削除エラー";
            // return $e;
            // return $new_point_y;
        }
    }

    function delete_all_image($user_id) {
        try {
            // 接続
            $this->connect();

            // SQLを定義する
            $sql = 'UPDATE';
            $sql .= ' image_management';
            $sql .= ' SET';
            $sql .= ' deletion_date = now(),';
            $sql .= ' deletion_flag = 1';
            $sql .= ' WHERE';
            $sql .= ' article_id =';
            $sql .= ' (SELECT article_id';
            $sql .= ' FROM article_management';
            $sql .= ' WHERE user_id = ?);';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // echo "<br><br><br>";
                // return "画像削除成功";
            }
            
            // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "画像削除エラー";
            // return $e;
            // return $new_point_y;
        }
    }

    function get_confirm_user_id($article_id) {
        try {
            // 接続
            $this->connect();

            // user_id取得用のSQLを作成する
            $sql = 'SELECT';
            $sql .= ' user_id';
            $sql .= '  FROM article_management';
            $sql .= ' WHERE article_id = ?;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(1, $article_id, PDO::PARAM_INT);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合

            if ($res) {
                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_COLUMN)) {
                    $confirm_user_id = $row;
                }
                
                // データが存在した場合
                if (isset($confirm_user_id)) {
                    return $confirm_user_id;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                // return "データが存在しません";

            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
                // return "SQL実行失敗";
            }    
        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return "エラー";
            // return $e;
            // return $new_point_y;
        }
    }

    function confirm_user_id($user_id, $article_id, $dbm) {
        if (isset($_SESSION['user_id'])) {
            $confirm_user_id = $dbm->get_confirm_user_id($article_id);
            // echo "<br><br><br>";
            // echo "user_id" . $user_id;
            // echo "<br>";
            // echo "confirm_user_id" . $confirm_user_id;
            if ($user_id == $confirm_user_id) {
                return true;
            } else {
                echo '<script type="text/javascript">alert("ログインしてください");
                window.location.href = "index.php";</script>';
                return false;
            }
        } else {
            echo '<script type="text/javascript">alert("ログインしてください");
            window.location.href = "index.php";</script>';
            return false;
        }
    }

    //  記事リストの取得
    function get_article_list_am($user_id, $category_id_string, $season_id_string) {
        try {
            // 接続
            $this->connect();

            // image_url取得用のSQLを作成する
            $image_url = '(SELECT';
            $image_url .= ' image_url';
            $image_url .= '  FROM article_management';
            $image_url .= '   LEFT JOIN image_management';
            $image_url .= '    ON article_management.article_id = image_management.article_id';
            $image_url .= ' WHERE article_management.article_id = id';
            $image_url .= ' AND image_management.deletion_flag = 0';
            $image_url .= ' ORDER BY display_order ASC LIMIT 1) AS image_url';

            // SQLを定義する
            $sql = 'SELECT';
            // image_url取得用のSQL用に名前をidに変更する
            $sql .= ' article_id AS id,';
            $sql .= ' user_management.user_name AS user_name,';
            $sql .= ' category_name,';
            $sql .= ' season_name,';
            $sql .= ' title,';
            $sql .= ' text,';
            $sql .= ' article_management.registration_date AS registration_date,';
            // image_url取得用のSQLを組み込む
            $sql .= $image_url;
            $sql .= '  FROM article_management';
            $sql .= '   LEFT JOIN user_management';
            $sql .= '    ON article_management.user_id = user_management.user_id';
            $sql .= '   INNER JOIN category_management';
            $sql .= '    ON article_management.category_id = category_management.category_id';
            $sql .= '   INNER JOIN season_management';
            $sql .= '    ON article_management.season_id = season_management.season_id';
            $sql .= ' WHERE article_management.user_id = '. $user_id . ' AND article_management.deletion_flag = 0';
            // バックナンバー検索の条件
            // 期間
            $date_start = date("0000-00-00");
            $date_end = date("Y-m-d");

            if (isset($_SESSION["date_start"]) AND $_SESSION["date_start"] != "") {
                $date_start = $_SESSION["date_start"];
            }
            if (isset($_SESSION["date_end"]) AND $_SESSION["date_end"] != "") {
                $date_end = $_SESSION["date_end"];
            }

            $sql .= ' AND DATE(article_management.registration_date) BETWEEN "' . $date_start .'" AND "' . $date_end.'"';

            // カテゴリ

            if (isset($category_id_string) AND $category_id_string !== "") {
                $sql .= ' AND category_management.category_id IN (' . $category_id_string . ')';
            }

            // 季節
            if (isset($season_id_string) AND $season_id_string !== "") {
                $sql .= ' AND season_management.season_id IN (' . $season_id_string . ')';
            }

            $sql .= ' ORDER BY article_id DESC;';

            // SQLをデータベースサーバーに送信し、実行の準備を整える。
            $stmt = $this->db->prepare($sql);

            // SQLの実行
            $res = $stmt->execute();

            // 接続解除
            $this->disconnect();

            // SQL分の実行が成功した場合
            if ($res) {
                // 配列を宣言する
                $article_list = [];
                // SQLの実行結果から、1行ずつデータを取得し、配列に格納する。
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $article_list[] = $row;
                }
                
                // データが存在した場合は配列を返す。
                if (count($article_list) != 0) {
                    return $article_list;
                }

                // データが存在しない場合はNULLを返す。
                return null;
                
            // SQL文の実行が失敗した場合、NULLを返す。
            } else {
                return null;
            }

        // エラーが発生した場合は接続解除をしてNULLを返す。
        } catch (PDOException $e) {
            $this->disconnect();
            return null;
            // return $e;
        }
    }
}

$dbm = new DBManager();

?>