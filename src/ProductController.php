<?php

class ProductController
{

    private ProductGateway $gateway;

    public function __construct(ProductGateway $gateway)
    {
        $this->gateway = $gateway;
    }


    // This method processes the request based on the HTTP method and product ID.
    public function processRequest(string $method, ?string $id): void
    {

        if ($id) {

            $this->processResourceRequest($method, $id);
        } else {

            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        if (empty($id) || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid or missing product ID']);
            return;
        }

        $product = $this->gateway->getProduct((int)$id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
            return;
        }

        switch ($method) {
            case 'GET':
                echo json_encode($product);
                break;

            case 'PATCH':
                $data = (array) json_decode(file_get_contents('php://input'), true);
                $errors = $this->getValidationErrors($data);
                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode([
                        'message' => 'Validation errors',
                        'errors' => $errors
                    ]);
                    return;
                }

                $rows = $this->gateway->update((int)$id, $data);
                echo json_encode(['message' => 'Product updated successfully']);

                break;
            case 'DELETE':
                $this->gateway->delete((int)$id);
                echo json_encode(['message' => 'Product deleted successfully']);
                break;

            default:
                http_response_code(405);
                header('Allow: GET, PATCH, DELETE');
                echo json_encode(['message' => 'Method not allowed']);
                break;
        }
    }

    private function processCollectionRequest(string $method): void
    {

        switch ($method) {
            case 'GET':
                echo json_encode($this->gateway->getAllProducts());
                break;

            case 'POST':
                $data = (array) json_decode(file_get_contents('php://input'), true);


                $errors = $this->getValidationErrors($data);
                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode([
                        'message' => 'Validation errors',
                        'errors' => $errors
                    ]);
                    return;
                }

                $id =  $this->gateway->create($data);
                echo json_encode([
                    'message' => 'Product created successfully',
                    'id' => $id
                ]);

                http_response_code(201);

                break;

            default:
                http_response_code(405);
                header('Allow: GET, POST');
        }
    }

    private function getValidationErrors(array $data): array
    {

        $errors = [];

        if (empty($data['title'])) {
            $errors[] = 'Title is required';
        }

        if (empty($data['price']) || !is_numeric($data['price'])) {
            $errors[] = 'Price is required and must be a number';
        }
        if (empty($data['qty']) || !is_numeric($data['qty'])) {
            $errors[] = 'Quantity is required and must be a number';
        }
        if (empty($data['inv_id']) || !is_numeric($data['inv_id'])) {
            $errors[] = 'Inventory ID is required and must be a number';
        }


        return $errors;
    }
}
