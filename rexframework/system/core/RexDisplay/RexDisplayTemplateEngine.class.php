<?php

interface RexDisplayTemplateEngine
{
    public function assign($name, $value);
    public function display($template);
    public function fetch($template);
    public function getVar($name);
}
