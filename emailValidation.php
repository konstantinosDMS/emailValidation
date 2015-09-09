<?php

defined('JPATH_BASE') or die;
JLoader::register('SMTP_Validate_Email',JPATH_PLUGINS.'/user/emailValidation/smtp-validate-email-master/smtp-validate-email.php');

/**
 * This is our custom registration plugin class.  It validates/verifies the email address a user 
 * entered into the email field of Joomla's registration form.
 *
 * @package     Joomla.Plugins
 * @subpackage  User.MyRegistration
 * @since       1.5.0
 */
class plgUserEmailValidation extends JPlugin
{	
	
	/**
	 * Method to handle the "onContentPrepareForm" event and alter the user registration form.  We
	 * are going to check and make sure that the form being prepared is the user registration form
	 * from the com_users component first. 
	 * 	 *
	 * @return  bool
	 * 
	 * @since   1.5.0
	 */
	public function onUserBeforeSave($previousData, $isNew, $futureData)
	{
            
		// If we aren't saving a "new" user (registration), then let the save happen without interruption.
		if (!$isNew || !JFactory::getApplication()->isSite()) {
			return true;
		}
		
		// Load the plugin language file
		$this->loadLanguage();                
                $result = false;
                
                // Save the content of email field
                $JForm = JRequest::getVar('jform');
                $JUserName = $JForm['email1'];
                           
                // Verify/Validate the User's email field and return the appropriate $result var.
		if (!empty($JUserName)) {
                        $from = 'a-happy-camper@campspot.net'; 
                        $email=$JUserName;
                        $validator = new SMTP_Validate_Email($email, $from);
                        $smtp_results = $validator->validate();
                        
                        if ($smtp_results[$email]=='1') $result=true; 
                        else {
                               JError::raiseWarning(1000, JText::_('PLG_USER_EMAIL_VALIDATION_EXIST'));
			       $result = false; 
                        }	
		}

		return $result;
	}	
}
