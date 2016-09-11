<?php
	Class Cat
	{
    public $name;
    public $age;


    function __construct($name, $age)
    {
       $this->name = (String) $name;
       $this->age = (Integer) $age;
    }

    function setName($new_name) {
      $this->name = (String) $new_name;
    }

    function getName() {
      return $this->name;
    }

    function setAge($new_age) {
      $this->age = (Integer) $new_age;
    }

    function getAge() {
      return $this->age;
    }


  }
?>
