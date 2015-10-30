<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * Category Controller
 */
class BJImageSliderControllerPhoto extends JControllerForm
{
	public function getModel($name = 'Photo', $prefix = 'BJImageSliderModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function save($key = null, $urlVar = null){
		$path = $this->handleUpload(false);

		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app		= JFactory::getApplication();
		$lang		= JFactory::getLanguage();
		$model		= $this->getModel();
		$table		= $model->getTable();
		$data		= JRequest::getVar('jform', array(), 'post', 'array');
		$context	= "$this->option.edit.$this->context";
		$task		= $this->getTask();
		
		// Determine the name of the primary key for the data.
		if (empty($key)) {
			$key = $table->getKeyName();
		}

		// The urlVar may be different from the primary key to avoid data collisions.
		if (empty($urlVar)) {
			$urlVar = $key;
		}

		$recordId	= JRequest::getInt($urlVar);

		$session	= JFactory::getSession();
		$registry	= $session->get('registry');

		// Populate the row id from the session.
		$data[$key] = $recordId;

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form) {
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		// Test if the data is valid.
		$validData = $model->validate($form, $data);
		
		$cid = JRequest::getVar('cid');
		$validData = array_merge($validData,array("cid"=>$cid));
		
		if($path){$validData = array_merge($validData,array("path"=>$path));}
		
		// Check for validation errors.
		if ($validData === false || !$cid) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState($context.'.data', $data);
			
			if(!$cid){
				$this->setMessage(JTEXT::_('COM_BJIMAGESLIDER_PHOTO_NO_CATEGORY_CHOOSEN'),'error');
			}
			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $key), false));

			return false;
		}
		$validData["description"] = $data["description"];
		// Attempt to save the data.
		if (!$model->save($validData)) {
			// Save the data in the session.
			$app->setUserState($context.'.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $key), false));

			return false;
		}
		
		$this->setMessage(JText::_(($lang->hasKey($this->text_prefix.($recordId==0 && $app->isSite() ? '_SUBMIT' : '').'_SAVE_SUCCESS') ? $this->text_prefix : 'JLIB_APPLICATION') . ($recordId==0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($this->context.'.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context.'.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $key), false));
				break;
			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context.'.data', null);

				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.$this->getRedirectToListAppend(), false));
				break;
		}

		return true;
	}
	
	function handleUpload($redirect = true){
		$database = &JFactory::getDbo();
		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
		
		include (JPATH_COMPONENT . DS .'configuration.php');    
		require_once (JPATH_COMPONENT . DS .'classes' . DS . 'upload.php');
		
		$handle = new Upload($_FILES['photo']);
		$handle_thumb = new Upload($_FILES['thumb']);
		
		$cid = JRequest::getVar('cid', 0);
		$state = JRequest::getVar('state', 0);
		$description = JRequest::getVar('description', '', _MOS_ALLOWHTML);
		$imagename = JRequest::getVar('name', '');
		$cssclass = JRequest::getVar('cssclass', '');
		$link = JRequest::getVar('link', '');
		$time = time();
		$known_images = array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png', 'image/gif');
		if(!empty($_FILES['photo']['name'])){
		  if($_FILES['photo']['error'] > 0){ //error during upload
			switch ($_FILES['photo']['error']){
			  case UPLOAD_ERR_INI_SIZE:
				$err_msg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
				break;
			  case UPLOAD_ERR_FORM_SIZE:
				$err_msg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
				break;
			  case UPLOAD_ERR_PARTIAL:
				$err_msg = 'The uploaded file was only partially uploaded.';
				break;
			  case UPLOAD_ERR_NO_FILE:
				$err_msg = 'No file was uploaded.';
				break;
			  case UPLOAD_ERR_NO_TMP_DIR:
				$err_msg = 'Missing a temporary folder.';
				break;
			  case UPLOAD_ERR_CANT_WRITE:
				$err_msg = 'Failed to write file to disk.';
				break;
			  case UPLOAD_ERR_EXTENSION:
				$err_msg = 'File upload stopped by extension.';
				break;
			  default:
				$err_msg = 'Unknown error';
				break;
			}
			
			echo "<script> alert('Upload error for file ". $_FILES['photo']['name']. ".\\nError message: " . $err_msg . "'); document.location.href='index.php?option=" . $option . "&task=photo.edit&id=".JRequest::getVar('id')."'; </script>\n";
			exit();
		  }
		  
		  if(!in_array($_FILES['photo']['type'], $known_images)){
			echo "<script> alert('Photo type: ". $_FILES['photo']['type']. " is an unknown photo type'); document.location.href='index.php?option=" . $option . "&task=photo.edit&id=".JRequest::getVar('id')."'; </script>\n";
				exit();
		  }
		  $image_name = preg_replace('#\W^\.#is', '', $_FILES["photo"]["name"]); //fix up name to get rid of spaces etc
		  $image_name = strtolower($image_name); //only lowercase
		  $image_name = $time . "_" . $image_name;
		  jimport('joomla.filesystem.file');
		  $image_name = str_replace(" ","_",str_replace("-","_",JFile::makeSafe($image_name)));
		  
		  if($this->prepareUploadFolder($cid))
		  {

			  // Do Upload
			  if ($handle->uploaded){
				  $handle->file_safe_name = true;
				  // Copy Original Image
				  // =========================
				  $handle->file_new_name_body = substr($image_name, 0, -3) . '_org';
				  $handle->Process(JPATH_SITE . DS . $bj_ss_absolute_path . DS . $cid);
					
				  // Create Image
				  // =========================
				  
					$handle->image_resize            = true;
					//$handle->image_ratio_y           = true;
					$size = getimagesize($_FILES['photo']['tmp_name']);
					if($bj_ss_image_width < $size[0])
						$handle->image_x                 = $bj_ss_image_width;
					else
						$handle->image_x                 = $size[0];
					//$handle->image_ratio_x           = true;
					if($bj_ss_image_height < $size[1])
						$handle->image_y                 = $bj_ss_image_height;
					else
						$handle->image_y                 = $size[1];
					$handle->file_new_name_body = substr($image_name, 0, -3);
					$handle->Process(JPATH_SITE . DS . $bj_ss_absolute_path . DS . $cid);
				  
				  // Create Thumb
				  //===========================
				  if(!$handle_thumb->uploaded) {
					// create default thumbnail
					
					$handle->image_resize            = true;
					//$handle->image_ratio_y           = true;
					$handle->image_x                 = $bj_ss_thumb_width;
					//$handle->image_ratio_x           = true;
					$handle->image_y                 = $bj_ss_thumb_height;
					$handle->file_new_name_body = substr($image_name, 0, -3)."t";
					$handle->Process(JPATH_SITE . DS . $bj_ss_absolute_path . DS . $cid);
				  } else {
					// create thumbnail by image uploaded
					  
					$handle_thumb->image_resize            = true;
					//$handle->image_ratio_y           = true;
					$handle_thumb->image_x                 = $bj_ss_thumb_width;
					//$handle->image_ratio_x           = true;
					$handle_thumb->image_y                 = $bj_ss_thumb_height;
					$handle_thumb->file_new_name_body = substr($image_name, 0, -3)."t";
					$handle_thumb->Process(JPATH_SITE . DS . $bj_ss_absolute_path . DS . $cid);
				  }
				  $handle->clean();
				  $handle_thumb->clean();
				  
				  if(!$handle->processed){
					echo "<script> alert(\"".$handle->error."\");document.location.href='index.php?option=" . $option . "&view=photo&task=photo.edit&id=".JRequest::getVar('id')."'; </script>\n";
					exit();	
				  } 
				  else 
				  {
					  return $bj_ss_path . '/' . $cid . '/' . $image_name;
				  }
			  }
			  else 
			  {
					echo "<script> alert('Upload failed!');document.location.href='index.php?option=" . $option . "&view=photo&task=photo.edit&id=".JRequest::getVar('id')."'; </script>\n";
					exit();	
			  }
		  }
		}
  }
  
  /**
   * Check and create nessecery folder for update files
   *
   * @param string $folder FolderName
   */
  function prepareUploadFolder($folder) {
	include (JPATH_COMPONENT . DS . 'configuration.php');
	// Set FTP credentials, if given
	jimport('joomla.client.helper');
	JClientHelper::setCredentialsFromRequest('ftp');
		
  	jimport('joomla.filesystem.*');
	if(!JFolder::create(JPATH_SITE . $bj_ss_absolute_path)) return false;
	
	jimport('joomla.filesystem.*');
	if(!JFolder::create(JPATH_SITE . $bj_ss_absolute_path . DS . $folder)) return false;
	
	return true;
  }
}
