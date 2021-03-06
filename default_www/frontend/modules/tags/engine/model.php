<?php

/**
 * In this file we store all generic functions that we will be using in the tags module
 *
 * @package		frontend
 * @subpackage	tags
 *
 * @author		Davy Hellemans <davy@netlash.com>
 * @author		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		2.0
 */
class FrontendTagsModel
{
	/**
	 * Calls a method that has to be implemented though the tags interface
	 *
	 * @return	mixed
	 * @param	string $module					The module wherin to search.
	 * @param	string $class					The class that should contain the method.
	 * @param	string $method					The method to call.
	 * @param	mixed[optional] $parameter		The parameters to pass.
	 */
	public static function callFromInterface($module, $class, $method, $parameter = null)
	{
		// check to see if the interface is implemented
		if(in_array('FrontendTagsInterface', class_implements($class)))
		{
			// return result
			return call_user_func(array($class, $method), $parameter);
		}

		// interface is not implemented
		else
		{
			// when debug is on throw an exception
			if(SPOON_DEBUG) throw new FrontendException('To use the tags module you need to implement the FrontendTagsInterface in the model of your module (' . $module . ').');

			// when debug is off show a descent message
			else exit(SPOON_DEBUG_MESSAGE);
		}
	}


	/**
	 * Get the tag for a given URL
	 *
	 * @return	array
	 * @param	string $URL		The URL to get the tag for.
	 */
	public static function get($URL)
	{
		// exists
		return (array) FrontendModel::getDB()->getRecord('SELECT id, language, tag AS name, number, url
															FROM tags
															WHERE url = ?',
															array((string) $URL));
	}


	/**
	 * Fetch the list of all tags, ordered by their occurence
	 *
	 * @return	array
	 */
	public static function getAll()
	{
		return (array) FrontendModel::getDB()->getRecords('SELECT t.tag AS name, t.url, t.number
															FROM tags AS t
															WHERE t.language = ? AND t.number > 0
															ORDER BY number DESC, t.tag',
															array(FRONTEND_LANGUAGE));
	}


	/**
	 * Get tags for an item
	 *
	 * @return	array
	 * @param	string $module		The module wherin the otherId occurs.
	 * @param	int $otherId		The id of the item.
	 */
	public static function getForItem($module, $otherId)
	{
		// redefine
		$module = (string) $module;
		$otherId = (int) $otherId;

		// init var
		$return = array();

		// get tags
		$linkedTags = (array) FrontendModel::getDB()->getRecords('SELECT t.tag AS name, t.url
																	FROM modules_tags AS mt
																	INNER JOIN tags AS t ON mt.tag_id = t.id
																	WHERE mt.module = ? AND mt.other_id = ?',
																	array($module, $otherId));

		// return
		if(empty($linkedTags)) return $return;

		// create link
		$tagLink = FrontendNavigation::getURLForBlock('tags', 'detail');

		// loop tags
		foreach($linkedTags as $row)
		{
			// add full URL
			$row['full_url'] = $tagLink . '/' . $row['url'];

			// add
			$return[] = $row;
		}

		// return
		return $return;
	}


	/**
	 * Get tags for multiple items.
	 *
	 * @return	array
	 * @param	string $module		The module wherefor you want to retrieve the tags.
	 * @param 	array $otherIds		The ids for the items.
	 */
	public static function getForMultipleItems($module, array $otherIds)
	{
		// redefine
		$module = (string) $module;

		// get db
		$db = FrontendModel::getDB();

		// init var
		$return = array();

		// get tags
		$linkedTags = (array) $db->getRecords('SELECT mt.other_id, t.tag AS name, t.url
												FROM modules_tags AS mt
												INNER JOIN tags AS t ON mt.tag_id = t.id
												WHERE mt.module = ? AND mt.other_id IN (' . implode(', ', $otherIds) . ')',
												array($module));

		// return
		if(empty($linkedTags)) return $return;

		// create link
		$tagLink = FrontendNavigation::getURLForBlock('tags', 'detail');

		// loop tags
		foreach($linkedTags as $row)
		{
			// add full URL
			$row['full_url'] = $tagLink . '/' . $row['url'];

			// add
			$return[$row['other_id']][] = $row;
		}

		// return
		return $return;
	}


	/**
	 * Get the tag-id for a given URL
	 *
	 * @return	int
	 * @param	string $URL		The URL to get the id for.
	 */
	public static function getIdByURL($URL)
	{
		return (int) FrontendModel::getDB()->getVar('SELECT id
														FROM tags
														WHERE url = ?',
														array((string) $URL));
	}


	/**
	 * Get the modules that used a tag.
	 *
	 * @return	array		An array with all the modules.
	 * @param	int $id	The	id of the tag.
	 */
	public static function getModulesForTag($id)
	{
		return (array) FrontendModel::getDB()->getColumn('SELECT module
															FROM modules_tags
															WHERE tag_id = ?
															GROUP BY module
															ORDER BY module ASC',
															array((int) $id));
	}


	/**
	 * Fetch a specific tag name
	 *
	 * @return	string
	 * @param	int $id		The id of the tag to grab the name for.
	 */
	public static function getName($id)
	{
		return FrontendModel::getDB()->getVar('SELECT tag
												FROM tags
												WHERE id = ?',
												array((int) $id));
	}


	/**
	 * Get all related items
	 *
	 * @return	array					An array with all the related item-ids.
	 * @param	int $id					The id of the item in the source-module.
	 * @param	int $module				The source module.
	 * @param	int $otherModule		The module wherein the related items should appear.
	 * @param	int[optional] $limit	The maximum of related items to grab.
	 */
	public static function getRelatedItemsByTags($id, $module, $otherModule, $limit = 5)
	{
		return (array) FrontendModel::getDB()->getColumn('SELECT t2.other_id
														FROM modules_tags AS t
														INNER JOIN modules_tags AS t2 ON t.tag_id = t2.tag_id
														WHERE t.other_id = ? AND t.module = ? AND t2.module = ? AND t2.other_id != t.other_id
														GROUP BY t2.other_id
														ORDER BY COUNT(t2.tag_id) DESC
														LIMIT ?',
														array((int) $id, (string) $module, (string) $otherModule, (int) $limit));
	}
}

?>