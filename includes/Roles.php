<?php
$post = json_decode(file_get_contents('php://input'), true);
require_once '_db.php';

if ($post) {
  switch ($post['action']) {
    case 'insert':
      $roles = new Roles();
      $roles->insertData($post);
      break;
    case 'delete':
      $roles = new Roles();
      $roles->deleteData($post);
      break;
    case 'selectOne':
      $roles = new Roles();
      $roles->getOneData($post);
      break;
    case 'update':
      $roles = new Roles();
      $roles->updateData($post);
      break;
  }
}
class Roles
{
  public function getAllData()
  {
    global $mysqli;
    $query = "SELECT * FROM roles";
    return $mysqli->query($query);
  }

  public function insertData($data)
  {
    global $mysqli;
    $nombre = $data['name'];
    $status = $data['status'];
    $query =  "INSERT INTO roles (name, active) VALUES ('$nombre', '$status')";
    $mysqli->query($query);
    $response = [
      "message" => "No se pudo almacenar el registro en la base de datos",
      "status" => 0
    ];
    if ($mysqli->insert_id != 0) {
      $response = [
        "message" => "Se registró correctamente el rol de " . $nombre,
        "status" => 1
      ];
    }
    echo json_encode($response);
  }

  public function deleteData($id)
  {
    global $mysqli;
    $id = (int) $id;
    $query = "DELETE FROM roles WHERE id = $id";
    $mysqli->query($query);
    $response = [
      "message" => "No se pudo eliminar el registro",
      "status" => 0
    ];
    if ($mysqli->affected_rows > 0) {
      $response = [
        "message" => "Se eliminó correctamente el Cliente",
        "status" => 1
      ];
    }
    echo json_encode($response);
  }
}

if ($post && $post['action'] == 'insert') {
  $roles = new Roles();
  $roles->insertData($post);
}


