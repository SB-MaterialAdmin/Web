<?php 
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
?>

<div class="table-responsive">
   <table cellspacing="0" cellpadding="0" class="table">
      <tbody>
         <tr>
            <th width="30%">Имя</th>
            <th width="5%">Флаг</th>
            <th width="50%">Назначение</th>
            <th width="15%">Включено</th>
         </tr>
         <tr id="srootcheckbox" name="srootcheckbox">
            <td>Главный админ (Полный доступ)</td>
            <td>z</td>
            <td> Включает в себя все флаги.</td>
            <td>
               <div class="checkbox">
                  <label for="s14">
                  <input type="checkbox" name="s14" id="s14" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>           
         </tr>
         <tr>
            <th colspan="5">Стандартные разрешения администратора сервера </th>
         </tr>
         <tr>
            <td>Резервные слоты </td>
            <td>a</td>
            <td> Резервный слот доступа.</td>
            <td>
               <div class="checkbox">
                  <label for="s1">
                  <input type="checkbox" name="s1" id="s1" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Администратор</td>
            <td>b</td>
            <td>Рядовой администратор; требуется для администраторов.</td>
            <td>
               <div class="checkbox">
                  <label for="s23">
                  <input type="checkbox" name="s23" id="s23" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>            	
         </tr>
         <tr>
            <td>Кик</td>
            <td>c</td>
            <td>Кик других игроков.</td>
            <td>
               <div class="checkbox">
                  <label for="s2">
                  <input type="checkbox" name="s2" id="s2" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Бан </td>
            <td>d</td>
            <td>Бан игроков.</td>
            <td>
               <div class="checkbox">
                  <label for="s3">
                  <input type="checkbox" name="s3" id="s3" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Разбан</td>
            <td>e</td>
            <td>Разбан игроков.</td>
            <td>
               <div class="checkbox">
                  <label for="s4">
                  <input type="checkbox" name="s4" id="s4" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Убить</td>
            <td>f</td>
            <td>Убить/нанести вред игроку.</td>
            <td>
               <div class="checkbox">
                  <label for="s5">
                  <input type="checkbox" name="s5" id="s5" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Смена карт </td>
            <td>g</td>
            <td>Изменение карты или основных особенностей геймплея.</td>
            <td>
               <div class="checkbox">
                  <label for="s6">
                  <input type="checkbox" name="s6" id="s6" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Квар </td>
            <td>h</td>
            <td>Изменение кваров.</td>
            <td>
               <div class="checkbox">
                  <label for="s7">
                  <input type="checkbox" name="s7" id="s7" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Конфиг</td>
            <td>i</td>
            <td>Выполнение конфигурационных файлов.</td>
            <td>
               <div class="checkbox">
                  <label for="s8">
                  <input type="checkbox" name="s8" id="s8" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Чат админа</td>
            <td>j</td>
            <td>Спец привелегии в чате.</td>
            <td>
               <div class="checkbox">
                  <label for="s9">
                  <input type="checkbox" name="s9" id="s9" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Голосование</td>
            <td>k</td>
            <td>Управление голосованиями.</td>
            <td>
               <div class="checkbox">
                  <label for="s10">
                  <input type="checkbox" name="s10" id="s10" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Пароль сервера</td>
            <td>l</td>
            <td>Установка пароля на сервер.</td>
            <td>
               <div class="checkbox">
                  <label for="s11">
                  <input type="checkbox" name="s11" id="s11" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>RCON </td>
            <td>m</td>
            <td>Выполнение RCON команд.</td>
            <td>
               <div class="checkbox">
                  <label for="s12">
                  <input type="checkbox" name="s12" id="s12" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Читы</td>
            <td>n</td>
            <td>Изменение sv_cheats или использование читов.</td>
            <td>
               <div class="checkbox">
                  <label for="s13">
                  <input type="checkbox" name="s13" id="s13" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <th colspan="5" class="tablerow4">Иммунитет </th>
         </tr>
         <tr>
            <td>Иммунитет </td>
            <td>-</td>
            <td>
				<div class="fg-line">
					<input type="hidden" id="fromsub" value="">
					<input type="number" tabindex="1" class="form-control" id="immunity" name="immunity" placeholder="Введите уровень иммунитета.">
				</div>               
            </td>
            <td align="center"></td>
         </tr>
         <tr>
            <th colspan="5" class="tablerow4">Пользовательские разрешения администратора сервера</th>
         </tr>
         <tr>
            <td>Пользовательский флаг</td>
            <td>o</td>
            <td>-</td>
            <td>
               <div class="checkbox">
                  <label for="s17">
                  <input type="checkbox" name="s17" id="s17" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Пользовательский флаг</td>
            <td>p</td>
            <td>-</td>
            <td>
               <div class="checkbox">
                  <label for="s18">
                  <input type="checkbox" name="s18" id="s18" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Пользовательский флаг</td>
            <td>q</td>
            <td>-</td>
            <td>
               <div class="checkbox">
                  <label for="s19">
                  <input type="checkbox" name="s19" id="s19" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Пользовательский флаг</td>
            <td>r</td>
            <td>-</td>
            <td>
               <div class="checkbox">
                  <label for="s20">
                  <input type="checkbox" name="s20" id="s20" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Пользовательский флаг</td>
            <td>s</td>
            <td>-</td>
            <td>
               <div class="checkbox">
                  <label for="s21">
                  <input type="checkbox" name="s21" id="s21" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
         <tr>
            <td>Пользовательский флаг</td>
            <td>t</td>
            <td>-</td>
            <td>
               <div class="checkbox">
                  <label for="s22">
                  <input type="checkbox" name="s22" id="s22" hidden="hidden" />
                  <i class="input-helper"></i>
                  </label>	
               </div> 
            </td>
         </tr>
      </tbody>
   </table>
</div>