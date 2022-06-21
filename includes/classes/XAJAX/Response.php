<?php

namespace XAJAX;

use xajaxResponse as base;

class Response extends base {
    public function renderTemplate($target, $template, $data = [], $attribute = 'innerHTML') {
        $templater = \App::templater();
        $templater->append($data);
        $body = $templater->fetch($template);

        $this->assign($target, $attribute, $body);
    }
}