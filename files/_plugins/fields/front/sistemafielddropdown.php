<?php

Plugin::load('fields', true);

$dropdown = new PluginFieldsSistemafieldDropdown();
include GLPI_ROOT . "/front/dropdown.common.php";
