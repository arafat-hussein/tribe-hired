<?php
/**
 * getting the jason data from the url
 */
$commentsJson = file_get_contents("https://jsonplaceholder.typicode.com/comments");
$commentsObject = json_decode($commentsJson, true);

$commentsArray = array();

foreach ($commentsObject as $key => $item) {
    $commentsId = $item['id'];
    $commentsArray[] = $item;
}

/**
 * setting default values for limit and offset 
 */

$offset = (isset($_GET['offset']) ? $_GET['offset']: 0 );
$limit = (isset($_GET['limit']) ? $_GET['limit']: 10 ); 

$postId = (isset($_GET['postId']) ? $_GET['postId']: null );
$commentId = (isset($_GET['id']) ? $_GET['id']: null );
$name = (isset($_GET['name']) ? $_GET['name']: null );
$email = (isset($_GET['email']) ? $_GET['email']: null );
$body = (isset($_GET['body']) ? $_GET['body']: null );

$arrayReturn = array();
/** 
 * atleast one of the search parameter should be there
*/
if( ( is_null($postId) && is_null($commentId) && is_null($name) && is_null($email) && is_null($body) ) ){
    $finalArray = array();
}
else{
    $finalArray = $commentsArray;
    if ($postId) {
        $finalArray = array_filter($finalArray, function ($var) use ($postId) {
            return ($var['postId'] == $postId);
        });
    }
    if ($commentId) {
        $finalArray = array_filter($finalArray, function ($var) use ($commentId) {
            return ($var['id'] == $commentId);
        });
    }
    if ($name) {
        $finalArray = array_filter($finalArray, function ($var) use ($name) {
            return strpos(strtolower($var['name']), strtolower($name));
        });
    }
    if ($email) {
        $finalArray = array_filter($finalArray, function ($var) use ($email) {
            return strpos(strtolower($var['email']), strtolower($email));
        });
    }
    if ($body) {
        $finalArray = array_filter($finalArray, function ($var) use ($body) {
            return strpos(strtolower($var['body']), strtolower($body));
        });
    }

    $arrayReturn = array_slice($finalArray, $offset, $limit);
}
 header('Content-type: application/json');
echo json_encode($arrayReturn);
?>