<?php

namespace model\Manager ;

use Exception;
use model\Abstract\AbstractMapping;
use model\Interface\InterfaceManager;
use model\Mapping\ArticleMapping;
use model\trait\TraitRunQuery;
use model\OurPDO;


use model\Mapping\UserMapping;
use model\Mapping\CategoryMapping;
use model\Mapping\TagMapping;

class ArticleManager implements InterfaceManager{

    use TraitRunQuery;
    private ?OurPDO $connect = null;

    public function __construct(OurPDO $db){
        $this->connect = $db;
    }


    public function selectAll(): ?array  // sauf les noms des tables, ceci est souvent similaire - faut que remplacer article_ par comment_ donc sans doute possible de mettre ailleurs
    {
        $sql = "SELECT * FROM `article`
         ORDER BY `article_date_create` DESC";
        
        $array = $this->runQuery($sql);
        if (!is_array($array)) throw new Exception("");
        $arrayArticles = [];
        foreach($array as $value){
            $arrayArticles[] = new ArticleMapping($value);
        }
        return $arrayArticles;
    }

/* 
*
*   Tout le reste ici est pour satisfait le Interface pendant que je crée les autres fonctions
*
*/


    // RECUEPRATION D'UN ARTICLE VIA ID 
    public function selectOneById(int $id): null|string|ArticleMapping
    {

        $sql     = "SELECT * 
                    FROM `article` 
                    WHERE `article_id`= ?";
        $getStmt = $this->connect->prepare($sql);
        $getStmt->bindValue(1,$id, OurPDO::PARAM_INT);

        try{
            $getStmt->execute();
            if($getStmt->rowCount()===0) return null;
                $result = $getStmt->fetch(OurPDO::FETCH_ASSOC);
                $result = new ArticleMapping($result);
                $getStmt->closeCursor();
            return $result;
        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }

    public function insert(AbstractMapping $mapping): bool|string {
 
    
            // requête préparée
            $sql = "INSERT INTO `article`(`article_title`, `article_slug`, `article_text`,`user_user_id`)  VALUES (?,?,?,?)";
            $addStmt = $this->connect->prepare($sql);

            try{
                $addStmt->bindValue(1,$mapping->getArticleTitle());
                $addStmt->bindValue(2,$mapping->getArticleSlug());
                $addStmt->bindValue(3,$mapping->getArticleText());
                $addStmt->bindValue(4,1, OurPDO::PARAM_INT);

    
                $addStmt->execute();
    
                $addStmt->closeCursor();
    
                return true;
    
            }catch(Exception $e){
                return $e->getMessage();
            }
        }

    public function update(AbstractMapping $mapping) {

        $sql = "UPDATE `article` 
                SET `article_title`=?,
                    `article_slug`=?,
                    `article_text`=?, 
                    `article_date_update`=? 
                WHERE `article_id`=?";
        // mise à jour de la date de modification
        $mapping->setArticleDateUpdate(date("Y-m-d H:i:s"));
        $prepare = $this->connect->prepare($sql);

        try{
            $prepare->bindValue(1,$mapping->getArticleTitle());
            $prepare->bindValue(2,$mapping->getArticleSlug());
            $prepare->bindValue(3,$mapping->getArticleText());
            $prepare->bindValue(4,$mapping->getArticleDateUpdate());
            $prepare->bindValue(5,$mapping->getArticleId(), OurPDO::PARAM_INT);

            $prepare->execute();

            $prepare->closeCursor();

            return true;

        }catch(Exception $e){
            return $e->getMessage();
        }
    }


/*
    // mise à jour d'un commentaire
    public function update(AbstractMapping $mapping): bool|string
    {

        // requête préparée
        $sql = "UPDATE `comment` SET `comment_text`=?, `comment_date_update`=? WHERE `comment_id`=?";
        // mise à jour de la date de modification
        $mapping->setCommentDateUpdate(date("Y-m-d H:i:s"));
        $prepare = $this->connect->prepare($sql);

        try{
            $prepare->bindValue(1,$mapping->getCommentText());
            $prepare->bindValue(2,$mapping->getCommentDateUpdate());
            $prepare->bindValue(3,$mapping->getCommentId(), OurPDO::PARAM_INT);

            $prepare->execute();

            $prepare->closeCursor();

            return true;

        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }

*/

    // SUPPRESSION D'UN ARTICLE
    public function delete(int $id): bool|string
    {
        $sql     = "DELETE FROM `article` 
                    WHERE `article_id`=?";
        $delStmt = $this->connect->prepare($sql);

        try{
            $delStmt->bindValue(1,$id, OurPDO::PARAM_INT);
            $delStmt->execute();
            $delStmt->closeCursor();

            return true;

        }catch(Exception $e){
            return $e->getMessage();
        }
        
    }



public function selectArticleAndUser () {


        // on récupère tous les articles avec jointures
        $query = $this->db->query("
        SELECT a.*, 
               u.`user_id`, u.`user_login`, u.`user_full_name`,
               GROUP_CONCAT(c.`category_id`) as`category_id`, 
               GROUP_CONCAT(c.`category_name` SEPARATOR '|||') as `category_name`, 
               GROUP_CONCAT(c.`category_slug` SEPARATOR '|||') as `category_slug`,
               (SELECT GROUP_CONCAT(t.`tag_slug` SEPARATOR '|||')
                    FROM `tag` t
                    INNER JOIN `tag_has_article` tha
                        ON tha.`article_article_id` = a.`article_id`
                    WHERE t.`tag_id` = tha.`tag_tag_id`
                    GROUP BY a.`article_id`
                    ORDER BY t.`tag_slug` ASC    
                    ) as `tag_slug`

        FROM `article` a
        INNER JOIN `user` u  
            ON u.`user_id` = a.`user_user_id`
        LEFT JOIN article_has_category ahc
            ON ahc.`article_article_id` = a.`article_id`
        LEFT JOIN category c
            ON c.`category_id` = ahc.`category_category_id`
        WHERE a.`article_is_published` = 1
            GROUP BY a.`article_id`
            ORDER BY a.`article_date_publish` DESC
        
        ");

        if ($query->rowCount() == 0) return null;
        $tabMapping = $query->fetchAll();

        $query->closeCursor();
        $tabObject = [];
        
        foreach ($tabMapping as $mapping) {
        
            $user = $mapping['user_login'] !== null ? new UserMapping($mapping) : null;
            // si on a des catégories
            if ($mapping['category_id'] !== null) {
                // on crée un tableau de catégories
                $tabCategories = [];
                // on récupère les catégories
                $tabCategoryIds = explode(",", $mapping['category_id']);
                $tabCategoryNames = explode("|||", $mapping['category_name']);
                $tabCategorySlugs = explode("|||", $mapping['category_slug']);
                // on boucle sur les catégories
                for ($i = 0; $i < count($tabCategoryIds); $i++) {
                    // on instancie la catégorie
                    $category = new CategoryMapping([
                        'category_id' => $tabCategoryIds[$i],
                        'category_name' => $tabCategoryNames[$i],
                        'category_slug' => $tabCategorySlugs[$i]
                    ]);
                    // on ajoute la catégorie au tableau
                    $tabCategories[] = $category;
                }

            } else {
                $tabCategories = null;
            }
            // si on a des tags
            if ($mapping['tag_slug'] !== null) {
                // on crée un tableau de tags
                $tabTags = [];
                // on récupère les tags
                $tabTagSlugs = explode("|||", $mapping['tag_slug']);
                // on boucle sur les tags
                for ($i = 0; $i < count($tabTagSlugs); $i++) {
                    // on instancie le tag
                    $tag = new TagMapping([
                        'tag_slug' => $tabTagSlugs[$i]
                    ]);
                    // on ajoute le tag au tableau
                    $tabTags[] = $tag;
                }
            } else {
                $tabTags = null;
            }


            // on instancie l'article
            $article = new ArticleMapping($mapping);
            // on ajoute user à l'article
            $article->setUser($user);
            // on ajoute les catégories à l'article
            $article->setCategories($tabCategories);
            // on ajoute les tags à l'article
            $article->setTags($tabTags);
            // on ajoute l'article au tableau
            $tabObject[] = $article;
        }
        return $tabObject;
    }

    public function selectAllArticleHomepage(): ?array
    {

        // on récupère tous les articles avec jointures
        $query = $this->db->query("
        SELECT a.*, 
               u.`user_id`, u.`user_login`, u.`user_full_name`,
               GROUP_CONCAT(c.`category_id`) as`category_id`, 
               GROUP_CONCAT(c.`category_name` SEPARATOR '|||') as `category_name`, 
               GROUP_CONCAT(c.`category_slug` SEPARATOR '|||') as `category_slug`,
               (SELECT GROUP_CONCAT(t.`tag_slug` SEPARATOR '|||')
                    FROM `tag` t
                    INNER JOIN `tag_has_article` tha
                        ON tha.`article_article_id` = a.`article_id`
                    WHERE t.`tag_id` = tha.`tag_tag_id`
                    GROUP BY a.`article_id`
                    ORDER BY t.`tag_slug` ASC    
                    ) as `tag_slug`

        FROM `article` a
        INNER JOIN `user` u  
            ON u.`user_id` = a.`user_user_id`
        LEFT JOIN article_has_category ahc
            ON ahc.`article_article_id` = a.`article_id`
        LEFT JOIN category c
            ON c.`category_id` = ahc.`category_category_id`
        WHERE a.`article_is_published` = 1
            GROUP BY a.`article_id`
            ORDER BY a.`article_date_publish` DESC
        
        ");
        // si aucun article n'est trouvé, on retourne null
        if ($query->rowCount() == 0) return null;
        // on récupère les articles sous forme de tableau associatif
        $tabMapping = $query->fetchAll();
        // on ferme le curseur
        $query->closeCursor();
        // on crée le tableau où on va instancier les objets
        $tabObject = [];
        // pour chaque article, on boucle
        foreach ($tabMapping as $mapping) {
            // si on a un user on l'instancie
            $user = $mapping['user_login'] !== null ? new UserMapping($mapping) : null;
            // si on a des catégories
            if ($mapping['category_id'] !== null) {
                // on crée un tableau de catégories
                $tabCategories = [];
                // on récupère les catégories
                $tabCategoryIds = explode(",", $mapping['category_id']);
                $tabCategoryNames = explode("|||", $mapping['category_name']);
                $tabCategorySlugs = explode("|||", $mapping['category_slug']);
                // on boucle sur les catégories
                for ($i = 0; $i < count($tabCategoryIds); $i++) {
                    // on instancie la catégorie
                    $category = new CategoryMapping([
                        'category_id' => $tabCategoryIds[$i],
                        'category_name' => $tabCategoryNames[$i],
                        'category_slug' => $tabCategorySlugs[$i]
                    ]);
                    // on ajoute la catégorie au tableau
                    $tabCategories[] = $category;
                }

            } else {
                $tabCategories = null;
            }
            // si on a des tags
            if ($mapping['tag_slug'] !== null) {
                // on crée un tableau de tags
                $tabTags = [];
                // on récupère les tags
                $tabTagSlugs = explode("|||", $mapping['tag_slug']);
                // on boucle sur les tags
                for ($i = 0; $i < count($tabTagSlugs); $i++) {
                    // on instancie le tag
                    $tag = new TagMapping([
                        'tag_slug' => $tabTagSlugs[$i]
                    ]);
                    // on ajoute le tag au tableau
                    $tabTags[] = $tag;
                }
            } else {
                $tabTags = null;
            }


            // on instancie l'article
            $article = new ArticleMapping($mapping);
            // on ajoute user à l'article
            $article->setUser($user);
            // on ajoute les catégories à l'article
            $article->setCategories($tabCategories);
            // on ajoute les tags à l'article
            $article->setTags($tabTags);
            // on ajoute l'article au tableau
            $tabObject[] = $article;
        }
        return $tabObject;
    }



}