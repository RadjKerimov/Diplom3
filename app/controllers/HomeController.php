<?php
   namespace App\controllers;

   use League\Plates\Engine;
   use App\QueryBuilder;
   use \Delight\Auth\Auth;
   use \Tamtamchik\SimpleFlash\Flash;
   use App\MyClass;



   class HomeController{
      private $templates; 
      private $db;
      private $auth;
      private $MyClass;

      public function __construct(Engine $templates, QueryBuilder $db, Auth $auth, MyClass $MyClass){
         $this->templates = $templates;
         $this->db = $db;
         $this->auth = $auth;
         $this->MyClass = $MyClass;
      }



      public function home(){
         echo $this->templates->render('name.view', [
            'auth' => $this->auth,
            'getAllUsers' =>  $this->db->getAll('users'),
         ]);
      }


      public function NOT_FOUND(){
         echo '404';
      }
      
      public function NOT_ALLOWED(){
         echo '405';
      }


   }