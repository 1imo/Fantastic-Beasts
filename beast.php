<?php
class FantasticBeast {
    private $name;
    private $classification;
    private $description;

    public function __construct($name, $classification, $description) {
        $this->name = $name;
        $this->classification = $classification;
        $this->description = $description;
    }

    public function getName() {
        return $this->name;
    }

    public function getClassification() {
        return $this->classification;
    }

    public function getDescription() {
        return $this->description;
    }
}
?>