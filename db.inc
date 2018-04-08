<?php
   // This file is the same as example 6-7, but includes mysqlclean() and shellclean() 

   $hostName = "127.0.0.1";
   $databaseName = "ps3";
   $username = "geralt";
   $password = "Woailiyu1";

   function showerror()
   {
      die("Error " . mysql_errno() . " : " . mysql_error());
   }

   function mysqlclean($array, $index, $maxlength, $connection)
   {
     if (isset($array["{$index}"]))
     {
        $input = substr($array["{$index}"], 0, $maxlength);
        $input = mysqli_real_escape_string($connection, $input);
        return ($input);
     }
     return NULL;
   }

   function shellclean($array, $index, $maxlength)
   {
     if (isset($array["{$index}"]))
     {
       $input = substr($array["{$index}"], 0, $maxlength);
       $input = EscapeShellCmd($input);
       return ($input);
     }
     return NULL;
   }
?>
