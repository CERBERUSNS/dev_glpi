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

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

// Class NotificationTarget
class PluginAdditionalalertsNotificationTargetOcsAlert extends NotificationTarget {

   function getEvents() {
      global $LANG;
      return array ('ocs' => $LANG['plugin_additionalalerts']['mailing'][5],
                     'newocs' => $LANG['plugin_additionalalerts']['alert'][9]);
   }

   function getDatasForTemplate($event,$options=array()) {
      global $LANG, $CFG_GLPI;

      $this->datas['##ocsmachine.entity##'] =
                        Dropdown::getDropdownName('glpi_entities',
                                                  $options['entities_id']);
      $this->datas['##lang.ocsmachine.entity##'] =$LANG['entity'][0];
      
      $events = $this->getAllEvents();
      
      $delay_ocs=$options["delay_ocs"];
			
      if ($event=="newocs")
         $this->datas['##lang.ocsmachine.title##'] = $events[$event];
      else
         $this->datas['##lang.ocsmachine.title##'] = $LANG['plugin_additionalalerts']['alert'][1]." ".$delay_ocs." ".$LANG['plugin_additionalalerts']['setup'][15];
      $this->datas['##lang.ocsmachine.name##'] = $LANG['common'][16];
      $this->datas['##lang.ocsmachine.urlname##'] = "URL";
      $this->datas['##lang.ocsmachine.operatingsystem##'] = $LANG['computers'][9];
      $this->datas['##lang.ocsmachine.state##'] = $LANG['state'][0];
      $this->datas['##lang.ocsmachine.location##'] = $LANG['common'][15];
      $this->datas['##lang.ocsmachine.user##'] = $LANG['common'][34]." / ".$LANG['common'][35]." / ".$LANG['common'][18];
      $this->datas['##lang.ocsmachine.urluser##'] = "URL";
      $this->datas['##lang.ocsmachine.urlgroup##'] = "URL";
      $this->datas['##lang.ocsmachine.lastocsupdate##'] = $LANG['ocsng'][14];
      $this->datas['##lang.ocsmachine.lastupdate##'] = $LANG['ocsng'][13];
      $this->datas['##lang.ocsmachine.ocsserver##'] = $LANG['ocsng'][29];
      
      foreach($options['ocsmachines'] as $id => $ocsmachine) {
         $tmp = array();
         
         $tmp['##ocsmachine.urlname##'] = urldecode($CFG_GLPI["url_base"]."/index.php?redirect=computer_".
                                    $ocsmachine['id']);
         $tmp['##ocsmachine.name##'] = $ocsmachine['name'];
         $tmp['##ocsmachine.operatingsystem##'] = Dropdown::getDropdownName("glpi_operatingsystems",$ocsmachine['operatingsystems_id']);
         $tmp['##ocsmachine.state##'] = Dropdown::getDropdownName("glpi_states",$ocsmachine['states_id']);
         $tmp['##ocsmachine.location##'] = Dropdown::getDropdownName("glpi_locations",$ocsmachine['locations_id']);
         
         $tmp['##ocsmachine.urluser##'] = urldecode($CFG_GLPI["url_base"]."/index.php?redirect=user_".
                                    $ocsmachine['users_id']);
         
         $tmp['##ocsmachine.urlgroup##'] = urldecode($CFG_GLPI["url_base"]."/index.php?redirect=group_".
                                    $ocsmachine['groups_id']);

         $tmp['##ocsmachine.user##'] = getUserName($ocsmachine['users_id']);
         $tmp['##ocsmachine.group##'] = Dropdown::getDropdownName("glpi_groups",$ocsmachine['groups_id']);
         $tmp['##ocsmachine.contact##'] = $ocsmachine['contact'];
         
         $tmp['##ocsmachine.lastocsupdate##'] = Html::convDateTime($ocsmachine['last_ocs_update']);
         $tmp['##ocsmachine.lastupdate##'] = Html::convDateTime($ocsmachine['last_update']);
         $tmp['##ocsmachine.ocsserver##'] = Dropdown::getDropdownName("glpi_ocsservers",$ocsmachine['ocsservers_id']);
         
         $this->datas['ocsmachines'][] = $tmp;
      }
   }
   
   function getTags() {
      global $LANG;

      $tags = array('ocsmachine.name'            => $LANG['common'][16],
                     'ocsmachine.urlname'            => 'URL '.$LANG['common'][16],
                   'ocsmachine.operatingsystem'   => $LANG['computers'][9],
                   'ocsmachine.state'    => $LANG['state'][0],
                   'ocsmachine.location' => $LANG['common'][15],
                   'ocsmachine.user'    => $LANG['common'][34],
                   'ocsmachine.urluser' => 'URL '.$LANG['common'][34],
                   'ocsmachine.group' => $LANG['common'][35],
                   'ocsmachine.urlgroup' => 'URL '.$LANG['common'][35],
                   'ocsmachine.contact' => $LANG['common'][18],
                   'ocsmachine.lastocsupdate' => $LANG['ocsng'][14],
                   'ocsmachine.lastupdate' => $LANG['ocsng'][13],
                   'ocsmachine.ocsserver' => $LANG['ocsng'][29]);
      foreach ($tags as $tag => $label) {
         $this->addTagToList(array('tag'=>$tag,'label'=>$label,
                                   'value'=>true));
      }
      
      $this->addTagToList(array('tag'=>'additionalalerts',
                                'label'=>$LANG['plugin_additionalalerts']['mailing'][5],
                                'value'=>false,
                                'foreach'=>true,
                                'events'=>array('ocs','newocs')));

      asort($this->tag_descriptions);
   }
}

?>