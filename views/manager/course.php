<?php
// views/manager/course.php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Course.php';
require_once __DIR__ . '/../../models/User.php';

if(!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
    $_SESSION['error'] = 'You must be logged in as an administrator or manager to access this page!';
    header('Location: login.php');
    exit();
}

$db = new Database();
$courseModel = new Course();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete' && isset($_POST['course_id'])) {
            if ($courseModel->deleteCourse($_POST['course_id'])) {
                $_SESSION['success'] = 'Course deleted successfully!';
            } else {
                $_SESSION['error'] = 'Error deleting course!';
            }
            header('Location: course.php');
            exit();
        }
        
        if ($_POST['action'] === 'update' && isset($_POST['course_id'])) {
            $data = [
                'course_id' => $_POST['course_id'],
                'course_code' => trim($_POST['course_code']),
                'course_name' => trim($_POST['course_name']),
                'teacher' => trim($_POST['teacher']),
                'schedule' => trim($_POST['schedule'])
            ];
            
            if ($courseModel->updateCourse($data)) {
                $_SESSION['success'] = 'Course successfully updated!';
            } else {
                $_SESSION['error'] = 'Error updating course!';
            }
            header('Location: course.php');
            exit();
        }
    } else {
        $data = [
            'course_code' => trim($_POST['course_code']),
            'course_name' => trim($_POST['course_name']),
            'teacher'     => trim($_POST['teacher']),
            'schedule'    => trim($_POST['schedule'])
        ];

        if ($courseModel->createCourse($data)) {
            $_SESSION['success'] = 'Course successfully created!';
        } else {
            $_SESSION['form_data'] = $data;
        }
        header('Location: course.php');
        exit();
    }
}

$editCourse = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $editCourse = $courseModel->getCourseById($_GET['edit']);
}

$courses = $courseModel->getAllCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Management</title>
    <link rel="stylesheet" href="../../public/css/courses.css">
    <style>
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn-edit, .btn-delete {
            padding: 2px 8px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .btn-cancel {
            background-color: #ccc;
        }
        
        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .logout-btn {
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info">
            <div>
                <strong>Welcome!<?= htmlspecialchars($_SESSION['username']) ?></strong> 
                (<?= $_SESSION['role_id'] == 1 ? 'admin' : 'manager' ?>)
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']) ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']) ?></div>
        <?php endif; ?>

        <?php if ($editCourse): ?>
   
        <form method="post" class="course-form">
            <h2>Edit Course</h2>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="course_id" value="<?= $editCourse->course_id ?>">
            
            <div class="form-group">
                <label for="course_code">Course Code:</label>
                <input type="text" id="course_code" name="course_code" 
                       value="<?= htmlspecialchars($editCourse->course_code) ?>" required>
            </div>

            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name"
                       value="<?= htmlspecialchars($editCourse->course_name) ?>" required>
            </div>

            <div class="form-group">
                <label for="teacher">Teacher:</label>
                <input type="text" id="teacher" name="teacher"
                       value="<?= htmlspecialchars($editCourse->teacher) ?>" required>
            </div>

            <div class="form-group">
                <label for="schedule">Time Schedule:</label>
                <input type="text" id="schedule" name="schedule"
                       value="<?= htmlspecialchars($editCourse->schedule) ?>" required>
            </div>

            <button type="submit" class="btn-submit">Update Course</button>
            <a href="course.php" class="btn-submit btn-cancel">Cancel</a>
        </form>
        <?php else: ?>
        <form method="post" class="course-form">
            <h2>Create New Course</h2>
            
            <div class="form-group">
                <label for="course_code">Course Code:</label>
                <input type="text" id="course_code" name="course_code" 
                       value="<?= $_SESSION['form_data']['course_code'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name"
                       value="<?= $_SESSION['form_data']['course_name'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="teacher">Teacher:</label>
                <input type="text" id="teacher" name="teacher"
                       value="<?= $_SESSION['form_data']['teacher'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label for="schedule">Time Schedule:</label>
                <input type="text" id="schedule" name="schedule"
                       value="<?= $_SESSION['form_data']['schedule'] ?? '' ?>" required>
            </div>

            <button type="submit" class="btn-submit">Create Course</button>
        </form>
        <?php endif; ?>

        <div class="course-list">
            <h2>Current Course List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Teacher</th>
                        <th>Schedule</th>
                        <th>Created Time</th>
                        <th>Edit / Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course) : ?>
                        <tr>
                            <td><?= htmlspecialchars($course->course_code) ?></td>
                            <td><?= htmlspecialchars($course->course_name) ?></td>
                            <td><?= htmlspecialchars($course->teacher) ?></td>
                            <td><?= htmlspecialchars($course->schedule) ?></td>
                            <td><?= isset($course->created_at) ? date('Y-m-d H:i', strtotime($course->created_at)) : 'unknown' ?></td>
                            <td class="action-buttons">
                                <a href="course.php?edit=<?= $course->course_id ?>" class="btn-edit">Edit</a>
                                <form method="post" style="display:inline;" onsubmit="return confirm('are you sure delete this course?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="course_id" value="<?= $course->course_id ?>">
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php unset($_SESSION['form_data']); ?>
