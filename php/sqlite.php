<?php
   class SQLiteDB extends SQLite3
   {
      function __construct()
      {
         $this->open(__DIR__.'/../data/covid-19.db');
      }
   }

   $db = new SQLiteDB();
   if(!$db){
      echo $db->lastErrorMsg();
   } 
?>