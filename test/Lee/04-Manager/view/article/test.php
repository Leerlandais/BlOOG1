<?php

/*
 *
 *
 * Ajouter des méthodes à ArticleManager

et une propriété de type null|UserMapping (et getters setters)

et une propriété de type null|[] contenant des instances de TagMapping (et getters setters)

et une propriété de type null|[] contenant des instances de CategoryMapping (et getters setters)

et une propriété contenant les null|[] contenant des instances de CommentMapping
*/

$sql = "SELECT a.*, c.*,
        GROUP_CONCAT(c.category_name SEPARATOR ',') AS cat_name
        FROM article a
        LEFT JOIN article_has_category h 
        ON h.article_article_id = a.article_id
        LEFT JOIN category c 
        ON c.category_id = h.category_category_id
        GROUP BY a.article_id;";


