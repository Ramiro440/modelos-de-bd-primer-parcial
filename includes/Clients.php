<?php
$post = json_decode(file_get_contents('php://input'), true);
require_once '_db.php';

if ($post) {
    $clients = new Clients();
    switch ($post['action']) {
        case 'insert':
            $clients->insertData($post);
            break;
        case 'delete':
            $clients->deleteData($post['id']);
            break;
        case 'selectOne':
            $clients->getOneData($post);
            break;
        case 'update':
            $clients->updateData($post);
            break;
    }
}

class Clients
{
    public function getAllData()
    {
        global $mysqli;
        $query = "SELECT clients.id, clients.name, clients.email, clients.phone, clients.active FROM clients";
        return $mysqli->query($query);
    }

    public function getOneData($post)
    {
        $id = $post['id'];
        global $mysqli;
        $query = "SELECT * FROM clients WHERE id = $id";
        $result = $mysqli->query($query);
        echo json_encode($result->fetch_object());
    }

    public function updateData($post)
    {
        $name = $post['name'];
        $email = $post['email'];
        $phone = $post['phone'];
        $status = $post['status'];
        $id = $post['id'];

        $query = "UPDATE clients SET name = '$name', email = '$email', phone = '$phone', active = '$status' WHERE id = $id";

        global $mysqli;
        $mysqli->query($query);

        $response = [
            "message" => "No se pudo editar el registro en la base de datos",
            "status" => 1
        ];
        if ($mysqli->affected_rows > 0) {
            $response = [
                "message" => "Se editó correctamente el usuario de " . $name,
                "status" => 2
            ];
        }
        echo json_encode($response);
    }

    public function insertData($data)
    {
        global $mysqli;
        $name = $mysqli->real_escape_string($data['name']);
        $email = $mysqli->real_escape_string($data['email']);
        $phone = $mysqli->real_escape_string($data['phone']);
        $active = (int)$data['status'];
        $query = "INSERT INTO clients (name, email, phone, active) VALUES ('$name', '$email', '$phone', '$active')";
        $mysqli->query($query);
        $response = [
            "message" => "No se pudo almacenar el registro en la base de datos",
            "status" => 0
        ];
        if ($mysqli->insert_id != 0) {
            $response = [
                "message" => "Se registró correctamente el Cliente de " . $name,
                "status" => 1
            ];
        }
        echo json_encode($response);
    }

    public function deleteData($id)
    {
        global $mysqli;
        $id = (int)$id;
        $query = "DELETE FROM clients WHERE id = $id";
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
?>
