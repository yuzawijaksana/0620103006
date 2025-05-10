<!DOCTYPE html>
<?php
    session_start();

    // Jika 'tasks' belum ada, inisialisasi dengan daftar tugas default
    if (!isset($_SESSION['tasks'])) {
        $_SESSION['tasks'] = [
            ["id" => 1, "title" => "Belajar PHP", "status" => "belum"],
            ["id" => 2, "title" => "Kerjakan tugas UX", "status" => "selesai"]
        ];
    }

    $tasks = $_SESSION['tasks'];

    // Jika ada permintaan POST untuk menambahkan tugas baru
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
        $newTask = [
            "id" => count($tasks) + 1,
            "title" => htmlspecialchars($_POST['task']),
            "status" => "belum"
        ];
        $tasks[] = $newTask;
        $_SESSION['tasks'] = $tasks;
    }

    // Jika ada permintaan GET untuk menghapus tugas berdasarkan ID
    if (isset($_GET['delete'])) {
        $deleteId = (int)$_GET['delete'];
        $tasks = array_filter($tasks, function($task) use ($deleteId) {
            return $task['id'] !== $deleteId;
        });
        $_SESSION['tasks'] = array_values($tasks);
    }

    // Fungsi untuk menampilkan daftar tugas
    function tampilkanDaftar($tasks) {
        foreach ($tasks as $task) {
            echo "<li class='d-flex justify-content-between align-items-center'>";
            echo "<div class='form-check'>";
            echo "<input type='checkbox' class='form-check-input' id='task-" . $task['id'] . "' " . ($task['status'] == 'selesai' ? 'checked' : '') . ">";
            echo "</div>";
            echo "<div class='task-title' style='border: 1px solid #ccc; padding: 10px; border-radius: 5px; word-wrap: break-word; white-space: normal;'>" . $task['title'] . "</div>";
            echo "<a href='?delete=" . $task['id'] . "' class='btn btn-danger btn-sm'>Hapus</a>";
            echo "</li>";
        }
    }

    // Jika ada permintaan GET untuk mereset aplikasi ke state default
    if (isset($_GET['reset'])) {
        session_destroy();
        header("Location: index.php");
        exit;
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi To-do List</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
<body class="text-center">
    <div class="container">
        <div class="card-header text-center">
            <h1>Tugas Baru</h1>
        </div>
        <div class="card-body">
            <form action="index.php" method="POST">
                <input type="text" name="task" placeholder="Tulis tugas baru..." required>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
        <ul>
            <?php
                tampilkanDaftar($tasks);
            ?>
        </ul>
        <a href="?reset=true" class="btn btn-warning">Kembali ke Default State</a>
    </div>
    <footer class="text-center">
        <p>&copy; 2025 Yuza Wijaksana Aplikasi To-do List</p>
    </footer>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>