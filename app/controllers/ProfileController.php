<?php
   namespace App\controllers;


   use League\Plates\Engine;
   use App\QueryBuilder;
   use \Delight\Auth\Auth;
   use \Tamtamchik\SimpleFlash\Flash;
   use App\MyClass;


   class ProfileController extends RegisterController {
      private $templates;
      private $db;
      private $auth;
      private $Flash;
      private $MyClass;
      public function __construct(Engine $templates, QueryBuilder $db, Auth $Auth, Flash $Flash, MyClass $MyClass){
         $this->templates = $templates;
         $this->db = $db;
         $this->auth = $Auth;
         $this->Flash = $Flash;
         $this->MyClass = $MyClass;
      }





      public function profile(){
         if (!$this->auth->isLoggedIn()) {
            $this->Flash::info('Вы не авторизованы, авторизуйтесь пожалуйста!');
            $this->MyClass->Redirect('/login');
            exit;
         } 
         echo $this->templates->render('profile.view', [
            'auth' => $this->auth,
            'getOneUser' =>  $this->db->getOne('users', $this->auth->getUserId()),
         ]);         
      }




      //Обновление Имени и email
      public function EditNameAndEmail(){
         if (!$this->auth->isLoggedIn()) {
            $this->MyClass->FlashRedirect('Вы не авторизованы, авторизуйтесь пожалуйста!', 'error', '/login');
         } 

         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //d($_POST);die;

            try {
               if ($this->auth->reconfirmPassword($_POST['password'])) {
                  $this->auth->changeEmail($_POST['email'], 
                     function ($selector, $token) {
                        $this->verification($selector, $token);
                        $this->MyClass->FlashRedirect('Изменение вступит в силу, как только будет подтвержден новый адрес электронной почты', 'error', '/profile');
                  });

                  $this->db->update('users', $this->auth->getUserId(), [
                     'username' => trim($_POST['username']),
                  ]);

                  $this->MyClass->FlashRedirect('Данные обновлены!', 'info', '/profile');
               } else {
                  $this->MyClass->FlashRedirect('Мы не можем быть уверены, являетесь ли вы тем, за кого себя выдает!', 'error', '/profile');     
               }
            } catch (\Delight\Auth\InvalidEmailException $e) {
               $this->MyClass->FlashRedirect('Неверный адрес электронной почты', 'error', '/profile');
            } catch (\Delight\Auth\UserAlreadyExistsException $e) {
               $this->MyClass->FlashRedirect('Адрес электронной почты уже существует', 'error', '/profile');
            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
               $this->MyClass->FlashRedirect('Учетная запись не подтверждена', 'error', '/profile');
            } catch (\Delight\Auth\NotLoggedInException $e) {
               $this->MyClass->FlashRedirect('Не вошел в систему', 'error', '/profile');
            } catch (\Delight\Auth\TooManyRequestsException $e) {
               $this->MyClass->FlashRedirect('Слишком много запросов!', 'error', '/profile');
            }
         }

      }







      public function changepassword(){
         if (!$this->auth->isLoggedIn()) {
            $this->MyClass->FlashRedirect('Вы не авторизованы, авторизуйтесь пожалуйста!', 'error', '/login');
         } 

         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['NewPasswordVerification'] == $_POST['newPassword']) {
               try {
                  $this->auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);
                  $this->MyClass->FlashRedirect('Пароль был изменен!', 'info', '/changepassword');
               } catch (\Delight\Auth\NotLoggedInException $e) {
                  $this->MyClass->FlashRedirect('Не вошел в систему!', 'error', '/changepassword');
               } catch (\Delight\Auth\InvalidPasswordException $e) {
                  $this->MyClass->FlashRedirect('Неверный пароль!', 'error', '/changepassword');
               } catch (\Delight\Auth\TooManyRequestsException $e) {
                  $this->MyClass->FlashRedirect('Слишком много запросов!', 'error', '/changepassword');
               }
            }else {
               $this->MyClass->FlashRedirect('Новые пароли не совпадают!', 'error', '/changepassword');               
            }
         }


         echo $this->templates->render('changepassword.view', [
            'auth' => $this->auth,
            'getOneUser' =>  $this->db->getOne('users', $this->auth->getUserId()),
         ]);       
      }



   }