<?php

header("Content-Type: application/json");
require "../dbconnection.php";

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    /* =====================
       READ
    ===================== */
    case "GET":
        if (isset($_GET['task_id'])) {
            $stmt = $pdo->prepare(
                "SELECT * FROM tasks WHERE task_id = ?"
            );
            $stmt->execute([$_GET['task_id']]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($data);
        } else {
            $stmt = $pdo->query(
                "SELECT * FROM tasks ORDER BY task_id"
            );
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);
        }
        break;

    /* =====================
       CREATE
    ===================== */
    case "POST":
        $input = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare(
            "INSERT INTO tasks (task_name, status, user_id, project_id, due_date)
         VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $input['task_name'],
            $input['status'],
            $input['user_id'],
            $input['project_id'],
            $input['due_date']
        ]);
        echo json_encode(["message" => "Task created"]);
        break;

    /* =====================
       UPDATE
    ===================== */
    case "PUT":
        parse_str($_SERVER['QUERY_STRING'], $query);
        $input = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare(
            "UPDATE tasks
         SET task_name = ?, status = ?, user_id = ?, project_id = ?, due_date = ?
         WHERE task_id = ?"
        );
        $stmt->execute([
            $input['task_name'],
            $input['status'],
            $input['user_id'],
            $input['project_id'],
            $input['due_date'],
            $query['task_id']
        ]);
        echo json_encode(["message" => "Task updated"]);
        break;

    /* =====================
       DELETE
    ===================== */
    case "DELETE":
        parse_str($_SERVER['QUERY_STRING'], $query);
        $stmt = $pdo->prepare(
            "DELETE FROM tasks WHERE task_id = ?"
        );
        $stmt->execute([$query['task_id']]);
        echo json_encode(["message" => "Task deleted"]);
        break;

}
