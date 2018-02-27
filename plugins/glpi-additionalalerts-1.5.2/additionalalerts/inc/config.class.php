<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 Additionalalerts plugin for GLPI
 Copyright (C) 2003-2011 by the Additionalalerts Development Team.

 https://forge.indepnet.net/projects/additionalalerts
 -------------------------------------------------------------------------

 LICENSE
		
 This file is part of Additionalalerts.

 Additionalalerts is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Additionalalerts is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with additionalalerts. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginAdditionalalertsConfig extends CommonDBTM {
	
	function canCreate() {
      return plugin_additionalalerts_haveRight("additionalalerts", 'w');
   }

   function canView() {
      return plugin_additionalalerts_haveRight("additionalalerts", 'r');
   }
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG,$CFG_GLPI;

      if ($item->getType()=='NotificationMailSetting' 
            && $item->getField('id') 
               && $CFG_GLPI["use_mailing"]) {
            return $LANG['plugin_additionalalerts']['title'][1];
      } else if ($item->getType()=='Entity') {
            return $LANG['plugin_additionalalerts']['title'][1];
      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      global $CFG_GLPI;

      if ($item->getType()=='NotificationMailSetting') {

         $target = $CFG_GLPI["root_doc"]."/plugins/additionalalerts/front/config.form.php";
         self::showFormAlerts($target);
         
      } else if ($item->getType()=='Entity') {
      
         PluginAdditionalalertsInfocomAlert::showNotificationOptions($item);
         PluginAdditionalalertsOcsAlert::showNotificationOptions($item);
         
      }
      return true;
   }
   
   static function showFormAlerts($target) {
      global $LANG;
       
      $self = new self();
      $self->getFromDB(1);
      
      echo "<form action='$target' method='post'>";
      echo "<input type='hidden' name='id' value='1'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_2'>";
      echo "<td>" . $LANG['setup'][245] . " " . $LANG['plugin_additionalalerts']['alert'][11] . "</td><td>";
      Alert::dropdownYesNo(array('name'=>"use_infocom_alert",
                              'value'=>$self->fields["use_infocom_alert"]));
      echo "</td></tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td >" . $LANG['setup'][245] . " " . $LANG['plugin_additionalalerts']['alert'][9] . "</td><td>";
      Alert::dropdownYesNo(array('name'=>"use_newocs_alert",
                              'value'=>$self->fields["use_newocs_alert"]));
      echo "</td></tr>";

      echo "<tr class='tab_bg_2'><td >" . $LANG['plugin_additionalalerts']['setup'][12] . "</td><td>";
      Alert::dropdownIntegerNever('delay_ocs',
                                  $self->fields["delay_ocs"],
                                  array('max'=>99));
      echo "&nbsp;".$LANG['calendar'][12]."</td></tr>";
      echo "</td></tr>";

      echo "<tr class='tab_bg_2'><td class='center' colspan='2'>";
      echo "<input type='hidden' name='id' value='1'>";
      echo "<input class='submit' type='submit' name='update' value='".$LANG['buttons'][2]."'>";
      echo "</td></tr>";
      echo "</table>";
      Html::closeForm();
   }
}

?>