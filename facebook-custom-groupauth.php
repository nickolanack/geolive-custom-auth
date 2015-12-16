<?php 

//since there is only one custom group, $group will always be 'site-planning'
//in the following methods.
//group name: fb-member, fb-admin
//
// do not paste the function definitions into the custom group script boxes, just the internals
// 

// group trees.
// 
// 	guest        admin
// 		        /	  \
// 	    registered     fb-admin
// 	       |               |
// 	    public         fb-member
//
//

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

	//TODO: 
	//   load fb api. 
	//   get token from stored FacebookPlugin parameters
	//   
	//   -- token should have been set up with extended access to user who is an admin of the group --
	//   
	//   -- for group: fb-admin check //.../{group}/admins
	//   -- for group: fb-member check //../{group}/members
	//   
	//   
	
	$fbPlugin=Core::LoadPlugin('FacebookPage');


	include_once $fbPlugin->getPath().DS.'vendor'.DS.'autoload.php';


	$db = Core::GetDatasource();
	$prfx = $db->getPrefix();

	$uid=Core::Client()->getUserId();

	$results = $db->query(
	    'SELECT provider_uid FROM ' . $prfx . 'users_authentications WHERE user_id=' . $uid .
	         ' AND provider=\'facebook\'');

	if(empty($results))return false;

	$fbuid=$results[0]->provider_uid;

	// the following credentials will need to be set. 
	// do not publish the source with real credentials or someone could hijack 
	// the facebook group

	$app_id='*****';
	$app_secret='*****';
	$app_token='*****';
	$group_id='*****';

	$fb = new Facebook\Facebook(array(
        'app_id' =>$app_id,
        'app_secret' =>$app_secret,
        'default_graph_version' => 'v2.5',
        ));

	$accessToken= $app_token;

    $fbGroupId=$group_id;

	$response = $fb->get(
        '/'.$fbGroupId.'/members',
        $accessToken
    );

	$data=json_decode($response->getBody());//->data;

	$members=$data->data;
	
	while(key_exists('paging', $data)&&key_exists('next', $data->paging)){

		// facebook group member list might include paging. combine all pages.

		$text=file_get_contents($data->paging->next);
		$data=json_decode($text);

		$members=array_merge($members, $data->data);
	}
	

	foreach($members as $member){
		if($member->id==$fbuid){
			if($group=="fb-admin"){
				if($member->administrator){
					return true;
				}
				return false;
			}
			return true;

		}
	}

	//print_r(array($fbuid,$members));

	return false;
}

/**
 * Is called when authorizing a user for an item with the special access level $group. 
 * this method should return all the levels above $group (especially any admin levels)
 * @param  string $group always 'site-planning'
 * @return array<string> list of super groups that can see/execute on things marked with $group level
 */
function groupMembersOf($group){

/**
 * 
 */

	if($group=='fb-member'){
		return array("special", "fb-admin");
	}
	return array("special");

/**
 * 
 */

}



/**
 * Test
 */

include_once __DIR__.'/core.php';

if(isMemberOf('fb-admin')){
	echo 'user is fb-admin';
}
if(isMemberOf('fb-member')){
	echo 'user is fb-member';
}