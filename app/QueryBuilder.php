<?php
   namespace App;

   use Aura\SqlQuery\QueryFactory ;
   use PDO;
   






   class QueryBuilder{
      public $pdo;
      protected $queryFactory;

      public function __construct(){
         $this->pdo = new PDO("mysql:host=localhost; dbname=diplom3; charset=utf8", 'root', 'root');
         $this->queryFactory =  new QueryFactory('mysql'); //Инициализация 
      }


   

      public function getPaginator($itemsPerPage, $currentPage){
         $select = $this->queryFactory->newSelect();  
         $select
               ->cols(['*'])
               ->from('posts') //C какой таблицы
               ->setPaging($itemsPerPage) //Сколько выводит 
               ->page($currentPage); //С какой начинать выводит
               
         $sth = $this->pdo->prepare($select->getStatement());
         $sth->execute($select->getBindValues());
         return $sth->fetchAll(PDO::FETCH_ASSOC);  
      }


      //Получаем все с таблицы ! 
      public function getAll($table){     
         $select = $this->queryFactory->newSelect();   
         $select->cols(['*'])->from($table);

         $sth = $this->pdo->prepare($select->getStatement());  
         $sth->execute($select->getBindValues()); 
         return $sth->fetchAll(PDO::FETCH_ASSOC);  
      }

      public function getOne($table, $id){     
         $select = $this->queryFactory->newSelect();   
         $select->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValues(['id' => $id]);

         $sth = $this->pdo->prepare($select->getStatement());  
         $sth->execute($select->getBindValues()); 
         return $sth->fetch(PDO::FETCH_ASSOC);  
      }





      //Сохраняем данные в таблице!
      public function insert($table, $data){
         $insert = $this-> queryFactory->newInsert();
         $insert
            ->into($table)                   
            ->cols($data);
         return $this->prepareExecute($insert);
      }




      // Update Редактирует данные в таблице! Принимает название таблицы, id, и параметры виде массива! 
      public function update($table, $id, $data){
         $update = $this->queryFactory->newUpdate();

         $update
            ->table($table)          
            ->cols($data)                 
            ->where('id = :id')           
            ->bindValues(['id' => $id]);

         return $this->prepareExecute($update);
      }





      /* 
         * delete Удаляет!
         * Принимает Название таблицы и id поля которую нужно удалить
      */
      public function delete($table, $id){
         $delete = $this->queryFactory->newDelete();

         $delete
            ->from($table)                   // FROM this table
            ->where('id = :id')           // AND WHERE these conditions  
            ->bindValue('id', $id);   // bind one value to a placeholder      
         return $this->prepareExecute($delete);
      }

      //Проверяеть еcлси такой пользыватель с таким email, Принемает email возрошает bool
      public function ifUser($email){
         $select = $this->queryFactory->newSelect();   
         $select->cols(['email'])
            ->from('users')
            ->where('email = :email')
            ->bindValues(['email' => $email]);
         $sth = $this->pdo->prepare($select->getStatement());  
         $sth->execute($select->getBindValues()); 
         

         if ($email == $sth->fetch(PDO::FETCH_ASSOC)['email']) 
            return true;
         else 
            return false;    
      }



      private function prepareExecute($array){
         $sth = $this->pdo->prepare($array->getStatement());
         return $sth->execute($array->getBindValues());
      }
   }