<?php
   namespace App\controllers;

   use League\Plates\Engine;
   use App\QueryBuilder;
   use App\MyClass;
   use \Delight\Auth\Auth;
   use \Tamtamchik\SimpleFlash\Flash;




   class RegisterController{
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








      public function login(){
         if ($this->auth->isLoggedIn()) {
            $this->Flash::info('Вы  авторизованы!');
            $this->MyClass->Redirect('/');
            exit;
         } 


         
         echo $this->templates->render('login.view', []);

         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rememberDuration = null;
            if ($_POST['remember'] == 1) {
               $rememberDuration = (int) (60 * 60 * 24 * 365.25);
            } 
               
            

            try {
               $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);
               $this->Flash::info('Пользователь <b>' . $this->auth->getUsername() . '</b> &#129303; вошел в систему');
               $this->MyClass->Redirect('/');
            } catch (\Delight\Auth\InvalidEmailException $e) {
               $this->Flash::error('Неправильный адрес электронной почты');
               $this->MyClass->Redirect('/login');
               exit;
            } catch (\Delight\Auth\InvalidPasswordException $e) {
               $this->Flash::error('Неверный пароль');
               $this->MyClass->Redirect('/login');
               exit;
            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
               $this->Flash::error('Электронная почта не потвержден');
               $this->MyClass->Redirect('/login');
               exit;
            } catch (\Delight\Auth\TooManyRequestsException $e) {
               $this->Flash::error('Слишком много запросов');
               $this->MyClass->Redirect('/login');
               exit;
            } 
         }  
      }


      public function register(){
         if ($this->auth->isLoggedIn()) {
            $this->Flash::info('Вы  авторизованы!  <a href="/exit" class="nav-link">Выйти из системы</a>');
            $this->MyClass->Redirect('/');
            exit;
         } 
         echo $this->templates->render('register.view', []);

         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['password'] != $_POST['passwordtwo']) {
               $this->Flash::error('Пароль не совпадает!');
               $this->MyClass->Redirect('/register');
               exit;
            }

            try {
               $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                  $this->verification($selector, $token);
               });
               $this->Flash::info('Мы зарегистрировали нового пользователя с идентификатором' . $userId);
            } catch (\Delight\Auth\InvalidEmailException $e) {
               $this->Flash::error('Неверный адрес электронной почты');
               $this->MyClass->Redirect('/register');
               exit();
            } catch (\Delight\Auth\InvalidPasswordException $e) {
               $this->Flash::error('Неверный пароль');
               $this->MyClass->Redirect('/register');
               exit();
            } catch (\Delight\Auth\UserAlreadyExistsException $e) {
               $this->Flash::error('Пользователь уже существует');
               $this->MyClass->Redirect('/register');
               exit();
            } catch (\Delight\Auth\TooManyRequestsException $e) {
               $this->Flash::error('Слишком много просьб');
               $this->MyClass->Redirect('/register');
               exit();
            }  
         }  
      }


      public function exit(){
         if ($this->auth->isLoggedIn()) {
            try {
               $this->auth->logOutEverywhere();
               $this->MyClass->Redirect('/');
               exit;
            } catch (\Delight\Auth\NotLoggedInException $e) {
               $this->Flash::error('Не удалось выйти из системы!');
               $this->MyClass->Redirect('/');
               exit;
            }  
         }else {
            $this->Flash::error('Вы не авторизованы!');
            $this->MyClass->Redirect('/');
            exit;
         }
    
      }


      public function verification($selector, $token){
         try {
            $this->auth->confirmEmail($selector, $token);
            $this->Flash::info('Адрес электронной почты был подтвержден можете авторизоваться!');
            $this->MyClass->Redirect('/login');
            exit;
         } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
               $this->Flash::error('Недопустимый токен');
               $this->MyClass->Redirect('/login');
               exit();
         } catch (\Delight\Auth\TokenExpiredException $e) {
               $this->Flash::error('Срок действия токена истек');
               $this->MyClass->Redirect('/login');
               exit();
         } catch (\Delight\Auth\UserAlreadyExistsException $e) {
               $this->Flash::error('Адрес электронной почты уже существует');
               $this->MyClass->Redirect('/login');
               exit();
         } catch (\Delight\Auth\TooManyRequestsException $e) {
               $this->Flash::error('Слишком много просьб');
               $this->MyClass->Redirect('/login');
               exit();
         }        
      }








   }
