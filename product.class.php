<?php

class Product
{

    protected $name;
    protected $price;

    public function __construct($name = null, $price = null, $category = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($newPrice)
    {
        $this->price = $newPrice;
    }

    public function getHTML()
    {
        return "<div><h2>" . $this->name . "</h2><p class='price'>" . $this->price . "</p></div>";
    }
}
