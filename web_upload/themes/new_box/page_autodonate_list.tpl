<div class="container-alt" data-collapse-color="red" id="accordionRed" role="tablist" aria-multiselectable="true">
    <div class="text-center" id="div_to_pay">
        <h2 class="f-400" id="pay_info_header">Покупка привелегий</h2>
            <p class="c-gray m-t-20 m-b-20" id="pay_info">Выберите подходящий для вас тарив, подробно ознакомившись с доступными привелегиями предлагаемыми ниже. Для получения дополнительной информации данного тарифа, нажмите на значек "покупки".</p>
                <div id="pay" style="display:none;">
                    <div class="card">
                        {display_header title="Подробная информация по выбранному тарифу"}
                        <div class="card-body card-padding">
                            <div class="panel-group" data-collapse-color="cyan" id="trafik" role="tablist" aria-multiselectable="true">
                                {foreach from="$tariffs" item="tariff"}
                                {assign var="serveraccess" value=$tariff.server}
                                {assign var="webaccess" value=$tariff.web}
                                {assign var="serverdata" value=$tariff.serverdata}
                                <div class="panel panel-collapse">
                                    <div id="trafik_{$tariff.id}" class="collapse" role="tabpanel" aria-expanded="true">
                                        <!-- Tariff content (START) -->
                                            <div class="table-responsive">
                                                <table width="80%" border="0" class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td width="30%" valign="top">
                                                                <p class="c-blue">Веб-права</p>
                                                                <ul class="clist clist-star">
                                                                    {foreach from="$webaccess" item="access"}
                                                                    <li> {$access}</li>
                                                                    {/foreach}
                                                                </ul>
                                                            </td>
                                                            <td width="30%" valign="top">
                                                                <p class="c-blue">Серверные права</p>
                                                                <ul class="clist clist-star">
                                                                    <li>{$access}</li>
                                                                    {/foreach}
                                                                </ul>
                                                            </td>
                                                            <td valign="top">
                                                                <blockquote class="blockquote-reverse m-b-25">
                                                                    <footer>Покупка тарифа на игровой сервер</footer>
                                                                    <p><b id="st_name_{$tariff.id}">Загрузка...</b></p>
                                                                </blockquote>
                                                                <img id="st_map_{$tariff.id}" height="255" width="100%" src="images/maps/nomap.jpg"></img>
                                                                <br \><br \>
                                                                <div align="center">
                                                                    <b>IP:Порт - {$serverdata.ip}:{$serverdata.port}</b> 
                                                                    <br \><br \>
                                                                    <button type="submit" onclick="document.location = 'steam://connect/{$serverdata.ip}:{$serverdata.port}'" name="button" class="btn bgm-teal btn-block btn-icon-text waves-effect" id="button"><i class="zmdi zmdi-steam"></i> Подключиться</button>
                                                                    <br \><br \>
                                                                    <a href="index.php?p=autodonate&o=buy&id={$tariff.id}"><button type="button" name="button" class="btn bgm-amber btn-block btn-lg waves-effect">Купить на 30 Дней</button></a>
                                                                </div>
                                                                <br \>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <!-- Tariff content (END)-->
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
                    <div class="row m-t-25">
                        <div class="col-sm-4">
                            <div class="card pt-inner">
                                <div class="pti-header bgm-amber">
                                    <h2>$25
                                        <small>| Месяц</small>
                                    </h2>
                                    <div class="ptih-title">Название группы</div>
                                </div>

                                <div class="pti-body collapse" id="trafik_info_1">
                                    <div class="ptib-item">
                                        Плюс группы 1...
                                    </div>
                                    <div class="ptib-item">
                                        Плюс группы 2...
                                    </div>
                                    <div class="ptib-item">
                                        Плюс группы 3...
                                    </div>
                                    <div class="ptib-item">
                                        Плюс группы 4...
                                    </div>
                                    <div class="ptib-item">
                                        Плюс группы 5...
                                    </div>
                                </div>

                                <div class="pti-footer">
                                    <a href="#trafik_info_1" aria-expanded="false" aria-controls="trafik_info_1"  data-toggle="collapse" class="bgm-amber" style="font-size: 40px;padding-top: 8px;"><i class="zmdi zmdi-menu"></i></a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
									<a data-toggle="collapse" data-parent="#trafik" href="#trafik_1" aria-expanded="true" class="bgm-amber" onclick="open_tarif('pay');" style="font-size: 40px;padding-top: 8px;"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
								</div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="card pt-inner">
                                <div class="pti-header bgm-green">
                                    <h2>$25
                                        <small>| Месяц</small>
                                    </h2>
                                    <div class="ptih-title">Название группы</div>
                                </div>

                                <div class="pti-body collapse" id="trafik_info_2">
                                    <div class="ptib-item">
                                        Плюс группы 1...
                                    </div>
                                    <div class="ptib-item">
                                        Плюс группы 2...
                                    </div>
                                    <div class="ptib-item">
                                        Плюс группы 3...
                                    </div>
                                    <div class="ptib-item">
                                        Плюс группы 4...
                                    </div>
                                    <div class="ptib-item">
                                        Плюс группы 5...
                                    </div>
                                </div>

                                <div class="pti-footer">
                                    <a href="#trafik_info_2" aria-expanded="false" aria-controls="trafik_info_2"  data-toggle="collapse" class="bgm-amber" style="font-size: 40px;padding-top: 8px;"><i class="zmdi zmdi-menu"></i></a>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
									<a data-toggle="collapse" data-parent="#trafik" href="#trafik_2" aria-expanded="true" class="bgm-amber" onclick="open_tarif('pay');" style="font-size: 40px;padding-top: 8px;"><i class="zmdi zmdi-shopping-cart-plus"></i></a>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
