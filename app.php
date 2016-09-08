<?php

// loads class
function __autoload($class) {
   $class_name = strtolower($class);
   $path       = "src/{$class}.php";
   if (file_exists($path)) {
       require_once($path);
   } else {
       die("The file {$class}.php could not be found!");
   }
}

// initializes a new instance of Person class
$person = new Person("Jim", 16);
$personName = $person->getName();
echo "###################\r\n";
echo "object created for {$personName} \r\n";
var_dump(get_object_vars($person));
echo "\n";

// while true you will be returned to the initial prompt
$inUse = true;

do {
  //initial prompt
  echo "\n";
  echo "###########################\r\n";
  fwrite(STDOUT,  "Welcome to Object Edit, type help for commands: \r\n");
  $line = trim(fgets(STDIN));


  if ($line == "help" || $line == "HELP") {
    //HELP command
    fwrite(STDERR, "HELP : view commands list \n"
                   . "SET < propertyname=value > : adds a property with a value to loaded object \n"
                   . "GET < propertyname > : retive the value of the property \n"
                   . "GET * : Diplay Objects members and values \n"
                   . "EXIT : exit Object Edit \n");

  } else if ((preg_match("/^SET\s[a-z][a-zA-Z1-9]*\=[a-zA-Z1-9]*$/", $line))) {
    //SET propertyname=value command
    $propStr = substr($line, 4);
      if ((preg_match("/^[a-z][a-zA-Z1-9]*\=[a-zA-Z1-9]*$/", $propStr))) {
        $newProp = explode("=", $propStr);
        $person->$newProp[0] = $newProp[1];
      }
  } else if ((preg_match("/^GET\s[a-z][a-zA-Z1-9]*$/", $line))) {
    //GET propertyname command
      $propStr = substr($line, 4);
      $getProp = "The value of property {$propStr} is {$person->$propStr} \r\n";
      echo $getProp;
  } else if ((preg_match("/^GET\s\*$/", $line))) {
    //GET * command
      var_dump(get_object_vars($person));
  } else if ($line == "exit" || $line == "EXIT") {
    //EXIT command
      $inUse = false;
      exit(0);
  } else {
    // no condtions met, program ends with error message
      die("I am sorry, but that is not a vaild command. \r\n");
  }

} while ($inUse === true);

?>
