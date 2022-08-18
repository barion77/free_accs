<?php 

namespace app\controllers;

use app\core\Controller;

class AccountApiController extends Controller
{
    public function index()
    {
        $accounts = $this->model->row('SELECT * FROM accounts');
        header('Content-Type: application/json');

        return json_encode($accounts);
    }

    public function store()
    {
        $params = json_decode(file_get_contents('php://input'), true);
        $this->model->query('INSERT INTO accounts (first_name, last_name, login, password, token, account_id, ip_address, followers, friends) VALUES (:first_name, :last_name, :login, :password, :token, :account_id, :ip_address, :followers, :friends)', $params);

        header('Content-Type: application/json');
        http_response_code(201);
        $response = [
            'status' => true,
            'account_id' => $this->model->lastInsertId(),
        ];

        return json_encode($response);
    }

    public function update()
    {
        $params = array_merge($this->parameters, json_decode(file_get_contents('php://input'), true));
        $this->model->query('UPDATE accounts SET first_name = :first_name, last_name = :last_name, login = :login, password = :password, token = :token, account_id = :account_id, ip_address = :ip_address, followers = :followers, friends = :friends WHERE id = :id', $params);

        header('Content-Type: application/json');
        $response = [
            'status' => true,
        ];

        return json_encode($response);
    }

    public function show()
    {
        $account = $this->model->row('SELECT * FROM accounts WHERE id = :id LIMIT 1', $this->parameters);

        header('Content-Type: application/json');

        return json_encode($account[0]);
    }

    public function delete()
    {
        $account = $this->model->query('DELETE FROM accounts WHERE id = :id', $this->parameters);

        header('Content-Type: application/json');
        $response = [
            'status' => true,
        ];

        return json_encode($response);
    }

}