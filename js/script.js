document.addEventListener('DOMContentLoaded', (event) => {

    let pathname = window.location.pathname.replace('/', '');
    console.log(pathname);

    switch (pathname) {
        case "walk_hiroshima/index.php":
            index();
            break;

        case "walk_hiroshima/account_top.php":
            account_top();
            break;

        // case "walk_hiroshima/bulletion_board.php":
        //     bulletion_board();
        //     break;

        // case "walk_hiroshima/information_map.php":
        //     informationMap();
        //     break;

        // case "walk_hiroshima/article.php":
        //     article();
        //     break;

        case "walk_hiroshima/upload.php":
            upload();
            break;
        
        case "walk_hiroshima/edit.php":
            edit();
            break;

        case "walk_hiroshima/account_setting.php":
        account_setting();
        break;
    
        default:
    }
});

function index() {
    let headerLogin = document.getElementById('header_login');
    let headerCreateAccount = document.getElementById('header_create_account');
    let mainImage = document.getElementById('main_image');
    let mainImageChildren = mainImage.children;
    let mainLogin = document.getElementById('main_login');
    let mainCreateAccount = document.getElementById('main_create_account');
    let naviMenu = document.getElementById('navi_menu');
    let mainLogo = document.getElementById('main_logo');
    let logIn = document.getElementById('login');
    let createAccount = document.getElementById('create_account');

    // メインイメージクリックイベント
    for(i = 0; i < mainImageChildren.length; i++) {
        mainImageChildren[i].addEventListener('click', (event) => {
            naviMenu.classList.remove('display_none');
            mainLogo.classList.add('display_none');
        });
    }

    //メインのリンク選択イベント
    mainLogin.addEventListener('click', (event) => {
        log_in();
    });
    mainCreateAccount.addEventListener('click', (event) => {
        create_account();
    })

    // ヘッダーのリンク選択イベント
    headerLogin.addEventListener('click', (event) => {
        log_in();
    })
    headerCreateAccount.addEventListener('click', (event) => {
        create_account();
    })

    function log_in() {
        logIn.classList.remove('display_none');
        if(!naviMenu.classList.contains('display_none')) {
            naviMenu.classList.add('display_none');
        }
        if(!mainLogo.classList.contains('display_none')) {
            mainLogo.classList.add('display_none');
        }
        if(!createAccount.classList.contains('display_none')) {
            createAccount.classList.add('display_none');
        }
    }

    function create_account() {
        createAccount.classList.remove('display_none');
        if(!naviMenu.classList.contains('display_none')) {
            naviMenu.classList.add('display_none');
        }
        if(!mainLogo.classList.contains('display_none')) {
            mainLogo.classList.add('display_none');
        }
        if(!logIn.classList.contains('display_none')) {
            logIn.classList.add('display_none');
        }
    }
}

// function informationMap() {

//     // document.addEventListener('DOMContentLoaded', (event) => {
//     // 地図の中心座標とズームレベルを指定
//     var map = L.map('map').setView([34.39803368302145, 132.47533146205527], 16);

//     // OpenStreetMapのタイルを追加
//     L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//         attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//     }).addTo(map);

//     // アイコンの設定
//     var SpringIcon = L.icon({
//         iconUrl: './GIMP/春.png',
//         iconSize: [52, 48],
//         iconAnchor: [12, 41],
//         popupAnchor: [0, -41]
//     });

//     var SummerIcon = L.icon({
//         iconUrl: './GIMP/夏.png',
//         iconSize: [77, 55],
//         iconAnchor: [12, 41],
//         popupAnchor: [0, -41]
//     });

//     var AutumnIcon = L.icon({
//         iconUrl: './GIMP/秋.png',
//         iconSize: [55, 58],
//         iconAnchor: [12, 41],
//         popupAnchor: [0, -41]
//     });

//     var WinterIcon = L.icon({
//         iconUrl: './GIMP/冬.png',
//         iconSize: [50, 50],
//         iconAnchor: [12, 41],
//         popupAnchor: [0, -41]
//     });

//     // マーカーを追加

//     var ToushouguMarker = L.marker([34.40276121000169, 132.475350481624], { icon: SpringIcon }).addTo(map);
//     var FukuyaMarker = L.marker([34.39669888030995, 132.47375123065123], { icon: SummerIcon}).addTo(map);
//     var HigashikuminMarker = L.marker([34.39589684258891, 132.4834428369069], { icon: AutumnIcon}).addTo(map);
//     var HiroshimaHospitalMarker = L.marker([34.40127456753965, 132.47783284910324], { icon: WinterIcon}).addTo(map);
    
//     // ポップアップの追加
//     ToushouguMarker.bindPopup("○○神社　秋まつり");
//     FukuyaMarker.bindPopup("○○神社　秋まつり");
//     HigashikuminMarker.bindPopup("○○神社　秋まつり");
//     HiroshimaHospitalMarker.bindPopup("○○神社　秋まつり");

//     ToushouguMarker.on('click', function (e) {
//         // クリックされた時の処理
//         const h2 = document.getElementById('info').children[0];
//         const p = document.getElementById('info').children[1];
//         const h3 = document.getElementById('info').children[2];
//         const address = document.getElementById('info').children[3];
//         const tel = document.getElementById('info').children[4];
//         const mail = document.getElementById('info').children[5];
//         const url = document.getElementById('info').children[6];

//         h2.innerHTML = "カラーズ・ラボ 広島";
//         p.innerHTML = "本通駅から徒歩5分、福祉・IT業界経験者のスタッフが、個々の状況や特性に合わせて、就職へ向けたサポートを行います。メンタルケア、コミュニケーションや動画で学べるWEB系プログラミング、WEBデザイン、office学習、就職活動、在宅就労に向けたサポート、定着支援、ときどきイベント。まずは一度ご見学ください！";
//         h3.innerHTML = "～お問い合わせはこちらへ～"
//         address.innerHTML = "広島県広島市中区大手町2丁目8-5　合人社広島大手町ビル7Ｆ";
//         tel.innerHTML = "082-258-2295";
//         mail.innerHTML = "hiroshima@cr2.co.jp";
//         url.children[0].innerHTML = "https://crpalette.co.jp/lp/colorsLABO/";
//         url.children[0].href = "https://crpalette.co.jp/lp/colorsLABO/";

//         console.log(url.children[0]);
//     });

//     let mapSearchToggle = document.getElementById('map_search_toggle');
//     let mapSeachDisplay = document.getElementById('map_search_display');
//     let mapCurrentSearchRequirementToggle = document.getElementById('map_current_search_requirement_toggle');
//     let mapCurrentSearchRequirementDisplay = document.getElementById('map_current_search_requirement_display');

//     // タブレット時の検索欄の折り畳み
//     if(window.matchMedia('(max-width:1220px)').matches) {
//         mapSearchToggle.addEventListener('click', (event) => {
//             mapSeachDisplay.classList.toggle('display_none');
//             mapCurrentSearchRequirementDisplay.classList.toggle('display_none');
//         })
        
//         mapCurrentSearchRequirementToggle.addEventListener('click', (event) => {
//             mapSeachDisplay.classList.toggle('display_none');
//             mapCurrentSearchRequirementDisplay.classList.toggle('display_none');
//         })   
//     }

//     // モバイル時の検索欄の折り畳み
//     if(window.matchMedia('(max-width:625px)').matches) {
//         mapSeachDisplay.classList.add('display_none');
//         mapSearchToggle.addEventListener('click', (event) => {
//             mapSeachDisplay.classList.toggle('display_none');
//         })
        
//         mapCurrentSearchRequirementDisplay.classList.add('display_none');
//         mapCurrentSearchRequirementToggle.addEventListener('click', (event) => {
//             mapCurrentSearchRequirementDisplay.classList.toggle('display_none');
//         })   
//     }
// };

// function article() {
//         // 地図の中心座標とズームレベルを指定
//         // var point_x = JSON.parse('<?php echo $point_x; ?>');
//         var point_x = <?php echo $point_y; ?>;
//         console.log(point_x);
//         console.log('<?php echo $point_y; ?>, <?php echo $point_x; ?>');
//         var map2 = L.map('map2').setView([<?php echo $point_y; ?>, <?php echo $point_x; ?>], 16);

//         // OpenStreetMapのタイルを追加
//         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//         }).addTo(map2);
// }

function upload() {
    // 地図の中心座標とズームレベルを指定
    var map3 = L.map('map3').setView([34.39803368302145, 132.47533146205527], 16);

    // OpenStreetMapのタイルを追加
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map3);

    let inputPointX = document.getElementById("lng");
    // console.log(inputPointX);
    let inputPointY = document.getElementById("lat");
    // console.log(inputPointY);

    //座標取得クリックイベント
    map3.on('click', function(e) {
        //クリック位置経緯度取得
        lat = e.latlng.lat;
        lng = e.latlng.lng;
        //経緯度表示
        if (window.confirm("ポイント指定Ｘ： " + lng + "\nポイント指定Ｙ： " + lat +"\nに書き替えますか？")) {
            inputPointX.value = lng;
            inputPointY.value = lat;
        };
    });
};

function edit() {
    // 地図の中心座標とズームレベルを指定
    var map4 = L.map('map4').setView([34.39803368302145, 132.47533146205527], 16);

    // OpenStreetMapのタイルを追加
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map4);

    let inputPointX = document.getElementById("lng");
    // console.log(inputPointX);
    let inputPointY = document.getElementById("lat");
    // console.log(inputPointY);

    //座標取得クリックイベント
    map4.on('click', function(e) {
        //クリック位置経緯度取得
        lat = e.latlng.lat;
        lng = e.latlng.lng;
        //経緯度表示
        if (window.confirm("ポイント指定Ｘ： " + lng + "\nポイント指定Ｙ： " + lat +"\nに書き替えますか？")) {
            inputPointX.value = lng;
            inputPointY.value = lat;
        };
    });

    // // 画像変更イベント
    // let inputImageArray = document.querySelectorAll('input[type="file"]');
    // console.log(inputImageArray);
    // let editImageArray = document.getElementsByClassName('edit_image');
    // console.log(editImageArray);
    // for (let i = 0; i < inputImageArray.length; i++) {
    //     inputImage = inputImageArray[i];
    //     console.log(inputImage);
    //     inputImage.addEventListener('change', (e) => {
    //         let fileUrl = e.target.files[0].name;
    //         console.log(fileUrl);
    //         editImageArray[i].src = '"' + fileUrl + '"';
    //         editImageArray[i].alt = '"' + fileUrl + '"';
    //     });
    // // https://www.google.com/search?q=queryselector+js&sca_esv=f78a37cf4b71fe3c&sxsrf=AHTn8zpsO7XCc53_y_PqX81hojY_I9JxtA%3A1738818311263&ei=B0OkZ_XeD7S3vr0PuqfLmAc&oq=queryse&gs_lp=Egxnd3Mtd2l6LXNlcnAiB3F1ZXJ5c2UqAggBMgsQABiABBixAxiDATIKEAAYgAQYQxiKBTIIEAAYgAQYsQMyChAAGIAEGEMYigUyChAAGIAEGEMYigUyBRAAGIAEMgoQABiABBhDGIoFMgUQABiABDIFEAAYgAQyBRAAGIAESI1bUABYizxwBXgBkAEAmAHxAqABjReqAQcwLjcuNS4xuAEDyAEA-AEBmAIRoAK2FagCEsICBBAjGCfCAgcQABiABBgEwgIKEAAYgAQYsQMYBMICDRAAGIAEGLEDGIMBGATCAhAQABiABBixAxiDARgEGIoFwgIQEAAYgAQYsQMYQxiDARiKBcICBxAjGCcY6gLCAhQQABiABBjjBBi0AhjpBBjqAtgBAcICEBAAGAMYtAIY6gIYjwHYAQHCAgoQIxiABBgnGIoFwgIMECMYgAQYExgnGIoFwgINEAAYgAQYsQMYQxiKBZgDCPEFwnXZImJsI1u6BgYIARABGAGSBwc1LjYuNS4xoAfwMQ&sclient=gws-wiz-serp
    // // https://zenn.dev/sugar/articles/36d17ef3464d13290f1f
    // // https://studio-babe.com/blog/programming/167/
    // // 画像選択時に表示されない！！
    // }
};

