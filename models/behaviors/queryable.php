<?php
/**
 * Queryable Behavior
 * 
 * @package searchable
 * @subpackage searchable.models.behaviors
 * @license MIT-License (http://www.opensource.org/licenses/mit-license.php)
 * @author Thomas Ploch <t.ploch@reizwerk.com>
 * @since 2011-05-31 21:37
 */
class QueryableBehavior extends ModelBehavior {

/**
 * Saves the Model's recursive to restore it in clean up
 * 
 * @var array
 * @access protected
 */
	protected $_recursive = array();

/**
 * Saves the Model's binding state
 * 
 * @var array
 * @access protected
 */
	protected $_wasBound = array();
	
/**
 * Behavior setup - Constructor
 * 
 * @param Model &$Model A reference to the model object the Behavior is attached to
 * @param Array $settings The passed in settings for this Behavior
 * @access public
 * @return void
 */
	public function setup(&$Model, $settings) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array(
				'searchModel' => 'Searchable.SearchIndex',
				'searchField' => 'data',
				'foreignKey' => 'foreign_key',
				'modelIdentifier' => 'model',
				'scoreField' => 'score',
				'minScore' => 2,
				'boolean' => true,
				'includeIndex' => false
			);
		}
		$this->settings[$Model->alias] = array_merge(
			$this->settings[$Model->alias],
			(array) $settings
		);
	}

/**
 * beforeFind Behavior callback.
 * 
 * Binds the set search model and sets corresponding conditions.
 * If the binding fails for some reason, it will __NOT__ cancel the find,
 * but just go on with the other $query parameters.
 * 
 * @param Model &$Model A reference to the model object the Behavior is attached to
 * @param Array $query The query for the current find transaction
 * @return Array Return $query
 * @access public
 */
	public function beforeFind(&$Model, $query) {
		if (!empty($query['term'])) {
			if ($Model->recursive < 0) {
				$this->_recursive[$Model->alias] = $Model->recursive;
				$Model->recursive = 0;
			}

			list($plugin, $searchmodel) = pluginSplit($this->getSearchSetting($Model, 'searchModel'));
			if (!isset($Model->hasOne[$searchmodel])) {
				if (!$this->_bindSearchModel($Model)) {
					unset($query['term']);
					return $query;
				}
				$this->_wasBound[$Model->alias] = $searchmodel;
			}

			if ($Model->Behaviors->attached('Containable')) {
				$this->_processContainableOptions($Model, $query, $searchmodel);
			}

			$this->_processQuery($Model, $query, $searchmodel);
		}
		return $query;
	}

/**
 * Behavior afterFind Callback
 * Cleaning up :)
 * 
 * @param Model $Model
 * @param Array $results The results returned by find
 * @param bool $primary Indicating if the Model was queried directly
 * @return Array processed $results
 * @access public
 */
	public function afterFind(&$Model, $results, $primary) {
		if (!empty($this->_recursive[$Model->alias])) {
			$Model->recursive = $this->_recursive[$Model->alias];
			$this->_recursive[$Model->alias] = null;
		}
		if (!$this->getSearchSetting($Model, 'includeIndex')) {
			if (!empty($this->_wasBound[$Model->alias])) {
				$Model->unbindModel(array('hasOne' => array($this->_wasBound[$Model->alias])), false);
				$this->_wasBound[$Model->alias] = null;
			}
		}
		return $results;
	}

/**
 * Processes options for Containable by parsing the current Containable state and adding
 * required fields
 * 
 * @param Model &$Model The current Model
 * @param Array &$query The query to be processed
 * @param string $searchmodel The name of the searchmodel that should be used
 * @return void
 * @access protected
 */
	protected function _processContainableOptions(&$Model, &$query, $searchmodel) {
		$useRuntime = false;
		$contain = array();

		if (!isset($query['contain'])) {
			if (!empty($Model->Behaviors->Containable->runtime[$Model->alias])) {
				$useRuntime = true;
				$contain = $Model->Behaviors->Containable->runtime[$Model->alias];
			}
		} else {
			$contain['contain'] = $query['contain'];
		}

		if (!empty($contain)) {
			if (!empty($contain['contain'][$searchmodel])) {
				$pk = $Model->{$searchmodel}->primaryKey;
				$requiredFields = array(
					"$searchmodel.$pk", "$searchmodel.{$this->getSearchSetting($Model, 'foreignKey')}",
					"$searchmodel.{$this->getSearchSetting($Model, 'searchField')}",
					"$searchmodel.{$this->getSearchSetting($Model, 'modelIdentifier')}"
				);
				if (!$this->getSearchSetting($Model, 'boolean')) {
					$match = "MATCH(`{$searchmodel}`.`{$this->getSearchSetting($Model, 'searchField')}`) AS score";
					$requiredFields[] = $match;
				}
				if (!empty($contain['contain'][$searchmodel]['fields'])) {
					$contain['contain'][$searchmodel]['fields'] = array_merge(
						$contain['contain'][$searchmodel]['fields'],
						$requiredFields
					);
				} else {
					$contain['contain'][$searchmodel]['fields'] = $requiredFields;
				}
			} elseif (!in_array($searchmodel, $contain['contain'])) {
				$contain['contain'][] = $searchmodel;
			}
		}

		if ($useRuntime) {
			$Model->Behaviors->Containable->runtime[$Model->alias] = $contain;
		} else {
			if (!empty($contain['contain'])) {
				$query['contain'] = $contain['contain'];
			}
		}
	}

/**
 * Binds the Search model to the $Model
 * 
 * @param Model &$Model A reference to the model object the Behavior is attached to
 * @return bool True on success, false otherwise
 * @access protected
 */
	protected function _bindSearchModel(&$Model) {
		list($plugin, $searchmodel) = pluginSplit($this->getSearchSetting($Model, 'searchModel'));

		$options = array(
			'hasOne' => array(
				$searchmodel => array()
			)
		);

		if ($plugin) {
			$options['hasOne'][$searchmodel]['className'] = "$plugin.$searchmodel";
		}

		$options['hasOne'][$searchmodel]['foreignKey'] = $this->getSearchSetting($Model, 'foreignKey');
		$options['hasOne'][$searchmodel]['conditions'] = "$searchmodel.{$this->getSearchSetting($Model, 'modelIdentifier')} = ";
		$options['hasOne'][$searchmodel]['conditions'] .= "'{$Model->alias}'";

		return $Model->bindModel($options);
	}

/**
 * Processes the query and adds required stuff to the Model
 * 
 * @param Model &$Model a reference to the current model
 * @param Array &$query A reference to the currently processed query
 * @param string $searchmodel The currently attached SearchIndex Model
 * @return void
 * @access protected
 */
	protected function _processQuery(&$Model, &$query, $searchmodel) {
		$scoreField = $this->getSearchSetting($Model, 'scoreField');
		$includeIndex = (bool) $this->getSearchSetting($Model, 'includeIndex');
		$term = implode(' ', array_map(array($this, '_replace'), preg_split('/[\s_]/', $query['term']))) . ' *';
		unset($query['term']);

		$match = "MATCH(`{$searchmodel}`.`{$this->getSearchSetting($Model, 'searchField')}`) ";
		$match .= "AGAINST('{$term}'";

		if (empty($query['fields']) && !$includeIndex) {
			$query['fields'] = $this->_makeFields($Model, $searchmodel);
		}
		
		if (!empty($query['fields']) && $includeIndex) {
			$query['fields'] = array_merge($query['fields'], $this->_makeFields($Model, $searchmodel, true));
		}

		if ($this->getSearchSetting($Model, 'boolean')) {
			$match .= ' IN BOOLEAN MODE)';
			$query['conditions'][] = array("$match");
		} else {
			$match .= ')';
			$Model->virtualFields[$scoreField] = $match;
			if (!empty($query['fields'])) {
				$query['fields'][] = $scoreField;
			}
			$query['conditions'][] = array("$match >=" => $this->getSearchSetting($Model, 'minScore'));
			$query['sort'][] = "score DESC";
		}

		$query['group'][] = "{$Model->alias}.{$Model->primaryKey}";
	}

/**
 * Shorthander for Behavior settings
 * 
 * @param Model &$Model A reference to the model object the Behavior is attached to
 * @param NULL|string $key if null, gets all settings, otherwise setting for key $key
 * @return mixed The settings for current Model or NULL if key was not found
 * @access public
 */
	public function getSearchSetting(&$Model, $key = null) {
		if (null == $key) {
			return $this->settings[$Model->alias];
		}

		if (!empty($this->settings[$Model->alias][$key])) {
			return $this->settings[$Model->alias][$key];
		}
		return null;
	}

/**
 * Sets a Behavior setting for Model $Model and key $key to $value
 * 
 * @param Model &$Model
 * @param string $key
 * @param mixed $value
 * @return void
 * @access public
 */
	public function setSearchSetting(&$Model, $key, $value) {
		$this->settings[$Model->alias][$key] = $value;
	}

/**
 * Helper method to parse search term
 * 
 * @param string $v The term to parse
 * @access protected
 * @return string
 */
	protected function _replace($v) {
		return str_replace(array(' +-', ' +~', ' ++', ' +'), array('-', '~', '+', '+'), " +{$v}");
	}

/**
 * Creates a field list from the Model schema
 * 
 * @param Model &$Model
 * @param string $searchmodel
 * @return Array fieldlist
 * @access protected
 */
	protected function _makeFields(&$Model, $searchmodel, $useSearch = false) {
		$return = array();
		if (!$useSearch) {
			foreach (array_keys($Model->schema()) as $field) {
				$return[] = "{$Model->alias}.$field";
			}
		} else {
			foreach (array_keys($Model->{$searchmodel}->schema()) as $field) {
				$return[] = "$searchmodel.$field";
			}
		}

		return $return;
	}
}
