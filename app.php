<?php

// loads class
function __autoload($class) {
   $path = "src/{$class}.php";
   if (file_exists($path)) {
        require_once($path);
   } else {
        die("The file {$class}.php could not be found!");
   }
}

// creates an array of the names of the classes stored in the src dir
$dirSrc = scandir("src/",1);
$dirSrc = array_slice($dirSrc, 0, -2);
$classList = array();
foreach ($dirSrc as $key => $value) {
  $className = substr($value, 0, -4);
  array_push($classList, $className);
}

// while true you will be returned to the initial prompt
$inUse = true;
// if $requestedClass length is less than 1, user will be ask for a class
$requestedClass = "";

do {
  //initial prompt
  if (strlen($requestedClass) == 0) {
    // displys a list of classes from the src folder for user to select from
    $avaibleClasses = implode(" ", $classList);
    echo "\n";
    $requestClass = "Welcome to Object Edit! "
                    . "Chooose a class to instnce your "
                    . "object :\r\n\033[33m{$avaibleClasses} \033[0m \r\n";
    fwrite(STDOUT, $requestClass );
    $requestedClass = trim(fgets(STDIN));
    $requestedClass = ucfirst($requestedClass);

    if (strlen($requestedClass) > 0 && in_array($requestedClass, $classList)) {
      // requests arguments from user
      echo "\n";
      $requestArguments = "Please list the arguments for \033[33m{$requestedClass}\033[0m"
                          . "\033[31m comma,sepertated,no,spaces\033[0m format.\r\n"
                          . "In the case of no arguments leave blank\r\n";
      fwrite(STDOUT, $requestArguments);
      $classArguments = trim(fgets(STDIN));
      $args = explode(",", $classArguments);
    } else {
      die("The class \033[33m{$requestedClass}\033[0m could not be found!\r\n");
    }

    // instances the object and displays print it to terminal
    $rc = new ReflectionClass($requestedClass);
    $obj = $rc->newInstanceArgs( $args );
    var_dump(get_object_vars($obj));

  }

  //command prompt
  echo "\n";
  echo "\n";
  fwrite(STDOUT,  "Type HELP for commands list.\r\n");
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
        $obj->$newProp[0] = $newProp[1];
      }
  } else if ((preg_match("/^GET\s[a-z][a-zA-Z1-9]*$/", $line))) {
    //GET propertyname command
      $propStr = substr($line, 4);
      $getProp = "The value of property {$propStr} is {$obj->$propStr} \r\n";
      echo $getProp;
  } else if ((preg_match("/^GET\s\*$/", $line))) {
    //GET * command
      var_dump(get_object_vars($obj));
  } else if ($line == "exit" || $line == "EXIT") {
    //EXIT command
      echo "goodbye\r\n";
      $inUse = false;
      exit(0);
  } else {
    // no condtions met, program ends with error message
      echo "I am sorry, but that is not a vaild command. \r\n";
  }

} while ($inUse === true);

?>
