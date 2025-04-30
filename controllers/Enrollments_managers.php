<?php
require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../models/Enrollment.php';
const permiteeID = 2;//permitee role is manager

//僅允許已登入使用者操作
if (!isLoggedIn()){
    redirect('/CISC3003-ProjectAssignment/views/login.php');
}elseif(!isPermitee(permiteeID)){
    flash('Access Denied', 'You do not have permission to access this page.');
    // redirect('/CISC3003-ProjectAssignment/views/manager/login.php');
    exit();
}


?>