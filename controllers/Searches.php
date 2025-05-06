<?php
require_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../models/Course.php';

$keyword = trim(filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS));
$courseModel = new Course();

if (!empty($keyword)) {
    $courses = $courseModel->searchCourses($keyword);
} else {
    $courses = $courseModel->getAllCourses();
}

// 可将查询关键词传给视图用于显示或保留在搜索栏中
$searchKeyword = $keyword;

require_once __DIR__ . '/../views/search/index.php';