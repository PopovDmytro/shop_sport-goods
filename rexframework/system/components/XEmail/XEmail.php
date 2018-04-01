<?php
/**
 * XEmail is a factory of PHPMailer
 *
 * @access public
 * @name XEmail
 * @package XFramework
 * @version 0.1
 *
 */

class XEmail
{
	/**
	 * Property for PHPMailer
	 *
	 * $_property = array (
	 * 	'Mailer' => 'smtp','mail','sendmail','qmail',
	 *  //path to sendmail
	 *  'Sendmail' => '/usr/sbin/sendmail',
	 *  //for SMTP
	 *  'SMTP_Host' => '',
	 *  'SMTP_Port' => '',
	 *  'SMTP_Username' => '',
	 *  'SMTP_Password' => '',
	 *  //Sender Info
	 *  'Sender' => 'some@domain.com',
	 *  'FromName' => 'some_name',
	 *
	 *  //another options
	 *  'IsHTML' => bool,
	 *  //default en
	 *  'Language' => br,ca,cz,de,dk,en,es,fi,fo,fr,hu,it,ja,nl,no,pl,ro,ru,se,tr,
	 * );
	 *
	 * @var array
	 */
	var $_property;

   /**
    * Constructor
    * @access public
    * @param mixed $aProperty The component property
    * @global object XEmail
    */
    function XEmail($aProperty = null)
    {
    	/* @var $XEmail XEmail */
        global $XEmail;
		if (!$XEmail) $XEmail = $this;

    	$XEmail->init($aProperty);
    }

    /**
	 * Destructor of XEmail
	 *
	 * @access  public
	 */
	 function _XEmail()
	 {

	 }

	 /**
	  * init
	  *
	  * initialize configuration
	  *
	  * @access  public
	  * @param   array     $aProperty  property for PHPMailer
	  */
	 function init($aProperty)
	 {
	 	/* @var $XEmail XEmail */
        global $XEmail;

        $XEmail->_property = $aProperty;
	 }

	 /**
	  * factory
	  *
	  * return instance of PHPMailer
	  *
	  * @access  public
	  * @return  PHPMailer  $PHPMailer
	  */
	 static function factory()
	 {
	 	/* @var $XEmail XEmail */
        global $XEmail;

		require_once dirname(__FILE__).'/phpmailer/class.phpmailer.php';

        $PHPMailer = new PHPMailer();

        $XEmail->_setProperty($PHPMailer);

		// Added for zpeople.com.ua
        $PHPMailer->CharSet = 'utf-8';

        return $PHPMailer;
	 }

	 /**
	  * _setProperty
	  *
	  * set property for PHPMailer
	  *
	  * @access  private
	  * @param   PHPMailer  $aPHPMailer
	  * @return  PHPMailer  $aPHPMailer
	  */
	 function _setProperty(&$aPHPMailer)
	 {
	 	/* @var $XEmail XEmail */
        global $XEmail;

        if (isset($XEmail->_property['mailer'])) {
        	// switch statement for $XEmail->_property['Mailer']
        	switch ($XEmail->_property['mailer']) {
        		case "smtp":
        			$aPHPMailer->IsSMTP();
        			if (isset($XEmail->_property['smtp'])) {

        				if (!empty($XEmail->_property['smtp']['host'])) {
        					$aPHPMailer->Host = $XEmail->_property['smtp']['host'];
        				}

        				if (!empty($XEmail->_property['smtp']['port'])) {
        					$aPHPMailer->Port = $XEmail->_property['smtp']['port'];
        				}

        				if (!empty($XEmail->_property['smtp']['username'])) {
        					$aPHPMailer->Username = $XEmail->_property['smtp']['username'];
        					$aPHPMailer->SMTPAuth = true;
        				}

        				if (!empty($XEmail->_property['smtp']['password'])) {
        					$aPHPMailer->Password = $XEmail->_property['smtp']['password'];
        				}
        			}
        			break;

        		case "mail":
        			$aPHPMailer->IsMail();
        			break;

        		case "sendmail":
        			$aPHPMailer->IsSendmail();
        			if (!empty($XEmail->_property['sendmail'])) {
    					$aPHPMailer->Sendmail = $XEmail->_property['sendmail'];
    				}
        			break;

        		case "qmail":
        			if (!empty($XEmail->_property['sendmail'])) {
    					$aPHPMailer->Sendmail = $XEmail->_property['sendmail'];
    				}
        			$aPHPMailer->IsQmail();
        			break;

        		default:
        			break;
        	}
        }

        if (!empty($XEmail->_property['sender'])) {
			$aPHPMailer->Sender	= $XEmail->_property['sender'];
			$aPHPMailer->From	= $XEmail->_property['sender'];
		}

        if (!empty($XEmail->_property['fromname'])) {
			$aPHPMailer->FromName = $XEmail->_property['fromname'];
		}

        if (!empty($XEmail->_property['ishtml']) && $XEmail->_property['ishtml'] == "true") {
			$aPHPMailer->IsHTML(true);
		}

        if (!empty($XEmail->_property['charset'])) {
			$aPHPMailer->CharSet = $XEmail->_property['charset'];
		}

        if (!empty($XEmail->_property['language'])) {
			$aPHPMailer->SetLanguage($XEmail->_property['language']);
		}

	 	return $aPHPMailer;
	 }
}
?>
