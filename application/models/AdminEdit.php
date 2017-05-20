<?php
namespace Models;

class AdminEdit extends \System\BaseModel
{
   /*
    * Устанавливает общие параметры запроса: таблицу и номер записи
    * Если номер записи не передан в запросе (то есть второй параметр — пустой массив), то будет выбрана
    * первая запись таблицы, или массив с элементом 0, если таблица пуста
    */
    public function SetParameters(string $table, &$recordId) : void
    {
        $this->_table = $table;

        if ($recordId != [])
        {
            $this->_recordId = $recordId;
        }
        else
        {
            $recordId = $this->_recordId = $this->_GetFirstRecordId();
        }
    }

    /* Получает значения полей соответствующей таблицы для соответствующей записи */
    public function GetValues()
    {
        $condition = $this->_GeneratePrimaryKeysSqlCondition();

        if ($this->_recordId == [ 0 ])
        {
            return false;
        }
        else
        {
            $statement = $this->_dataBase->prepare("SELECT * FROM `{$this->_table}` WHERE {$condition}");
            $statement->execute($this->_recordId);

            return $statement->fetch();
        }
    }

    /* Получает список всех полей данной таблицы */
    public function GetFields() : array
    {
        return $this->_storage->tables->{$this->_table}->fields;
    }

    /* Получает номера записей, соседних с данной, массив с элементом 0 означает что запись крайняя */
    public function GetNeighboringRecords(&$previous, &$next) : void
    {
        $availableKeys = $this->_GetAvailableKeys();

        $current = array_search($this->_recordId, $availableKeys);

        $previous = [ 0 ];
        $next = [ 0 ];

        if ($current !== false)
        {
            if ($current - 1 >= 0)
            {
                $previous = $availableKeys[$current - 1];
            }

            if ($current + 1 < count($availableKeys))
            {
                $next = $availableKeys[$current + 1];
            }
        }
    }

    /* Проверяет существование данной таблицы и записи в ней или указания на создание новой записи */
    public function Validate(&$tableIsEmpty) : bool
    {
        if (!isset($this->_storage->tables->{$this->_table}))
        {
            return false;
        }

        $availableKeys = $this->_GetAvailableKeys();

        $tableIsEmpty = count($availableKeys) == 0 ? true : false;

        // $this->_recordId равен [ 0 ], когда в таблицу требуется вставить новую запись
        return in_array($this->_recordId, $availableKeys) || $this->_recordId == [ 0 ];
    }

    /* Производит добавление новой записи в данную таблицу */
    public function InsertRecord(array $recordData) : bool
    {
        $this->_ComplementWithStartNulls($recordData);

        if (!$this->_CheckFieldsAccordance($recordData))
        {
            return false;
        }

        // Преобразуем ассоциативный массив параметров в индексированный, пригодный для execute(...)
        $parameters = array_values($recordData);

        return $this->_ExecuteWithCatchingError($this->_GenerateInsertQuery($this->_table), $parameters);
    }

   /*
    * Дополняет массив параметров запроса недостающими полями таблицы с null-значениями
    * Используется для прохождения проверки на соответствие переданных в запросе полей полям таблицы
    */
    private function _ComplementWithStartNulls(array &$recordData) : void
    {
        $fields = $this->_storage->tables->{$this->_table}->fields;

        $difference = count($fields) - count($recordData);

        $complement = [];

        if ($difference > 0)
        {
            for ($i = 0; $i < $difference; $i++)
            {
                $complement[$fields[$i]->name] = null;
            }
        }

        $recordData = $complement + $recordData;
    }

    /* Генерирует SQL-запрос на добавление записи в таблицу, руководствуясь ее описанием */
    private function _GenerateInsertQuery(string $table) : string
    {
        $query = "INSERT INTO `{$table}` (";

        foreach($this->_storage->tables->{$this->_table}->fields as $field)
        {
            $query .= "`{$field->name}`, ";
        }

        // Удаляем лишнюю запятую и пробел
        $query = substr($query, 0, -2);

        $count = count($this->_storage->tables->{$this->_table}->fields);

        $placeholders = trim(str_repeat('?, ', $count), ', ');

        return $query . ") VALUES ({$placeholders})";
    }

    /* Производит обновление данной записи в данной таблице */
    public function UpdateRecord(array $recordData) : bool
    {
        $this->_ComplementWithStartNulls($recordData);

        if (!$this->_CheckFieldsAccordance($recordData))
        {
            return false;
        }

        /* Удаляем null-значения массива, которые были добавлены для прохождения
        проверки на соответствие переданных в запросе полей полям таблицы */
        $recordData = array_diff($recordData, [ null ]);

        // Вставляем ключевые поля в конец массива, при подстановке они идут последними
        $parameters = array_values($recordData + $this->_recordId);

        return $this->_ExecuteWithCatchingError($this->_GenerateUpdateQuery($this->_table), $parameters);
    }

    /* Генерирует SQL-запрос на обновление записи таблицу, руководствуясь ее описанием */
    private function _GenerateUpdateQuery(string $table) : string
    {
        $query = "UPDATE `{$table}` SET";

        $fields = $this->_storage->tables->{$this->_table}->fields;
        unset($fields[0]); // Ключевое поле обрабатывается отдельно

        foreach($fields as $field)
        {
            $query .= " `{$field->name}` = ?,";
        }

        $condition = $this->_GeneratePrimaryKeysSqlCondition();

        // Удаляем лишнюю запятую перед WHERE
        $query = substr($query, 0, -1) . " WHERE {$condition}";

        return $query;
    }

    /* Производит удаление данной записи из данной таблицы */
    public function DeleteRecord() : bool
    {
        $condition = $this->_GeneratePrimaryKeysSqlCondition();

        return $this->_ExecuteWithCatchingError(
            "DELETE FROM `{$this->_table}` WHERE {$condition}",
            $this->_recordId
        );
    }

    /* Возвращает последнее сообщение об ошибке в базе данных */
    public function GetLastErrorMessage() : string
    {
        return $this->_lastErrorMessage;
    }

    /* Выполняет запрос с параметрами при этом отлавливая исключение и проверяя успешность операции */
    public function _ExecuteWithCatchingError(string $query, array $parameters) : bool
    {
        try
        {
            $statement = $this->_dataBase->prepare($query);
            $statement->execute($parameters);

            // Число затронутых операцией строк, для большинства драйверов НЕ работает с SELECT'ом
            return $statement->rowCount() != 0;
        }
        catch(\PDOException $exception)
        {
            $this->_lastErrorMessage = $exception->getMessage();
            return false;
        }
    }

   /* 
    * Генерирует часть SQL-запроса, содержащую условие WHERE с первичными ключами таблицы
    * Пример: UserID, ArticleID --> `UserID` = ? AND `ArticleID` = ?
    */
    private function _GeneratePrimaryKeysSqlCondition() : string
    {
        $key = $this->_storage->tables->{$this->_table}->primaryKeys;

        // Если ключ составной, то экранируем кавычками каждое поле
        $this->_EscapeFields($key);

        // ...и заменяем запятые-разделители на AND
        if (strpos($key, ',') !== false)
        {
            $key = str_replace(', ', '= ? AND ', $key);
        }

        return "`{$key}` = ?";
    }

    /* Возвращает ключ первой записи в таблице или массив с элементом 0, если таблица пуста */
    private function _GetFirstRecordId() : array
    {
        $availableKeys = $this->_GetAvailableKeys();

        if (count($availableKeys) == 0)
        {
            return [ 0 ];
        }
        else
        {
            return $availableKeys[0];
        }
    }

    /* Проверяет, что поля запроса и их порядок соответствуют полям данной таблицы и их порядку */
    private function _CheckFieldsAccordance(array $parameters) : bool
    {
        $tableFields = array_column($this->_storage->tables->{$this->_table}->fields, 'name');

        // Переиндексируем массив, чтобы его индексы совпадали с индексами $parametersFields
        $tableFields = array_values($tableFields);

        $parametersFields = array_column(array_keys($parameters), null);

        $this->_lastErrorMessage = 'Order of fields or their names do not match fields of table!';

        return $tableFields === $parametersFields;
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
            $key = $this->_storage->tables->{$this->_table}->primaryKeys;

            // Если ключ составной, то экранируем кавычками каждое поле в отдельности
            $this->_EscapeFields($key);

            $statement = $this->_dataBase->query("SELECT `{$key}` FROM `{$this->_table}`");
            $availableKeys = $statement->fetchAll(\PDO::FETCH_NUM);

            sort($availableKeys);
        }

        return $availableKeys;
    }

    /* Экранирует кавычками каждое поле из списка, где поля разделены символами ' ,' */
    private function _EscapeFields(string &$fields) : void
    {
        if (strpos($fields, ',') !== false)
        {
            $fields = str_replace(', ', '`, `', $fields);
        }
    }

    /* Загружает в хранилище список таблиц, создает алиас для объекта соединения с базой данных */
    protected function _Initialize() : void
    {
        $this->_storage = $this->_services->storage;
        $this->_storage->LoadJsonFile('tables');

        $this->_dataBase = $this->_services->dataBase->connection;
    }

    private $_table = null;
    private $_recordId = [];

    private $_storage = null;
    private $_dataBase = null;

    private $_lastErrorMessage = null;
}
?>