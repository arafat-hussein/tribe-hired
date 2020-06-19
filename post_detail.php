<?php
/**
 * getting the jason data from the url
 */
$postsJson = file_get_contents("https://jsonplaceholder.typicode.com/posts");
$commentsJson = file_get_contents("https://jsonplaceholder.typicode.com/comments");

/**
 * setting default values for limit and offset 
 */

$offset = (isset($_GET['offset']) ? $_GET['offset']: 0 );
$limit = (isset($_GET['limit']) ? $_GET['limit']: 10 );

$postsObject = json_decode($postsJson, true);
$commentsObject = json_decode($commentsJson, true);

$postsArray = array();
$commentsArray = array();
    foreach ($commentsObject as $key => $item) {
        $commentsId = $item['id'];
    $commentsArray[$item['postId']][] = $item;
    }

    foreach ($postsObject as $key => $item) {
        $postsArray[$item['id']] = $item;
    }

    array_multisort(array_map('count', $commentsArray), SORT_DESC, $commentsArray);

$finalArray = array();
foreach( $commentsArray as $key => $value){
    $postId = $value[0]['postId']; 
    $arr = array();
    $arr['post_id'] = $postId;
    $arr['post_title'] = $postsArray[$postId]['title'];
    $arr['post_body'] = $postsArray[$postId]['body'];
    $arr['total_number_of_comments'] = count($value);
    $finalArray[] = $arr;
}

$array_segment = array_slice($finalArray, $offset, $limit);
header('Content-type: application/json');
echo json_encode($array_segment);


?>