<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * Categories Model
 */
class BJImageSliderModelCategories extends JModelList
{
        /**
         * Method to build an SQL query to load the list data.
         *
         * @return      string  An SQL query
         */
        protected function getListQuery()
        {
			// Create a new query object.         
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			// Select some fields
			$query->select('id,name,description,ordering,published');
			$query->order('ordering ASC');
			// From the hello table
			$query->from('#__bj_ss_categories');
			return $query;
        }
		
		/**
		 * Returns a Table object, always creating it.
		 *
		 * @param	type	The table type to instantiate
		 * @param	string	A prefix for the table class name. Optional.
		 * @param	array	Configuration array for model. Optional.
		 *
		 * @return	JTable	A database object
		*/
		public function getTable($type = 'Category', $prefix = 'BJImageSliderTable', $config = array())
		{
			return JTable::getInstance($type, $prefix, $config);
		}
		
		public function getModel($name = 'Category', $prefix = 'BJImageSliderModel') 
        {
                $model = JModel::getInstance($name, $prefix, array('ignore_request' => true));
                return $model;
        }
		
		public function reorder($pks, $delta = 0)
		{
			// Initialise variables.
			$user	= JFactory::getUser();
			$table	= $this->getTable();
			$pks	= (array) $pks;
			$result	= true;

			$allowed = true;

			foreach ($pks as $i => $pk) {
				$table->reset();

				if ($table->load($pk)) {
					$where = array();

					if (!$table->move($delta, $where)) {
						$this->setError($table->getError());
						unset($pks[$i]);
						$result = false;
					}
				} else {
					$this->setError($table->getError());
					unset($pks[$i]);
					$result = false;
				}
			}

			if ($allowed === false && empty($pks)) {
				$result = null;
			}

			if ($result == true) {
				// Clear the component's cache
				$cache = JFactory::getCache($this->option);
				$cache->clean();
			}

			return $result;
		}
		
		public function saveorder($pks = null, $order = null)
		{
			// Initialise variables.
			$table		= $this->getTable();
			$conditions	= array();
			$user = JFactory::getUser();

			if (empty($pks)) {
				return JError::raiseWarning(500, JText::_($this->text_prefix.'_ERROR_NO_ITEMS_SELECTED'));
			}

			// update ordering values
			foreach ($pks as $i => $pk) {
				$table->load((int) $pk);

				if ($table->ordering != $order[$i]) {
					$table->ordering = $order[$i];

					if (!$table->store()) {
						$this->setError($table->getError());
						return false;
					}
				}
			}

			// Clear the component's cache
			$cache = JFactory::getCache($this->option);
			$cache->clean();

			return true;
		}
		
		public function delete(){
			// Check for request forgeries
			JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

			// Get items to remove from the request.
			$cid	= JRequest::getVar('cid', array(), '', 'array');

			$model = $this->getModel();
			
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->delete($cid)) {
					return true;
				} else {
					return false;
				}
		}
		
		public function publish($id, $val)
		{
			// Initialise variables.
			$table		= $this->getTable();
			$table->load((int) $id);
			$table->published = $val;
			if (!$table->store()) {
				$this->setError($table->getError());
				return false;
			}
			return true;
		}
}
