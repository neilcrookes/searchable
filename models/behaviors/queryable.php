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
				'modelIdentifier' => 'model'
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

			list($plugin, $searchmodel) = pluginSplit($this->getSetting($Model, 'searchModel'));
			if (!isset($Model->hasOne[$searchmodel])) {
				if (!$this->_bindSearchModel($Model)) {
					unset($query['term']);
					return $query;
				}
			}

			if ($Model->Behaviors->attached('Containable')) {
				$this->_processContainableOptions($Model, $query, $searchmodel);
			}

			$term = implode(' ', array_map(array($this, '_replace'), preg_split('/[\s_]/', $query['term']))) . '*';
			unset($query['term']);

			$match = "MATCH(`{$searchmodel}`.`{$this->getSetting($Model, 'searchField')}`) ";
			$match .= "AGAINST('{$term}' IN BOOLEAN MODE)";

			$query['conditions'][] = array("$match >" => 0);
			$query['group'][] = "{$Model->alias}.{$Model->primaryKey}";
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
					"$searchmodel.$pk", "$searchmodel.{$this->getSetting($Model, 'foreignKey')}",
					"$searchmodel.{$this->getSetting($Model, 'searchField')}",
					"$searchmodel.{$this->getSetting($Model, 'modelIdentifier')}"
				);
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
		list($plugin, $searchmodel) = pluginSplit($this->getSetting($Model, 'searchModel'));

		$options = array(
			'hasOne' => array(
				$searchmodel => array()
			)
		);

		if ($plugin) {
			$options['hasOne'][$searchmodel]['className'] = "$plugin.$searchmodel";
		}

		$options['hasOne'][$searchmodel]['foreignKey'] = $this->getSetting($Model, 'foreignKey');
		$options['hasOne'][$searchmodel]['conditions'] = "$searchmodel.{$this->getSetting($Model, 'modelIdentifier')} = ";
		$options['hasOne'][$searchmodel]['conditions'] .= "'{$Model->alias}'";

		return $Model->bindModel($options);
	}

/**
 * Shorthander for Behavior settings
 * 
 * @param Model &$Model A reference to the model object the Behavior is attached to
 * @param NULL|string $key if null, gets all settings, otherwise setting for key $key
 * @return mixed The settings for current Model or NULL if key was not found
 * @access public
 */
	public function getSetting(&$Model, $key = null) {
		if (null == $key) {
			return $this->settings[$Model->alias];
		}

		if (!empty($this->settings[$Model->alias][$key])) {
			return $this->settings[$Model->alias][$key];
		}
		return null;
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
}
