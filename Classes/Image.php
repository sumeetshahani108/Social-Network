<?php
class Image {
    public static function uploadImage($formname, $query, $params) {
        $image = base64_encode(file_get_contents($_FILES[$formname]['tmp_name']));
        $options = array('http'=>array(
            'method'=>"POST",
            'header'=>"Authorization: Bearer 12ce7e70ba10bcf9a74f0b1fdb41a9c75f76440a\n".
                "Content-Type: application/x-www-form-urlencoded",
            'content'=>$image
        ));
        $context = stream_context_create($options);
        $imgurURL = "https://api.imgur.com/3/image";
        if ($_FILES[$formname]['size'] > 10240000) {
            die('Image too big, must be 10MB or less!');
        }
        $response = file_get_contents($imgurURL, false, $context);
        $response = json_decode($response);
        // this -> is used when you have json data
        $preparams = array($formname=>$response->data->link);
        // the reason $preparams is written first is because in the query the first parameter is of img ;
        $params = $preparams + $params;
        DB::query($query, $params);
    }
}
?>