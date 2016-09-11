<?php

// loads classes
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

// while true user will be returned to the initial prompt
$inUse = true;
// if $requestedClass length is 0, user will be ask for a class
$requestedClass = "";

do {
  //initial prompt
  if (strlen($requestedClass) == 0) {
    // displys a list of classes from the src folder for user to select from
    $avaibleClasses = implode(" ", $classList);
    $requestClass = "Welcome to Object Edit! "
                    . "Chooose a \033[33mClass\033[0m to instnce your "
                    . "object :\r\n\033[33m{$avaibleClasses} \033[0m \r\n";
    fwrite(STDOUT, $requestClass );
    $requestedClass = trim(fgets(STDIN));
    if (strlen($requestedClass) == 0) {
      $requestedClass = $classList[0];
    }
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

      // instances the object and displays print it to terminal
      $rc = new ReflectionClass($requestedClass);
      $obj = $rc->newInstanceArgs($args);
      var_dump(get_object_vars($obj));
    } else {
      echo "The class \033[33m{$requestedClass}\033[0m could not be found!\r\n";
      $requestedClass = "";
    }

  }

  //command prompt
  echo "\n\n";
  fwrite(STDOUT,  "Type HELP for commands list.\r\n");
  $line = trim(fgets(STDIN));

  if ($line == "help" || $line == "HELP" || strlen($line) == 0) {
    //HELP command
    fwrite(STDERR, "\033[32mHELP\033[0m : view commands list\n"
                   . "\033[32mSET < propertyname=value >\033[0m : adds a property with a value to loaded object\n"
                   . "\033[32mGET < propertyname >\033[0m : retive the value of the property\n"
                   . "\033[32mGET *\033[0m : Diplay Object's properties and values\n"
                   . "\033[32mSAVE\033[0m : Save Object to local file\n"
                   . "\033[32mEXIT\033[0m : exit Object Edit\n");

  } else if ((preg_match("/^(SET|set)\s[a-z][a-zA-Z1-9]*\=[a-zA-Z1-9]*$/", $line))) {
    //SET propertyname=value command
      $propStr = substr($line, 4);
      if ((preg_match("/^[a-z][a-zA-Z1-9]*\=[a-zA-Z1-9]*$/", $propStr))) {
        $prop = explode("=", $propStr);
        if(is_bool($prop[1])) {
          $prop[1] = boolval($prop[1]);
        }
        if(is_numeric($prop[1])) {
          $prop[1] = intval($prop[1]);
        }
        if (property_exists($obj,$prop[0])) {
          echo "property exists checking type ...";
          if (gettype($obj->$prop[0]) == gettype($prop[1])) {
            echo "type matches...setting {$prop[1]} as value";
            $obj->$prop[0] = $prop[1];
          } else {
            $typeName = gettype($obj->$prop[0]);
            echo "{$prop[1]} type does not match {$typeName}";
          }
        } else {
          echo "property {$prop[0]} was added with a value of {$prop[1]}";
          if (is_bool($prop[1])) {
            $obj->$prop[0] = (bool) $prop[1];
          } else if (is_numeric($prop[1])) {
            $obj->$prop[0] = (integer) $prop[1];
          } else {
            $obj->$prop[0] = $prop[1];
          }
        }
      }
  } else if ((preg_match("/^(GET|get)\s[a-z][a-zA-Z1-9]*$/", $line))) {
    //GET propertyname command
      $propStr = substr($line, 4);
      $getProp = "The value of property {$propStr} is {$obj->$propStr} \r\n";
      echo $getProp;
  } else if ((preg_match("/^(GET|get)\s\*$/", $line))) {
    //GET * command
      print_r(get_object_vars($obj));
  } else if ((preg_match("/^(SAVE|save)$/", $line))) {
    //SAVE command
      $file = 'objects/data.txt';
      $serialized = serialize($obj);
      file_put_contents($file, $serialized, FILE_APPEND | LOCK_EX);
  } else if ($line == "exit" || $line == "EXIT") {
    //EXIT command
      echo "\033[31mgoodbye\033[0m\r\n";
      $inUse = false;
      exit(0);
  } else {
    // no condtions met, error message
      echo "I am sorry, ${line} is not a vaild command. \r\n";
  }

} while ($inUse == true);

?>
