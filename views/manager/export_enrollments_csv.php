<?php
require_once __DIR__ . '/../../models/Enrollment.php';

$enrollmentModel = new Enrollment();
$enrollments = $enrollmentModel->getEnrollments();

// 设置 HTTP 头部，用于下载 CSV 文件
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=enrollments.csv');

// 打开 php://output 流
$output = fopen('php://output', 'w');
// 输出表头
fputcsv($output, ['Student ID', 'Student Name', 'Course Code', 'Course Name', 'Enrollment Date', 'Status']);

// 循环 enrollments 并写入 CSV
foreach ($enrollments as $enrollment) {
    fputcsv($output, [
        $enrollment->userID,
        ucfirst($enrollment->userName),
        $enrollment->course_code,
        $enrollment->course_name,
        $enrollment->createdAt,
        ucfirst($enrollment->status)
    ]);
}

fclose($output);
exit();