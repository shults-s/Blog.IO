<?php
namespace Models;

class Show extends \System\BaseModel
{
    /* Устанавливает параметры запроса: имя таблицы и идентификатор записи */
    public function SetParameters(string $table, int $recordId) : void
    {
        $this->_table = $table;
        $this->_recordId = $recordId;
    }

    /* Проверяет существование таблицы и записи в ней с заданным идентификатором */
    public function ValidParameters() : bool
    {
        if (!in_array($this->_table, self::TABLES_WHITE_LIST))
        {
            return false;
        }

        $availableKeys = $this->_GetAvailableKeys();

        return in_array($this->_recordId, $availableKeys);
    }

    /* Получает из базы статью по ее идентификатору */
    public function GetArticle()
    {
        $statement = $this->_dataBase->prepare('SELECT * FROM `Articles` WHERE `ID` = ?');

        $statement->execute([ $this->_recordId ]);

        $article = $statement->fetch();
        $article->Authors = $this->_GetAuthorsByArticleId($article->ID);
        $article->Category = $this->_GetCategory($article->ID);
        $article->Commentaries = $this->_GetCommentaries($article->ID);

        return $article;
    }

   /*
    * Получает список всех ключевых полей таблицы в виде индексированного массива
    * ВНИМАНИЕ: Значения получаются из базы только при первом обращении к методу!
    */
    private function _GetAvailableKeys() : array
    {
        static $availableKeys = null;

        if ($availableKeys === null)
        {
            $statement = $this->_dataBase->query("SELECT `ID` FROM `{$this->_table}`");

            while (($value = $statement->fetchColumn()) !== false)
            {
                $availableKeys[] = $value;
            }

            $availableKeys = $availableKeys ?? [];

            sort($availableKeys);
        }

        return $availableKeys;
    }

    /* Получает список авторов статьи по ее идентификатору */
    private function _GetAuthorsByArticleId(int $articleId) : array
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

    /* Получает комментарии к статье по ее идентификатору */
    private function _GetCommentaries(int $articleId) : array
    {
        $statement = $this->_dataBase->prepare('SELECT * FROM `Commentaries` WHERE `ID` IN '
            . '(SELECT `CommentaryID` FROM `ArticleCommentary` WHERE `ArticleID` = ?)');

        $statement->execute([ $articleId ]);
        $commentaries = $statement->fetchAll();

        foreach ($commentaries as $commentary)
        {
            $statement = $this->_dataBase->prepare('SELECT `Name` FROM `Users` WHERE `ID` = '
                . '(SELECT `UserID` FROM `UserCommentary` WHERE `CommentaryID` = ?)');

            $statement->execute([ $commentary->ID ]);
            $commentary->Author = $statement->fetch()->Name;
        }

        return $commentaries;
    }

    /* Создает алиас для объекта соединения с базой данных */
    protected function _Initialize() : void
    {
        $this->_dataBase = $this->_services->dataBase->connection;
    }

    private $_table = null;
    private $_recordId = null;

    private $_dataBase = null;

    private const TABLES_WHITE_LIST = [ 'Articles'/*, 'Categories'*/ ];
}
?>