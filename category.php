<?php
class Category {
    private $name;
    private $parent;
    private $subcategories = [];

    public function __construct($name, $parent = null) {
        $this->name = $name;
        $this->parent = $parent;
    }

    public function addSubcategory(Category $subcategory) {
        $this->subcategories[] = $subcategory;
    }

    public function getName() {
        return $this->name;
    }

    public function getSubcategories() {
        return $this->subcategories;
    }
}
?>