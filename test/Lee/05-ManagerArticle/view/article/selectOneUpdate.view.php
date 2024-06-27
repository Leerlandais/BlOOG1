<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <title><?=$title?></title>
</head>
<body>
    <h1>Exemple du ArticleManager::selectOneArticle()</h1>
    <div>
        <?php

        require 'menu.Article.view.php';

        if(!is_object($selectOneArticle)) {
        ?>
        <h3>Article inexistant</h3>
        
        <?php
    }else{
        include ("inc/oneArticleForm.inc.php");
    }
        ?>
    </div>

</body>
</html>