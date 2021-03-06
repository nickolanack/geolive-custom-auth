<?php 

/**
 * This is a really simple customization, it allows items to be visible to users who are assigned to a new custom user group
 * 
 * in joomla user management make a new user group, and keep track of the group id.
 *
 * in geolive add the new user group name, and scripts. the new user group will now be available for all layers/mapitems/widgets etc
 * the name you choose isn't that important but the id must match your joomla group. 
 */


//since there is only one custom group, $group will always be 'site-planning'
//in the following methods.
//group name: site-planning
//group id:13
//
// do not paste the function definitions into the custom group script boxes, just the internals

/**
 * return true if the current user is a member of $group. here $group will only ever be 'site-planning', but if
 * multiple groups where defined it would be important to distinguish
 * @param  string  $group always 'site-planning'
 * @return boolean
 */
function isMemberOf($group){

	if (Core::Client()->isAdmin()) {
	    return true;
	}

	$db = Core::GetDatasource();
	$prfx = $db->getPrefix();
	$results = $db->query(
	    'SELECT group_id FROM ' . $prfx . 'user_usergroup_map WHERE user_id=' . Core::Client()->getUserId() .
	         ' AND group_id=13');
	return ! empty($results);

}

/**
 * is called when authorizing a user for an item with the special access level $group. 
 * this method should return all the levels above $group (especially any admin levels)
 * @param  string $group always 'site-planning'
 * @return array<string> list of super groups that can see/execute on things marked with $group level
 */
function groupMembersOf($group){

/**
 * 
 */


	return array("special");

/**
 * 
 */

}