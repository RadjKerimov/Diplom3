<?php
   use League\Plates\Engine;
   use \Delight\Auth\Auth;
   use App\QueryBuilder;
   use Aura\SqlQuery\QueryFactory ;

   

   return [
      PDO::class => function () {
         return new PDO("mysql:host=localhost; dbname=diplom3; charset=utf8", "root", "root");
      },

      Engine::class => function(){
         return new Engine('../app/view');
      },



      Auth::class => function ($container) {
         return new Auth($container->get('PDO', null, null, false));
      },


   ];