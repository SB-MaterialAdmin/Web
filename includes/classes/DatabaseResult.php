<?php
class DatabaseResult {
  private $Statement;
  private $Result;

  public function __construct($Statement) {
    $this->Statement = $Statement;
  }

  public function Execute($data = null) {
    $this->Result = $this->Statement->execute($data);
  }

  public function EndData() {
    $this->Statement->closeCursor();
    $this->Result = false;
  }

  /**
   * @section Fetching
   */
  public function Single() {
    return $this->Statement->fetch(\PDO::FETCH_ASSOC);
  }

  public function All() {
    $Res = [];
    while ($Row = $this->Single())
      $Res[] = $Row;

    return $Res;
  }

  public function RowCount() {
    return $this->Statement->rowCount();
  }

  public function NextRowSet() {
    return $this->Statement->nextRowSet();
  }

  /**
   * @section Bindings
   */
  public function BindData($name, $value, $type = NULL) {
    if ($type === NULL) {
      if (is_int($value))
        $type = \PDO::PARAM_INT;
      else if (is_bool($value))
        $type = \PDO::PARAM_BOOL;
      else if (is_null($value))
        $type = \PDO::PARAM_NULL;
      else
        $type = \PDO::PARAM_STR;
    }

    $this->Statement->bindValue($name, $value, $type);
  }

  public function BindMultipleData($data) {
    foreach ($data as $key => $value)
      $this->BindData($key, $value);
  }

  /**
   * @section Error Handling
   */
  public function ErrorCode() {
    return $this->Statement->errorCode();
  }

  public function ErrorInfo() {
    return $this->Statement->errorInfo();
  }

  public function IsSuccess() {
    return $this->Result;
  }

  /**
   * @section Debug
   */
  public function QueryString() {
    return $this->Statement->queryString;
  }

  public function DumpParams() {
    return $this->Statement->debugDumpParams();
  }
}