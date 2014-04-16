<?php
interface Dxcore_Controller_Action_Helper_WidgetInterface
{
    public function buildWidget();
    public function renderWidget(&$data);
}