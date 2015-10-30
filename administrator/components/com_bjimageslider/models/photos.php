<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * Photos Model
 */
class BJImageSliderModelPhotos extends JModelList
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
			$query->select('a.id,a.name,cssclass,path,is_default,a.ordering,a.published,link,b.name as category');
			$query->from('#__bj_ss_items as a');
			$query->join('LEFT', '#__bj_ss_categories AS b ON a.cid = b.id');
			$query->order('a.ordering ASC');
			
			// Filter by category.
			$categoryId = $this->getState('filter.cid');
			if (is_numeric($categoryId)) {
				$query->where('a.cid = '.(int) $categoryId);
			}
		
			return $query;
        }
		
		/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		$id	.= ':'.$this->getState('filter.cid');
		return parent::getStoreId($id);
	}
		
		/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.cid', 'filter_cid', '');
		
		$this->setState('filter.cid', $categoryId);

		// List state information.
		parent::populateState('name', 'asc');
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
		public function getTable($type = 'Photo', $prefix = 'BJImageSliderTable', $config = array())
		{
			return JTable::getInstance($type, $prefix, $config);
		}
		
		public function getModel($name = 'Photo', $prefix = 'BJImageSliderModel') 
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
