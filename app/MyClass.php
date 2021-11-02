<?php

   namespace App;
   use \Tamtamchik\SimpleFlash\Flash;

   class MyClass{
      private $Flash;
      public function __construct(Flash $Flash){
         $this->Flash = $Flash;
      }


      public function FlashRedirect ($tetx, $StatusDisplay, $Redirect){
         $this->Flash::$StatusDisplay($tetx);
         $this->Redirect($Redirect);
      }

      public function Redirect($to ='/' ){
         header('Location: ' .$to);
         exit();
      }





   }