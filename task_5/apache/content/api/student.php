<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php";
include_once "../objects/student.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $database = new Database();
    $db = $database->getConnection();

    $student = new Student($db);

    $query_result = $student->read();

    $result = array("results" => array());
    foreach ($query_result as $student) {
        $students_obj = array(
            "id" => $student["id"],
            "name" => $student["name"],
            "surname" => $student["surname"]
        );
        $result["results"][] = $students_obj;
    }

    http_response_code(200);
    echo json_encode($result);
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $student = new Student($db);

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->name) &&
        !empty($data->surname)
    ) {
        $student->name = $data->name;
        $student->surname = $data->surname;

        $stmt = $student->create();

        if ($stmt) {
            http_response_code(201);
            echo json_encode(array("message" => "Студент добавлен"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно добавить студента"), JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно добавить студента: данные неполные"), JSON_UNESCAPED_UNICODE);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    $database = new Database();
    $db = $database->getConnection();

    $student = new Student($db);

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->id) &&
        !empty($data->name) &&
        !empty($data->surname)
    ) {
        $student->id = $data->id;
        $student->name = $data->name;
        $student->surname = $data->surname;

        $stmt = $student->update();

        if ($stmt) {
            http_response_code(201);
            echo json_encode(array("message" => "Данные студента обновлены"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно обновить данные студента"), JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно обновить данные: данные неполные"), JSON_UNESCAPED_UNICODE);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $database = new Database();
    $db = $database->getConnection();

    $student = new Student($db);

    if (!isset($_GET["id"])) {
        http_response_code(400);
        echo json_encode(array("message" => "Неправильный запрос: не указан ID студента"));
    } else {
        $student->id = $_GET["id"];
        $stmt = $student->delete();
        if ($stmt) {
            http_response_code(200);
            echo json_encode(array("message" => "Студент удалён"));
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Студент с таким ID не существует"));
        }
    }
}