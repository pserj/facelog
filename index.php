<?php
session_start();
require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );
require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php');
require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookSignedRequestFromInputHelper.php');
require_once( 'Facebook/FacebookCanvasLoginHelper.php');
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphUser.php');
require_once( 'Facebook/GraphSessionInfo.php' );
 
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;
use Facebook\FacebookSession;
use Facebook\FacebookSignedRequestFromInputHelper;
use Facebook\FacebookCanvasLoginHelper;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;
//check if user wants to logout
if(isset($_REQUEST['logout']))
{
  unset($_SESSION['fb_token']);
}


$app_id='353571498183263';
$app_secret='db6c85d0207bd82a05eabbecc34bf8e4';
$redirect_url='http://localhost/facelog/index.php';

FacebookSession::setDefaultApplication($app_id,$app_secret);
$helper = new FacebookRedirectLoginHelper($redirect_url);
$sess= $helper->getSessionFromRedirect();
//check if fb session exists
if(isset($_SESSION['fb_token'])){
   
   $sess=new FacebookSession($_SESSION['fb_token']);

}
//logout
$logout ='http://localhost/facelog/index.php&logout=true';
//if fb session exists,echo the name,profile photo and email
if(isset($sess)){
//store token in php session
$_SESSION['fb_token'] = $sess->getToken();
//create request object,execute method and capture the response
$request = new FacebookRequest($sess, 'GET', '/me');
//get graph object from response
$response = $request->execute();
$graph = $response->getGraphObject(GraphUser::classname());
//use graph object methods to get user details
$name= $graph->getName();
$id =$graph->getId();
$image = 'https://graph.facebook.com/'.$id.'/picture?width=500';
$email=$graph->getProperty('email');
echo "hi $name<br><br>";  
echo "your email is $email<br><br>";
echo "<img scr='$image' /><br><br>";     
echo "<a href='".$logout."'> <button>Logout</button></a>";
}
else{
//if no session exists echo the login
echo '<a href= "'.$helper->getLoginUrl(array('email')). ' "> Login with Facebook<a/>';
}


