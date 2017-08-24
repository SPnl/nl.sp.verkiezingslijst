<?php

/**
 * Collection of upgrade steps
 */
class CRM_Verkiezingslijst_Upgrader extends CRM_Verkiezingslijst_Upgrader_Base {

  public function install() {
    $this->executeSqlFile('sql/verkiezingslijst.sql');
    $this->addOptionGroup('verkiezingen', 'Verkiezingen');
  }

  public function upgrade_1001() {
    $sql = "ALTER TABLE civicrm_verkiezingslijst 
            ADD COLUMN `afdracht_verklaring_ondertekend` int(11) DEFAULT '0',
            ADD COLUMN `gekozen` int(1) DEFAULT '0';";
    CRM_Core_DAO::executeQuery($sql);
    return true;
  }
  
  protected function addOptionGroup($name, $title) {
    try {
      $existing = civicrm_api3('OptionGroup', 'getsingle', array('name' => $name));
      return;
    } catch (Exception $ex) {
      //do nothing
    }
    
    $params['name'] = $name;
    $params['title'] = $title;
    $params['is_reserved'] = 1;
    $params['is_active'] = 1;
    civicrm_api3('OptionGroup', 'create', $params);
  }


}
