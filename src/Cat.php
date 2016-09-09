<?php
	Class Cat
	{
    public $name;
    Public $age;

    function __construct($name, $age)
    {
       $this->name = (String) $name;
       $this->age = (Integer) $age;
    }

    function setName($new_name) {
      $this->Name = (String) $new_name;
    }

    function getName() {
      return $this->name;
    }

    function setAge($new_age) {
      $this->Age = (String) $new_age;
    }

    function getAge() {
      return $this->age;
    }


  }
?>
