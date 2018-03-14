<?php

Plugin::load('fields', true);

$dropdown = new PluginFieldsTipofieldDropdown();
include GLPI_ROOT . "/front/dropdown.common.php";
