<?php

class CRM_Verkiezingslijst_DAO extends CRM_Core_DAO {

  /**
   * static instance to hold the field values
   *
   * @var array
   * @static
   */
  static $_fields = null;
  
  public $id;
  
  public $verkiezing;
  
  public $kandidaat_contact_id;
  
  public $partij_contact_id;
  
  public $positie;

  public $afdracht_verklaring_ondertekend;

  public $gekozen;

  /**
   * empty definition for virtual function
   */
  static function getTableName() {
    return 'civicrm_verkiezingslijst';
  }

  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  static function &fields() {
    if (!(self::$_fields)) {
      self::$_fields = array(
        'id' => array(
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true,
        ),
        'verkiezing' => array(
          'name' => 'verkiezing',
          'type' => CRM_Utils_Type::T_STRING,
          'required' => true,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'pseudoconstant' => array(
            'optionGroupName' => 'verkiezingen',
          )
        ),
        'kandidaat_contact_id' => array(
          'name' => 'kandidaat_contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true,
        ),
        'partij_contact_id' => array(
          'name' => 'partij_contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true,
        ),
        'positie' => array(
          'name' => 'positie',
          'type' => CRM_Utils_Type::T_INT,
          'required' => true,
        ),
        'afdracht_verklaring_ondertekend' => array(
          'name' => 'afdracht_verklaring_ondertekend',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'required' => true,
        ),
        'gekozen' => array(
          'name' => 'gekozen',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'required' => true,
        ),
      );
    }
    return self::$_fields;
  }

  /**
   * Returns an array containing, for each field, the arary key used for that
   * field in self::$_fields.
   *
   * @access public
   * @return array
   */
  static function &fieldKeys() {
    if (!(self::$_fieldKeys)) {
      self::$_fieldKeys = array(
        'id' => 'id',
        'verkiezing' => 'verkiezing',
        'kandidaat_contact_id' => 'kandidaat_contact_id',
        'partij_contact_id' => 'partij_contact_id',
        'positie' => 'positie',
        'afdracht_verklaring_ondertekend' => 'afdracht_verklaring_ondertekend',
        'gekozen' => 'gekozen,'
      );
    }
    return self::$_fieldKeys;
  }
}
