document.addEventListener('DOMContentLoaded', (event) => {

    let pathname = window.location.pathname.replace('/walk_hiroshima/', '');

    switch (pathname) {
        case "index.php":
            index();
            break;
    }
});

// ログアウト処理
function CheckLogout() {
    if (confirm('ログアウトします')) {
        return true;
    } else {
        return false;
    }
}

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
    for(let i = 0; i < mainImageChildren.length; i++) {
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
        for(let i = 0; i < mainImageChildren.length; i++) {
            mainImageChildren[i].addEventListener('click', (event) => {
                naviMenu.classList.remove('display_none');
                logIn.classList.add('display_none');
            });
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
        for(let i = 0; i < mainImageChildren.length; i++) {
            mainImageChildren[i].addEventListener('click', (event) => {
                naviMenu.classList.remove('display_none');
                createAccount.classList.add('display_none');
            });    
        }
    }
}
