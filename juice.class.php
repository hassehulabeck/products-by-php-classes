<?php

class Juice extends Product
{
    private $flavor;

    public function __construct($name, $price, $category, $flavor)
    {

        parent::__construct($name, $price, $category);
        $this->flavor = $flavor;
    }
    public function getFlavor()
    {
        return $this->flavor;
    }
    public function getHTML()
    {
        return "<div><h2>" . $this->name . "</h2><p class='price'>" . $this->price . "</p><p class='flavor'>Smak: <span>" . $this->flavor . "</span></div>";
    }
}
