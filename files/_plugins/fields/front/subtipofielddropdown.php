<?php

Plugin::load('fields', true);

$dropdown = new PluginFieldsSubtipofieldDropdown();
include GLPI_ROOT . "/front/dropdown.common.php";
