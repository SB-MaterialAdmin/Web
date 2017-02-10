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

class CSystemLog {
	var $log_list = array();
	var $type = "";
	var $title = "";
	var $msg = "";
	var $aid = 0;
	var $host = "";
	var $created = 0;
	var $parent_function = "";
	var $query = "";
	
	function __construct($tpe="", $ttl="", $mg="", $done=true, $HideDebug = false)
	{
		global $userbank;
		if(!empty($tpe) && !empty($ttl) && !empty($mg))
		{
			$this->type = $tpe;
			$this->title = $ttl;
			$this->msg = $mg;
			// if (!$HideDebug && ((isset($_GET['debug']) && $_GET['debug'] == 1) || defined("DEVELOPER_MODE")))
			// {
				// echo "CSystemLog: " . $mg;
			// }
			
			if( !$userbank )
				return false;
			
			$this->aid =  $userbank->GetAid()?$userbank->GetAid():"-1";
			$this->host = $_SERVER['REMOTE_ADDR'];
			$this->created = time(); 
			$this->parent_function = $this->_getCaller();
			$this->query = isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'';
			if(isset($done) && $done == true)
				$this->WriteLog();
		}				
	}
	
	function AddLogItem($tpe, $ttl, $mg)
	{
		$item = array();
		$item['type'] = $tpe;
		$item['title'] = $ttl;
		$item['msg'] = $mg;
		$item['aid'] =  SB_AID;
		$item['host'] = $_SERVER['REMOTE_ADDR'];
		$item['created'] = time(); 
		$item['parent_function'] = $this->_getCaller();
		$item['query'] = $_SERVER['QUERY_STRING'];
		
		array_push($this->log_list, $item);
	}
	
	function WriteLogEntries()
	{
		$this->log_list = array_unique($this->log_list);
		foreach($this->log_list as $logentry)
		{
			if(!$logentry['query'])
				$logentry['query'] = "N/A";
			if(isset($GLOBALS['db']))
			{
				$sm_log_entry = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_log(type,title,message, function, query, aid, host, created)
						VALUES (?,?,?,?,?,?,?,?)");
				$GLOBALS['db']->Execute($sm_log_entry,array($logentry['type'], $logentry['title'], $logentry['msg'], (string)$logentry['parent_function'],$logentry['query'], $logentry['aid'], $logentry['host'], $logentry['created']));
			}
		}
		unset($this->log_list);
	}
	
	function WriteLog()
	{
		if(!$this->query)
			$this->query = "N/A";
		if(isset($GLOBALS['db']))
		{
			$sm_log_entry = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_log(type,title,message, function, query, aid, host, created)
						VALUES (?,?,?,?,?,?,?,?)");
			$GLOBALS['db']->Execute($sm_log_entry,array($this->type, $this->title, $this->msg, (string)$this->parent_function,$this->query, $this->aid, $this->host, $this->created));
		}
	}
	
	function _getCaller()
	{
		$bt = debug_backtrace();
	
		$functions = "";
		$count = count($bt);
		for ($idx = 2; $idx<$count; $idx++)
			if ($bt[$idx]['function'] != "sbError")
				$functions .= "<b>". ($count-$idx) . "</b>: " . str_replace(ROOT, "/", $bt[$idx]['file']) . "::".$bt[$idx]['function']."(".$this->FormatArguments($bt[$idx]['args']).") - " . $bt[$idx]['line'] . "<br />\n";
		return $functions;
	}
	
	function GetAll($start, $limit, $searchstring="")
	{
		if( !is_object($GLOBALS['db']) )
				return false;
				
		$start = (int)$start;
		$limit = (int)$limit;
		$sm_logs = $GLOBALS['db']->GetAll("SELECT ad.user, l.type, l.title, l.message, l.function, l.query, l.host, l.created, l.aid 
										   FROM ".DB_PREFIX."_log AS l
										   LEFT JOIN ".DB_PREFIX."_admins AS ad ON l.aid = ad.aid
										   ".$searchstring."
										   ORDER BY l.created DESC 
										   LIMIT $start, $limit");
		return $sm_logs;
	}
	
	function LogCount($searchstring="")
	{
		$sm_logs = $GLOBALS['db']->GetRow("SELECT count(l.lid) AS count FROM ".DB_PREFIX."_log AS l".$searchstring);
		return $sm_logs[0];
	}
	
	function CountLogList()
	{
		return count($this->log_list);
	}
	
	/* Log Helpers for args logger */
	function FormatArguments($args) {
		$argsV2 = [];
		foreach ($args as $arg)
			$argsV2[] = $this->FormatArgument($arg);
		return implode(", ", $argsV2);
	}
	
	function GetEntryType($entry) {
		$type = gettype($entry);
		if ($type == "boolean") return 4;
		if ($type == "integer" || $type == "double") return 1;
		if ($type == "string") return 0;
		if ($type == "array") return 2;
		if ($type == "NULL") return 5;
		if ($type == "object") return 3;
		return -1;
	}
	
	function FormatArgument($arg) {
		$et = $this->GetEntryType($arg);
		$log = htmlentities((($et==2)?$this->PrepareArray($arg):(($et == 0)?"'".$arg."'":($et==3?sprintf("Object %s", get_class($arg)):$arg))));
		
		if (strlen($log) > 256)
			$log = sprintf("%s...%s", substr($log_prepared, 0, 256), ($et==0)?"'":"");
		
		return $log;
	}
	
	function PrepareArray($array) {
		if (gettype($array) != "array") return $array;
		$result = "[";
		foreach ($array as $Key => $Entry) {
			$result .= $this->FormatArgument($Entry);
			$result .= ", ";
		}
		return str_replace(", ]", "]", $result."]");
	}
}

?>
