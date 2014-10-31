<?php

class CRM_Verkiezingslijst_Config_Settings {
  
  protected static $_singleton;
  
  protected function __construct() {
    
  }
  
  /**
   * 
   * @return CRM_Verkiezingslijst_Config_Settings
   */
  public static function singleton() {
    if (!self::$_singleton) {
      self::$_singleton = new CRM_Verkiezingslijst_Config_Settings();
    }
    return self::$_singleton;
  }
  
  public function getPartijContactTypes() {
    return array(
      'SP_Afdeling',
      'SP_Provincie',
      'SP_Fractie',
      'SP_Landelijk'
    );
  }
  
}
