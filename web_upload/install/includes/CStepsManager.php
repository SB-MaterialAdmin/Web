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

class CStepsManager {
    private $steps = array();

    public function addStep($stepName) {
        $this->steps[] = array(
            'name'      => $stepName,
            'state'     => 'green',
            'desc'      => 'Текущий шаг',
            'completed' => false
        );
    }

    public function setState($stepNumber, $stepDesc, $stepState, $stepCompleted) {
        $stepNumber--;
        if (!isset($this->steps[$stepNumber]))
            return false;

        $this->steps[$stepNumber]['desc'] = $stepDesc;
        $this->steps[$stepNumber]['state'] = $stepState;
        $this->steps[$stepNumber]['completed'] = $stepCompleted;
        return true;
    }
    
    public function render() {
        $stepCount = 1;
        include(TEMPLATES_PATH . '/steps_start.php');
        foreach ($this->steps as &$step) {
            include(TEMPLATES_PATH . '/step.php');
            $stepCount++;
        }
        include(TEMPLATES_PATH . '/steps_end.php');
    }
}
