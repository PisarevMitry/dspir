<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/database.php";
include_once "../objects/course.php";

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $database = new Database();
    $db = $database->getConnection();

    $course = new Course($db);

    $query_result = $course->read();

    $result = array("results" => array());
    foreach ($query_result as $course) {
        $courses_obj = array(
            "id" => $course["id"],
            "name" => $course["name"],
            "description" => $course["description"]
        );
        $result["results"][] = $courses_obj;
    }

    http_response_code(200);
    echo json_encode($result);
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $course = new Student($db);

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->name) &&
        !empty($data->description)
    ) {
        $course->name = $data->name;
        $course->description = $data->description;

        $stmt = $course->create();

        if ($stmt) {
            http_response_code(201);
            echo json_encode(array("message" => "Курс создан"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно добавить курс"), JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно добавить курс: данные неполные"), JSON_UNESCAPED_UNICODE);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {

    $database = new Database();
    $db = $database->getConnection();

    $course = new Course($db);

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->id) &&
        !empty($data->name) &&
        !empty($data->description)
    ) {
        $course->id = $data->id;
        $course->name = $data->name;
        $course->description = $data->description;

        $stmt = $course->update();

        if ($stmt) {
            http_response_code(201);
            echo json_encode(array("message" => "Данные курса обновлены"), JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Невозможно обновить данные курса"), JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Невозможно обновить данные: данные неполные"), JSON_UNESCAPED_UNICODE);
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $database = new Database();
    $db = $database->getConnection();

    $course = new Course($db);

    if (!isset($_GET["id"])) {
        http_response_code(400);
        echo json_encode(array("message" => "Неправильный запрос: не указан ID курса"));
    } else {
        $course->id = $_GET["id"];
        $stmt = $course->delete();
        if ($stmt) {
            http_response_code(200);
            echo json_encode(array("message" => "Курс удалён"));
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Курс с таким ID не существует"));
        }
    }
}