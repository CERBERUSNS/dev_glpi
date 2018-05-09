<?php
/**
 * @version $Id: histoinst.php 357 2018-03-16 16:17:52Z yllen $
 -------------------------------------------------------------------------
  LICENSE

 This file is part of Reports plugin for GLPI.

 Reports is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 Reports is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with Reports. If not, see <http://www.gnu.org/licenses/>.

 @package   reports
 @authors    Nelly Mahu-Lasson, Remi Collet
 @copyright Copyright (c) 2009-2018 Reports plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/reports
 @link      http://www.glpi-project.org/
 @since     2009
 --------------------------------------------------------------------------
 */

$USEDBREPLICATE        = 1;
$DBCONNECTION_REQUIRED = 1; // Really a big SQL request

include ("../../../../inc/includes.php");

includeLocales("reporte15");

//Session::checkRight("plugin_reports_histoinst", READ);
$computer = new Computer();
$computer->checkGlobal(READ);
$software = new Software();
$software->checkGlobal(READ);

//TRANS: The name of the report = History of last software's installations
Html::header(__('histoinst_report_title', 'reports'), $_SERVER['PHP_SELF'], "utils", "report");

Report::title();

echo "<div class='center'>";
echo "<table class='tab_cadrehov' cellpadding='5' style='border:1px'>\n";
echo "<tr class='tab_bg_1 center'>".
      "<th colspan='4'>" . __("History of last software's installations", "reports") .
      "</th></tr>\n";

echo "<tr class='tab_bg_2'><th>". __('Categoria', 'reports') . "</th>" .
      "<th>". __('Totales','reports') . "</th>"
      //"<th>". __("Computer's name") . "</th>".
      //"<th>". sprintf(__('%1$s (%2$s)'), _n('Software', 'Software', 1), __('version'))."</th></tr>\n
      ;

$sql = "call proc_solu_cerra_despues_diez_dias(date(now()),last_day(now()))";

$result = $DB->request($sql);

//var_dump($result);
//die;

$prev = "";
$class = "tab_bg_2";
while ($data = $result->next()) {
   /*if (empty($data["Categorias"])) {
      $data["name"] = "(".$data["cid"].")";
   }
   if ($prev == $data["dat"].$data["name"]) {
      echo "<br />";
   } else {
      if (!empty($prev)) {
         echo "</td></tr>\n";
      }
*/     

      echo "<tr class='" . $class . "_top'>".
            "<td class='left'>".($data["Categoria"]) . "</td>" .
            "<td class='center'>".($data["total"]) . "</td>" ;
            //"<td>". $data["TotalesAbiertos"] . "&nbsp;</td>";
            
      $class = ($class=="tab_bg_2" ? "tab_bg_1" : "tab_bg_2");
  // }
   echo $data["new_value"];
}

if (!empty($prev)) {
   echo "</td></tr>\n";
}
echo "</table><p>". __('The list is limited to 200 items and 21 days', 'reports')."</p></div>\n";

Html::footer();