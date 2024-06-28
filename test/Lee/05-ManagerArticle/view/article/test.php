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

$sql = "SELECT 
    A.*,
    COUNT(C.comment_id) AS comm_count,
    GROUP_CONCAT(CAT.category_name SEPARATOR ', ') AS cats
FROM 
    article A
LEFT JOIN 
    comment C ON A.article_id = C.article_article_id
LEFT JOIN 
    article_has_category AHC ON A.article_id = AHC.article_article_id
LEFT JOIN 
    category CAT ON AHC.category_category_id = CAT.category_id
GROUP BY 
    A.article_id;";


$sql = "SELECT 
    A.*,
    COALESCE(comm_count, 0) AS comm_count,
    COALESCE(cats, '') AS cats
FROM 
    article A
LEFT JOIN (
    SELECT 
        C.article_article_id,
        COUNT(C.comment_id) AS comm_count
    FROM 
        comment C
    GROUP BY 
        C.article_article_id
) AS comm ON A.article_id = comm.article_article_id
LEFT JOIN (
    SELECT 
        AHC.article_article_id,
        GROUP_CONCAT(CAT.category_name SEPARATOR ', ') AS cats
    FROM 
        article_has_category AHC
    LEFT JOIN 
        category CAT ON AHC.category_category_id = CAT.category_id
    GROUP BY 
        AHC.article_article_id
) AS cat ON A.article_id = cat.article_article_id;
"