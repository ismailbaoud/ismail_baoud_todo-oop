<?php 
include_once "../../config/connectiondb.php";

class TaskController {
    private $db;
    private $task;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->task = new Task($this->db);
    }

    public function createTask() {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->task->title = $_POST['title'];
            $this->task->type = $_POST['type'];
            $this->task->status = 'todo'; // Default status
            $this->task->assignee = $_POST['assignee'];

            if($this->task->create()) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Task created successfully"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to create task"
                ]);
            }
        }
    }

    public function updateTaskStatus() {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $taskId = $_POST['taskId'];
            $newStatus = $_POST['status'];

            if($this->task->updateStatus($taskId, $newStatus)) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Task status updated"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Failed to update task status"
                ]);
            }
        }
    }

    public function getTasks() {
        $result = $this->task->getAllTasks();
        $tasks = [];
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = $row;
        }
        
        echo json_encode($tasks);
    }
}
?>