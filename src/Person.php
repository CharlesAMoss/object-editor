<?php
	Class Person
	{
    public $name;
    public $age;
		public $isCool;

    function __construct($name, $age, $isCool)
    {
       $this->name = (string) $name;
       $this->age = (integer) $age;
			 $this->isCool = (bool) $isCool;
    }

    function setName($new_name) {
      $this->name = (string) $new_name;
    }

    function getName() {
      return $this->name;
    }

    function setAge($new_age) {
      $this->age = (integer) $new_age;
    }

    function getAge() {
      return $this->age;
    }

		function setIsCool($new_isCool) {
      $this->isCool = (integer) $new_isCool;
    }

    function getIsCool() {
      return $this->isCool;
    }

  }
?>
