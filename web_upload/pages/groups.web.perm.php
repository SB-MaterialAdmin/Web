<?php 
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}

?>

<div class="table-responsive">
   <table cellspacing="0" cellpadding="0" class="table">
      <tbody>
         <tr>
            <th>Название</th>
            <th>Включено</th>
         </tr>
         <tr id="wrootcheckbox" name="wrootcheckbox">
            <td width="80%">Главный админ (Полный доступ)</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p2">
                  <input type="checkbox" name="p2" id="p2" hidden="hidden" onclick="UpdateCheckBox(2, 3, 39);" />
                  <i class="input-helper"></i>
                  </label>	
               </div>
            </td>
         </tr>
         <tr class="main">
            <td width="80%">Управление админами </td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p3">
                  <input type="checkbox" name="p3" id="p3" hidden="hidden" onclick="UpdateCheckBox(3, 4, 7);" />
                  <i class="input-helper"></i>
                  </label>	
               </div>            	
            </td>
         </tr>
         <tr>
            <td width="80%">Просмотр админов </td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p4">
                  <input type="checkbox" name="p4" id="p4" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>               	
            </td>
         </tr>
         <tr>
            <td width="80%">Добавление админов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p5">
                  <input type="checkbox" name="p5" id="p5" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Редактирование админов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p6">
                  <input type="checkbox" name="p6" id="p6" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Удаление админов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p7">
                  <input type="checkbox" name="p7" id="p7" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
             <td width="80%">Выдача предупреждений админам</td>
             <td width="20%">
                 <div class="checkbox">
                     <label for="p15">
                         <input type="checkbox" name="p15" id="p15" hidden="hidden" />
                         <i class="input-helper"></i>
                     </label>
                 </div>
             </td>
         </tr>
         <tr class="main">
            <td width="80%">Управление серверами</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p8">
                  <input type="checkbox" name="p8" id="p8" hidden="hidden" onclick="UpdateCheckBox(8, 9, 12);" />
                  <i class="input-helper"></i>
                  </label>	
               </div>             	
            </td>
         </tr>
         <tr>
            <td width="80%">Просмотр серверов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p9">
                  <input type="checkbox" name="p9" id="p9" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Добавление серверов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p10">
                  <input type="checkbox" name="p10" id="p10" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Редактирование серверов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p11">
                  <input type="checkbox" name="p11" id="p11" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Удаление серверов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p12">
                  <input type="checkbox" name="p12" id="p12" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr class="main">
            <td width="80%">Управление банами</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p13">
                  <input type="checkbox" name="p13" id="p13" hidden="hidden" onclick="UpdateCheckBox(13, 14, 20, 32, 33, 34, 38, 39);" />
                  <i class="input-helper"></i>
                  </label>	
               </div>             	
            </td>
         </tr>         
         <tr>
            <td width="80%">Добавление банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p14">
                  <input type="checkbox" name="p14" id="p14" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Редактирование своих банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p16">
                  <input type="checkbox" name="p16" id="p16" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Редактирование банов групп</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p17">
                  <input type="checkbox" name="p17" id="p17" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Редактирование всех банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p18">
                  <input type="checkbox" name="p18" id="p18" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Протесты банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p19">
                  <input type="checkbox" name="p19" id="p19" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Предложения банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p20">
                  <input type="checkbox" name="p20" id="p20" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Разбан своих банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p38">
                  <input type="checkbox" name="p38" id="p38" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Разбан банов групп</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p39">
                  <input type="checkbox" name="p39" id="p39" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Разбан всех банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p32">
                  <input type="checkbox" name="p32" id="p32" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Удаление банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p33">
                  <input type="checkbox" name="p33" id="p33" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Импорт банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p34">
                  <input type="checkbox" name="p34" id="p34" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr class="main">
            <td width="80%">Управление Группами</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p21">
                  <input type="checkbox" name="p21" id="p21" hidden="hidden" onclick="UpdateCheckBox(21, 22, 25);" />
                  <i class="input-helper"></i>
                  </label>	
               </div>             	
            </td>
         </tr>          
         <tr>
            <td width="80%">Просмотр групп</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p22">
                  <input type="checkbox" name="p22" id="p22" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Добавление групп</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p23">
                  <input type="checkbox" name="p23" id="p23" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Редактирование групп</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p24">
                  <input type="checkbox" name="p24" id="p24" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Удаление групп</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p25">
                  <input type="checkbox" name="p25" id="p25" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr class="main">
            <td width="80%">Email уведомления</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p35">
                  <input type="checkbox" name="p35" id="p35" hidden="hidden" onclick="UpdateCheckBox(35, 36, 37);" />
                  <i class="input-helper"></i>
                  </label>	
               </div>             	
            </td>
         </tr>          
         <tr>
            <td width="80%">Уведомления о предложениях банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p36">
                  <input type="checkbox" name="p36" id="p36" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>
         <tr>
            <td width="80%">Уведомления о протестах банов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p37">
                  <input type="checkbox" name="p37" id="p37" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr> 
         <tr>
            <td width="80%">Настройки ВЕБ панели SourceBans</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p26">
                  <input type="checkbox" name="p26" id="p26" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr> 
         <tr class="main">
            <td width="80%">Управление МОДами</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p27">
                  <input type="checkbox" name="p27" id="p27" hidden="hidden" onclick="UpdateCheckBox(27, 28, 31);" />
                  <i class="input-helper"></i>
                  </label>	
               </div>             	
            </td>
         </tr> 
         <tr>
            <td width="80%">Просмотр МОДов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p28">
                  <input type="checkbox" name="p28" id="p28" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr>                                  
         <tr>
            <td width="80%">Добавление МОДов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p29">
                  <input type="checkbox" name="p29" id="p29" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr> 
         <tr>
            <td width="80%">Редактирование МОДов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p30">
                  <input type="checkbox" name="p30" id="p30" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr> 
         <tr>
            <td width="80%">Удаление МОДов</td>
            <td width="20%">
               <div class="checkbox">
                  <label for="p31">
                  <input type="checkbox" name="p31" id="p31" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div>                	
            </td>
         </tr> 
      </tbody>
   </table>
</div>