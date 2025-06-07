<?php

class ProductController {
    // This method processes the request based on the HTTP method and product ID.
    public function processRequest(string $method, ?string $id): void  {
        
        if($id) {

            $this->processResourceRequest($method, $id);
        } else {
            
            $this->processCollectionRequest($method);
       
        }
    }

    private function processResourceRequest(string $method, string $id): void {
        
    }

    private function processCollectionRequest(string $method): void {

        switch ($method) {
            case 'GET':
                echo json_encode([
                    ['id' => 1, 'name' => 'Product 1', 'price' => 10.99],
                    ['id' => 2, 'name' => 'Product 2', 'price' => 12.99],
                    ['id' => 3, 'name' => 'Product 3', 'price' => 15.99]
                ]);
                break;
            case 'POST':
                //
        }
    }
}