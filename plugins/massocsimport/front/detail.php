<?php
/*
 * @version $Id: detail.php 135 2011-11-08 11:34:36Z remi $
 -------------------------------------------------------------------------
 massocsimport - Massive OCS import plugin for GLPI
 Copyright (C) 2003-2011 by the massocsimport Development Team.

 https://forge.indepnet.net/projects/massocsimport
 -------------------------------------------------------------------------

 LICENSE

 This file is part of massocsimport.

 massocsimport is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 massocsimport is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with massocsimport. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT . "/inc/includes.php");

Session::checkRight("logs", "r");
Html::header($LANG["massocsimport"]["common"][21], $_SERVER["PHP_SELF"], "plugins", "massocsimport",
             "details");

Search::show('PluginMassocsimportDetail');

Html::footer();
?>
