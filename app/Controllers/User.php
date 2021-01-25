<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class User extends ResourceController{
    use ResponseTrait;

    protected $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function createUser(){
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $this->model->insert($input);
        $response = [
            "message" => "user created successfully"
        ];
        echo $response;
        return $this->respondCreated($response);
    }

    public function getAllUsers() {
        try{
            $data['users'] = $this->model->orderBy('id', 'asc')->findAll();
            return $this->respond($data);
        } catch(Exception $e){
            $errorMessage = $e->getMessage();
            die($errorMessage);
        }
        
    }

    public function getUser($id = null) {
        
        $data = $this->model->getWhere(['id' => $id ])->getResult();
        if($data) {
            return $this->respond($data);
        }

        return $this->failNotFound('No user found');
    }

    public function updateUser($id = null){
        // $input = $this->request->getRawInput();
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $data = [
            'surname' => $input['surname'],
            'firstName' => $input['firstName'],
            'email' => $input['email']
        ];
        $this->model->update($id, $data);
        $response = [
            'message' => 'user updated successfully'
        ];
        return $this->respond($response);
    }

    public function deleteUser($id){
        $data = $this->model->find($id);
        if($data){
            $this->model->delete($id);
            $response = [
                'message' => 'user deleted successfully'
            ];
            return $this->respond($response);
        }

        return $this->failNotFound('user does not exist');
    }
}