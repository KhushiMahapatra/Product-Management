<?php
class Product {
    private $name;
    private $category;
    private $price;
    private $active;
    private $images = [];

    public function __construct($name, Category $category, $price) {
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->active = true;
    }

    // Add an image to the product
    public function addImage($image) { 
        if (is_string($image) && !empty($image)) {
            $this->images[] = $image;
        }
    }

    // Remove an image from the product
    public function removeImage($image) {
        if (($key = array_search($image, $this->images)) !== false) {
            unset($this->images[$key]);
        }
    }

    // Activate the product
    public function activate() {
        $this->active = true;
    }

    // Deactivate the product
    public function deactivate() {
        $this->active = false;
    }

    // Getters
    public function getName() {
        return $this->name;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getPrice() {
        return $this->price;
    }

    public function isActive() {
        return $this->active;
    }

    // Get all images
    public function getImages() {
        return $this->images;
    }

    // Convert product to string representation
    public function __toString() {
        return "Product(name={$this->name}, category={$this->category->getName()}, price={$this->price}, active={$this->active})";
    }
}
?>