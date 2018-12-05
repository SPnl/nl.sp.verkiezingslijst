<?php

require_once 'CRM/Core/Page.php';

class CRM_Verkiezingslijst_Page_Verkiezingslijst extends CRM_Core_Page_Basic {
  
  protected static $_links;
  
  protected $_action;
  
  protected $_id;
  
  protected $_cid;
  
  function run() {
    // set title and breadcrumb
    CRM_Utils_System::setTitle(ts('Verkiezingslijsten'));
    $breadCrumb = array(array('title' => ts('Verkeizingslijsten'),
        'url' => CRM_Utils_System::url('civicrm/contact/verkiezingslijst',
          'reset=1'
        ),
      ));
    CRM_Utils_System::appendBreadCrumb($breadCrumb);

    $this->_cid = CRM_Utils_Request::retrieve('cid', 'Integer', $this, FALSE, 0);
    $this->_id = CRM_Utils_Request::retrieve('id', 'Integer',$this, FALSE, 0);
    $this->_action = CRM_Utils_Request::retrieve('action', 'String',$this, FALSE, 0);

    // Example: Assign a variable for use in a template
    $this->assign('cid', $this->_cid);

    return parent::run();
  }
  
  function browse($action = NULL) {
    
    $kandidaten = CRM_Verkiezingslijst_BAO::findByPartij($this->_cid);
    $rows = array();
    foreach($kandidaten as $kandidaat) {
      list($display_name, $image, $image_url) = CRM_Contact_BAO_Contact::getDisplayAndImage($kandidaat['kandidaat_contact_id']);
      $row = array();
      $row['id'] = $kandidaat['id'];
      $row['positie'] = $kandidaat['positie'];
      $row['verkiezing'] = $kandidaat['verkiezing'];
      $row['kandidaat_display_name'] = $display_name;
      $row['kandidaat_image'] = $image;
      $row['afdracht_verklaring_ondertekend'] = $kandidaat['afdracht_verklaring_ondertekend'];
      $row['gekozen'] = $kandidaat['gekozen'];
      $row['kandidaat_url'] = CRM_Utils_System::url('civicrm/contact/view', 'reset=1&cid=' . $kandidaat['kandidaat_contact_id']);
      //$row['action'] = CRM_Core_Action::formLink(self::links(), $action,
      //  array('id' => $kandidaat['id'])
      //);
      $rows[] = $row;
    }
    
    $this->assign('rows', $rows);
    
    /*$session = CRM_Core_Session::singleton();
    $userContext = $this->userContext();
    $session->pushUserContext($userContext);*/
  }

  public function editForm() {
    return 'CRM_Verkiezingslijst_Form_Verkiezingslijst';
  }

  public function editName() {
    return 'Bewerk kandidaatspositie';
  }

  public function getBAOName() {
    return 'CRM_Verkiezingslijst_BAO';
  }

  function &links() {
    if (!(self::$_links)) {
      self::$_links = array(
        CRM_Core_Action::UPDATE => array(
          'name' => ts('Edit'),
          'url' => 'civicrm/contact/verkiezingslijst/positie',
          'qs' => 'action=update&id=%%id%%&reset=1',
          'title' => ts('Edit postion'),
        ),
        CRM_Core_Action::DELETE => array(
          'name' => ts('Delete'),
          'url' => 'civicrm/contact/verkiezingslijst/positie',
          'qs' => 'action=delete&id=%%id%%',
          'title' => ts('Delete position'),
        ),
      );
    }
    return self::$_links;
  }

  public function userContext($mode = NULL) {
    return 'civicrm/contact/view';
    
  }
  
  function userContextParams($mode = NULL) {
    return 'reset=1&selectedChild=verkeizingslijst&cid='.$this->_cid;
  }

}
