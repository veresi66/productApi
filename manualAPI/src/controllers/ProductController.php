<?php

namespace controllers;

use models\Product;

class ProductController
{
    private Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $method
     * @param string|null $id
     * @return void
     */
    public function processRequest(string $method, ?string $id) : void
    {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    /**
     * @param string $method
     * @param string $id
     * @return void
     */
    private function processResourceRequest(string $method, string $id) : void
    {
        $product = $this->model->get($id);

        if (!$product) {
            http_response_code(404);
            echo json_encode(["message" => "Product not found"]);
            return;
        }

        switch ($method) {
            case "GET" :
                echo json_encode($product);
                break;
            case "PUT" :
            case "PATCH" :
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data, false);

                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $rows = $this->model->update($product, $data);

                echo json_encode([
                    "message" => "Product $id updated!",
                    "rows" => $rows
                ]);
                break;
            case "DELETE" :
                $rows = $this->model->delete($id);
                echo json_encode([
                    "message" => "Product $id deleted!",
                    "rows" => $rows
                ]);
                break;
            default :
                http_response_code(405);
                header("Allow: GET, PUT, PATCH, DELETE");
        }
    }

    /**
     * @param string $method
     * @return void
     */
    private function processCollectionRequest(string $method) : void
    {
        switch ($method) {
            case "GET" :
                echo json_encode($this->model->getAll());
                break;

            case "POST" :
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $id = $this->model->create($data);

                http_response_code(201);
                echo json_encode([
                    "message" => "Product created!",
                    "id" => $id
                ]);
                break;
            default :
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }

    /**
     * Data validation
     * @param array $data
     * @param bool $isNew
     * @return array
     */
    private function getValidationErrors(array $data, bool $isNew = true) : array
    {
        $errors = [];

        if ($isNew && empty($data["name"])) {
            $errors[] = "Name is required";
        }

        if (array_key_exists("price", $data)) {
            if (filter_var($data["price"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "Price must be an integer!";
            }
        }

        return $errors;
    }
}
