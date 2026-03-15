<?php
require_once "../database/connect.php";
abstract class Model {
    protected $conn;
    public function __construct($pdo) {
        $this->conn = $pdo;
    }
}

class Task extends Model{
    protected $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // ---------------- Retrieve ----------------
    public function RetrieveTasks($task_id = null){
        if ($task_id !== null){
                $stmt = $this->conn->prepare(
                    "SELECT * FROM tasks
                     WHERE task_id = ?"
                );
            $stmt->execute([$task_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else {
            $stmt = $this->conn->prepare("SELECT * FROM tasks");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }}
    

    // ---------------- Create ----------------
public function CreateTask($task_name = null, $status = null, $user_id = null, $project_id = null, $due_date = null){
    if ($task_name === null || $status === null || $user_id === null || $project_id === null || $due_date === null){
        return false;
    }

    $stmt = $this->conn->prepare(
        "INSERT INTO tasks (task_name, status, user_id, project_id, due_date)
         VALUES (?, ?, ?, ?, ?)"
    );
    if (!$stmt) return false;
    return $stmt->execute([$task_name, $status, $user_id, $project_id, $due_date]);
}
    // ---------------- Update ----------------
public function UpdateTask($task_id = null, $task_name = null, $status = null, $user_id = null, $due_date = null){
    if ($task_id === null || $task_name === null || $status === null || $user_id === null || $due_date === null) return false;

    $stmt = $this->conn->prepare(
        "UPDATE tasks
         SET task_name = ?, status = ?, user_id = ?, due_date = ?
         WHERE task_id = ?"
    );
    if (!$stmt) return false;
    return $stmt->execute([$task_name, $status, $user_id, $due_date, $task_id]);
}

    // ---------------- Delete ----------------
    public function DeleteTask($task_id = null){
        if (!$task_id) return false;

        $stmt = $this->conn->prepare(
            "DELETE FROM tasks WHERE task_id = ?"
        );
        if (!$stmt) return false;

        $success = $stmt->execute([$task_id]);
        return ($success && $stmt->rowCount() > 0);
    }
}

?>



