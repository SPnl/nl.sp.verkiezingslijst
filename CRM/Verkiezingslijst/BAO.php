<?php

class CRM_Verkiezingslijst_BAO extends CRM_Verkiezingslijst_DAO {
  
  public static function findByPartij($partij_contact_id) {
    $option_group_id = CRM_Core_BAO_OptionGroup::getFieldValue('CRM_Core_DAO_OptionGroup', 'verkiezingen', 'id', 'name');
    
    $sql = "SELECT `v`.*, `ov`.`label` AS `verkiezing` FROM `civicrm_verkiezingslijst` `v`
            INNER JOIN `civicrm_option_value`  `ov` ON `v`.`verkiezing` = `ov`.`value` AND `ov`.`option_group_id` = %1
            WHERE `partij_contact_id`  = %2 
            ORDER BY `ov`.`weight`, `v`.`positie`";
    
    $dao = CRM_Core_DAO::executeQuery($sql, array(
      1 => array($option_group_id, 'Integer'),
      2 => array($partij_contact_id, 'Integer')
    ), TRUE, 'CRM_Verkiezingslijst_DAO');
    
    $return = array();
    while($dao->fetch()) {
      $temp = array();
      CRM_Core_DAO::storeValues($dao, $temp);
      $return[] = $temp;
    }
    return $return;  
  }
  
  public static function checkPositie($partij_contact_id, $verkiezing, $positie, $kandidaat_contact_id) {
    $sql = "SELECT * FROM `civicrm_verkiezingslijst` WHERE 
        `partij_contact_id` = %1 AND `verkiezing` = %2 AND `positie` = %3 AND `kandidaat_contact_id` != %4";
    $params = array();
    $params[1] = array($partij_contact_id, 'Integer');
    $params[2] = array($verkiezing, 'String');
    $params[3] = array($positie, 'Integer');
    $params[4] = array($kandidaat_contact_id, 'Integer');
    $dao = CRM_Core_DAO::executeQuery($sql, $params);
    if ($dao->fetch()) {
      return $dao->kandidaat_contact_id;
    }
    return false;
  }
  
  public static function checkKandidaat($partij_contact_id, $verkiezing, $kandidaat_contact_id) {
    $sql = "SELECT * FROM `civicrm_verkiezingslijst` WHERE 
        `partij_contact_id` = %1 AND `verkiezing` = %2 AND `kandidaat_contact_id` = %3";
    $params = array();
    $params[1] = array($partij_contact_id, 'Integer');
    $params[2] = array($verkiezing, 'String');
    $params[3] = array($kandidaat_contact_id, 'Integer');
    $dao = CRM_Core_DAO::executeQuery($sql, $params);
    if ($dao->fetch()) {
      return $dao->id;
    }
    return false;
  }
  
  public static function getCountPerPartij($partij_contact_id) {
    $sql = "SELECT COUNT(DISTINCT(`verkiezing`)) AS `total` FROM `civicrm_verkiezingslijst` WHERE `partij_contact_id` = %1";
    $dao = CRM_Core_DAO::executeQuery($sql, array(1 => array($partij_contact_id, 'Integer')));
    if ($dao->fetch()) {
      return $dao->total;
    }
    return 0;
  }
}
