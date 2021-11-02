<!DOCTYPE html>
<html lang="ru">

<head>
   <meta charset="UTF-8">
   <title><?php $this->e($title) ?></title>
   <meta name="description" content="">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">


   <link rel="stylesheet" type="text/css" href="css/fa-solid.css">
   <link id="appbundle" rel="stylesheet" type="text/css" media="screen, print" href="css/app.bundle.css">
   <link id="myskin" rel="stylesheet" type="text/css" media="screen, print" href="css/skins/skin-master.css">
   <link rel="stylesheet" type="text/css" media="screen, print" href="css/fa-solid.css">
   <link rel="stylesheet" type="text/css" media="screen, print" href="css/fa-brands.css">
   <link rel="stylesheet" type="text/css" media="screen, print" href="css/fa-regular.css">
   <script src="js/vendors.bundle.js"></script>
   <script src="js/app.bundle.js"></script>
   
</head>


<body class="mod-bg-1 mod-nav-link">
   <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
      <a class="navbar-brand d-flex align-items-center fw-500" href="/"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="navbarColor02">
         <ul class="navbar-nav mr-auto">
            <li class="nav-item active"><a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a></li>
         </ul>
         <ul class="navbar-nav ml-auto">
            <?php if ($auth->isLoggedIn()) : ?>
               <li class="nav-item"><a class="nav-link" href="/exit">Выйти</a></li>
            <?php else : ?>
               <li class="nav-item"><a href="/login" class="nav-link">Войти</a></li>
            <?php endif; ?>
         </ul>
      </div>
   </nav>









   <?= $this->section('content') ?>







   <!-- BEGIN Page Footer -->
   <footer class="page-footer" role="contentinfo">
      <div class="d-flex align-items-center flex-1 text-muted"><span class="hidden-md-down fw-700">2021 © Учебный проект</span></div>
      <div>
         <ul class="list-table m-0">
            <li><a href="intel_introduction.html" class="text-secondary fw-700">Home</a></li>
            <li class="pl-3"><a href="info_app_licensing.html" class="text-secondary fw-700">About</a></li>
         </ul>
      </div>
   </footer>

</body>

</html>