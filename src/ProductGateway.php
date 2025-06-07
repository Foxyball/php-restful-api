<?php

class ProductGateway{

    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAllProducts(): array {
        $sql = "SELECT * FROM articles";
        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row; // for all columns
            // If we want to select specific columns, we can do it like this:
            $data[] = [
                'art_id' => $row['art_id'],
                'title' => $row['title'],
                'price' => $row['price'],
                'qty' => $row['qty'],
                'inv_id' => $row['inv_id']
            ];
        }

        return $data;
    }
}
