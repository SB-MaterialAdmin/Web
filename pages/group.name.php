<?php 
if(!defined("IN_SB")){echo "Ошибка доступа!";die();} 
?>

<div class="col-xs-12 p-b-10">
<table width="100%" border="0" id="group.name">
  <tr>
    <td width="15%">Имя группы:</td>
    <td>
       <input type="text" class="submit-fields" id="{name}" name="{name}" />
       <div id="{name}_err" class="badentry"></div>
    </td>
  </tr>
 </table>
</div>