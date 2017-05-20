<?php
namespace Models;

class Main extends \System\BaseModel
{
    /* Получает из базы данных список статей, отсортированных по дате */
    public function GetArticles() : array
    {
        $statement = $this->_dataBase->query('SELECT * FROM `Articles` ORDER BY `WriteDate` DESC LIMIT 10');
        $articles = $statement->fetchAll();

        foreach ($articles as $article)
        {
            $article->Authors = $this->_GetAuthors($article->ID);
            $article->Category = $this->_GetCategory($article->ID);
            $article->CommentariesNumber = $this->_GetCommentariesNumber($article->ID);
        }

        return $articles;
    }

    /* Получает список авторов статьи по ее идентификатору */
    private function _GetAuthors(int $articleId) : array
    {
        $statement = $this->_dataBase->prepare('SELECT `Name` FROM `Users` WHERE `ID` IN '
            . '(SELECT `UserID` FROM `UserArticle` WHERE `ArticleID` = ?)');

        $statement->execute([ $articleId ]);

        return $statement->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    /* Получает категорию статьи по ее идентификатору */
    private function _GetCategory(int $articleId) : string
    {
        $statement = $this->_dataBase->prepare('SELECT `Name` FROM `Categories` WHERE `ID` = '
            . '(SELECT `CategoryID` FROM `ArticleCategory` WHERE `ArticleID` = ?)');

        $statement->execute([ $articleId ]);

        return $statement->fetch()->Name;
    }

    /* Получает категорию статьи по ее идентификатору */
    private function _GetCommentariesNumber(int $articleId) : string
    {
        $statement = $this->_dataBase->prepare('SELECT COUNT(*) FROM `ArticleCommentary` WHERE `ArticleID` = ?');
        $statement->execute([ $articleId ]);

        return $statement->fetchColumn();
    }

    /* Создает алиас для объекта соединения с базой данных */
    protected function _Initialize() : void
    {
        $this->_dataBase = $this->_services->dataBase->connection;
    }

    private $_dataBase = null;
}
?>