<?php


class Product
{
    public $id;
    public $name;
    public $picture;
    public $description;
    public $category_id;

    public function __construct()
    {
        settype($this->id, 'integer');
        settype($this->category_id, 'integer');
    }

    public function getProductsByCategoryId($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = :id");
        $stmt->execute(['id' => filter_var($id, FILTER_SANITIZE_NUMBER_INT)]);
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $products;
    }

    public function getProductById($id)
    {
        global $pdo;
        $statement = $pdo->prepare('SELECT * FROM products WHERE products.id = :id');
        $statement->execute(['id' => filter_var($id, FILTER_SANITIZE_NUMBER_INT)]);
        $product = $statement->fetch(PDO::FETCH_OBJ);
        if (!$product) {
            return false;
        }
        $this->id = $product->id;
        $this->name = $product->name;
        $this->picture = $product->picture;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
        return $this;
    }

    public function update()
    {

        global $pdo;
        $statement = $pdo->prepare('UPDATE products SET name = :name, picture = :picture, description = :description, category_id = :category_id WHERE id = :id');
        $statement->execute([
            'name' => filter_var($this->name, FILTER_SANITIZE_STRING),
            'picture' => filter_var($this->picture, FILTER_SANITIZE_STRING),
            'description' => filter_var($this->description, FILTER_SANITIZE_STRING),
            'category_id' => filter_var($this->category_id, FILTER_SANITIZE_NUMBER_INT),
            'id' => filter_var($this->id, FILTER_SANITIZE_NUMBER_INT)
        ]);
        return $statement->rowCount() > 0;

    }

    public function delete()
    {
        global $pdo;
        $statement = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $statement->execute(['id' => filter_var($this->id, FILTER_SANITIZE_NUMBER_INT)]);
        return $statement->rowCount() > 0;
    }

    public function create()
    {
        global $pdo;
        $statement = $pdo->prepare('INSERT INTO products (name, picture, description, category_id) VALUES (:name, :picture, :description, :category_id)');
        $statement->execute([
            'name' => filter_var($this->name, FILTER_SANITIZE_STRING),
            'picture' => filter_var($this->picture, FILTER_SANITIZE_STRING),
            'description' => filter_var($this->description, FILTER_SANITIZE_STRING),
            'category_id' => filter_var($this->category_id, FILTER_SANITIZE_NUMBER_INT)
        ]);
        return $statement->rowCount() > 0;
    }


}