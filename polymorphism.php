<?php



class Shape {
    public function area() {
        return 0;
    }
}

class Rectangle extends Shape {
    private $length;
    private $width;

    public function __construct($length, $width) {
        $this->length = $length;
        $this->width = $width;
    }

    public function area() {
        return $this->length * $this->width;
    }
}

class Circle extends Shape {
    private $radius;

    public function __construct($radius) {
        $this->radius = $radius;
    }

    public function area() {
        return pi() * ($this->radius ** 2);
    }
}

$shapes = [new Rectangle(5, 10), new Circle(7)];

foreach ($shapes as $shape) {
    echo $shape->area() . "\n"; // Outputs area of each shape
}



?>
