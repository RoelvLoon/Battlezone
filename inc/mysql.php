<?php
require_once dirname(__FILE__) . '/config.php';

class modDB
{

    var $myDB;
    public function __construct()
    {
        global $mainDB;
        if ($mainDB == null)
        {
            $mainDB = mysqli_connect(_MYSQL_HOST, _MYSQL_USER, _MYSQL_PASS, _MYSQL_DB) or die('Er is een fout opgetreden - Kon geen verbinding maken met de databaseserver. Probeer het later opnieuw');
        }
        $this->myDB = $mainDB;
    }

    public function QueryArray($strQuery)
    {
        // Voer de query uit en retourneer de resultatenset als array
        $myQry = $this->Query($strQuery);
        while ($myRow = $this->Fetch($myQry))
        {
            $ret[] = $myRow;
        }
        return $ret;
    }

    public function QuerySingle($strQuery)
    {
        $query = $this->Query($strQuery);
        if (!is_bool($query)) return mysqli_fetch_array($query, MYSQLI_ASSOC);
    }

    public function Fetch(&$mysqlQuery)
    {
        return mysqli_fetch_array($mysqlQuery, MYSQLI_ASSOC);
    }

    public function Query($strQuery)
    {
        return mysqli_query($this->myDB, $strQuery);
    }

    public function Delete($table, $conditionArray)
    {

        $query = 'DELETE FROM `' . $table . '` WHERE';
        $intCount = 0;
        foreach ($conditionArray as $fieldName => $fieldValue)
        {
            if ($intCount > 0) $query .= ' AND ';
            $query .= '`' . $fieldName . '` = \'' . mysqli_real_escape_string($this->myDB, $fieldValue) . '\'';
            $intCount++;
        }
        return mysqli_query($this->myDB, $query);
    }

    public function Insert($table, $fieldArray)
    {
        $query = 'INSERT INTO `' . $table . '` (';
        $intCount = 0;
        foreach ($fieldArray as $fieldName => $fieldValue)
        {
            if ($intCount > 0) $query .= ', ';
            if (substr($fieldName, 0, 1) == '!')
            {
                $query .= '`' . substr($fieldName, 1) . '`';
            }
            else
            {
                $query .= '`' . $fieldName . '`';
            }
            $intCount++;
        }
        $query .= ') VALUES (';
        $intCount = 0;
        foreach ($fieldArray as $fieldName => $fieldValue)
        {
            if ($intCount > 0) $query .= ', ';
            if (substr($fieldName, 0, 1) == '!')
            {
                $query .= mysqli_real_escape_string($this->myDB, $fieldValue);
            }
            else
            {
                $query .= '\'' . mysqli_real_escape_string($this->myDB, $fieldValue) . '\'';
            }
            $intCount++;
        }
        $query .= ')';

        $myQry = mysqli_query($this->myDB, $query);
        return mysqli_insert_id($this->myDB);

    }

    public function Update($table, $fieldArray, $conditionArray)
    {

        $query = 'UPDATE `' . $table . '` SET ';
        $intCount = 0;
        foreach ($fieldArray as $fieldName => $fieldValue)
        {
            if ($intCount > 0) $query .= ', ';
            if (substr($fieldName, 0, 1) == '!')
            {
                $query .= '`' . substr($fieldName, 1) . '`=' . mysqli_real_escape_string($this->myDB, $fieldValue);
            }
            else
            {
                $query .= '`' . $fieldName . '`=\'' . mysqli_real_escape_string($this->myDB, $fieldValue) . '\'';
            }
            $intCount++;
        }
        $intCount = 0;
        $query .= ' WHERE ';
        foreach ($conditionArray as $fieldName => $fieldValue)
        {
            if ($intCount > 0) $query .= ' AND ';
            $query .= '`' . $fieldName . '` = \'' . mysqli_real_escape_string($this->myDB, $fieldValue) . '\'';
            $intCount++;
        }
        return mysqli_query($this->myDB, $query);
    }

    public function Count($query)
    {
        $query = 'SELECT COUNT(*) as cnt FROM (' . $query . ') as tDerivedCount';
        $count = $this->QuerySingle($query);
        return (!empty($count['cnt']) ? $count['cnt'] : 0);
    }

    public function Escape($string)
    {
        return mysqli_real_escape_string($this->myDB, $string);
    }
    public function Error()
    {
        return mysqli_error($this->myDB);
    }
}
?>