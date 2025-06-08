<?php

class ProductGateway
{

    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAllProducts(): array
    {
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

    public function create(array $data): string
    {
        $sql = "INSERT INTO articles (title, price, qty, inv_id) VALUES (:title, :price, :qty, :inv_id)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_STR);
        $stmt->bindValue(':qty', $data['qty'], PDO::PARAM_INT);
        $stmt->bindValue(':inv_id', $data['inv_id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Failed to create product: " . implode(", ", $stmt->errorInfo()));
        }

        return $this->conn->lastInsertId();
    }

    public function getProduct(string $id): array
    {

        $sql = "SELECT * FROM articles WHERE art_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Failed to retrieve product: " . implode(", ", $stmt->errorInfo()));
        }

        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) {
            throw new Exception("Product not found");
        }

        return $product;
    }

    public function update(string $id, array $data): void
    {
        $sql = "UPDATE articles SET title = :title, price = :price, qty = :qty, inv_id = :inv_id WHERE art_id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $data['price'], PDO::PARAM_STR);
        $stmt->bindValue(':qty', $data['qty'], PDO::PARAM_INT);
        $stmt->bindValue(':inv_id', $data['inv_id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update product: " . implode(", ", $stmt->errorInfo()));
        }

        if ($stmt->rowCount() === 0) {
            throw new Exception("Product not found or no changes made");
        }
    }

    public function delete(string $id): void
    {
        $sql = "DELETE FROM articles WHERE art_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Failed to delete product: " . implode(", ", $stmt->errorInfo()));
        }

        if ($stmt->rowCount() === 0) {
            throw new Exception("Product not found");
        }
    }
}
