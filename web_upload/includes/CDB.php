<?php
/**************************************************************************
 * Эта программа является частью SourceBans MATERIAL Admin.
 *
 * Все права защищены © 2016-2017 Sergey Gut <webmaster@kruzefag.ru>
 *
 * SourceBans MATERIAL Admin распространяется под лицензией
 * Creative Commons Attribution-NonCommercial-ShareAlike 3.0.
 *
 * Вы должны были получить копию лицензии вместе с этой работой. Если нет,
 * см. <http://creativecommons.org/licenses/by-nc-sa/3.0/>.
 *
 * ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО
 * ГАРАНТИЙ, ЯВНЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ,
 * ГАРАНТИИ ПРИГОДНОСТИ ДЛЯ КОНКРЕТНЫХ ЦЕЛЕЙ И НЕНАРУШЕНИЯ. НИ ПРИ КАКИХ
 * ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ЗА
 * ЛЮБЫЕ ПРЕТЕНЗИИ, ИЛИ УБЫТКИ, НЕЗАВИСИМО ОТ ДЕЙСТВИЯ ДОГОВОРА,
 * ГРАЖДАНСКОГО ПРАВОНАРУШЕНИЯ ИЛИ ИНАЧЕ, ВОЗНИКАЮЩИЕ ИЗ, ИЛИ В СВЯЗИ С
 * ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ
 * ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ.
 *
 * Эта программа базируется на работе, охватываемой следующим авторским
 *                                                           правом (ами):
 *
 *  * SourceBans ++
 *    Copyright © 2014-2016 Sarabveer Singh
 *    Выпущено под лицензией CC BY-NC-SA 3.0
 *    Страница: <https://sbpp.github.io/>
 *
 ***************************************************************************/

class CPDO extends PDO {
    public function __construct($dsn, $username, $password, $options) {
        parent::__construct($dsn, $username, $password, $options);
    }
    
    private function checkQuery($query, $params) {
        $hStatement = parent::prepare($query);
        $res = $hStatement->execute($params);
        if (!$res)
            return false;
        
        return $hStatement;
    }

    /* Scratches */
    public function GetRow($query, $params = array()) {
        $hStatement = $this->checkQuery($query, $params);
        if (!$hStatement)
            return null;

        $row = $hStatement->fetch(PDO::FETCH_LAZY);
        return $row;
    }

    public function GetOne($query, $params = array()) {
        $Row = $this->GetRow($query, $params);
        return $Row[0];
    }

    public function GetAll($query, $params = array()) {
        $hStatement = $this->checkQuery($query, $params);
        if (!$hStatement)
            return null;

        $data = array();
        while ($row = $hStatement->fetch(PDO::FETCH_LAZY)) {
            $data[] = $row;
        }
        return $data;
    }

    public function Prepare($query, $options = NULL) {
        return parent::prepare($query);
    }

    public function Execute($statement, $params = array()) {
        // ob_end_clean();
        // var_dump(debug_backtrace());
        // exit(0);
        if (is_string($statement))
            $statement = $this->prepare($statement);

        if (get_class($statement) !== "PDOStatement")
            return false;

        return new CPDO_Result($statement, $params);
    }

    public function qstr($string) {
        if (is_array($string))
            return "'Array'";
        else if (is_string($string))
            return parent::quote($string);
        else if (is_object($string))
            return $this->qstr(get_class($string));
        else
            return $string;
    }
}

class CPDO_Result {
    private $hStatement;
    protected $EOF;
    protected $values;

    public function __construct($hStatement, $Params = array()) {
        $this->hStatement = $hStatement;
        $this->hStatement->execute($Params);
        $this->EOF = $this->hStatement->nextRowset();
        
        if ($this->EOF) {
            $this->MoveNext();
        }
    }

    public function MoveNext() {
        $this->values = $this->hStatement->fetch(PDO::FETCH_LAZY);
        $this->EOF = $this->hStatement->nextRowset();
    }
}
