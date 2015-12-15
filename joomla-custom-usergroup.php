<?php 

//since there is only one custom group, $group will always be 'site-planning'
//in the following methods.
//group name: site-planning
//group id:13
//
// do not paste the function definitions into the custom group script boxes, just the internals

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
 * @param  string $group [description]
 * @return array<string> [description]
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