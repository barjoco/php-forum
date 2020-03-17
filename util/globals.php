<?

function send_error($err_msg, $destination) {
    $_SESSION['err'] = $err_msg;
    header("Location: $destination");
    exit(1);
}

function send_success($success_msg, $destination) {
    $_SESSION['success'] = $success_msg;
    header("Location: $destination");
    exit(0);
}

function redirect($destination) {
    header("Location: $destination");
    exit(0);
}

function get_p() {
    return (isset($_GET['p']) && preg_match('/^\d+$/',$_GET['p']) && $_GET['p']>0) ? $_GET['p'] : 1;
}

function get_l() {
    return (isset($_GET['l']) && preg_match('/^\d+$/',$_GET['l']) && $_GET['l']>0) ? $_GET['l'] : 10;
}

function get_c() {
    return (isset($_GET['c']) && preg_match('/^(\d,?)+$/',$_GET['c'])) ? explode(',', $_GET['c']) : null;
}

function get_u() {
    if (session_status() == PHP_SESSION_NONE) session_start();
    return (isset($_GET['u']) && preg_match('/^\d+$/',$_GET['u'])) ? $_GET['u'] : $_SESSION['uid'];
}