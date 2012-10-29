<?php
try {
    $appId = $_GET['id'];
    $appSecret = $_GET['app'];
    $numberUsers = $_GET['numusers'];
    $friend = $_GET['friend'];
} catch (Exception $e) {
    echo $e->getMessage();
    die;
}
require_once("facebook/facebook.php");

$config = array();
$config['appId'] = $appId;
$config['secret'] = $appSecret;
$config['fileUpload'] = false; // optional

$facebook = new Facebook($config);


$access_token = $facebook->getAccessToken();

$users;
$characters = 'abcdefghijklmnopqrstuvwxyz';


for ($i = 0; $i < $numberUsers; $i++) {
    $name = '';
    for ($j = 0; $j < 10; $j++) {
        $name .= $characters[rand(0, strlen($characters) - 1)];
    }

    $userResponse = $facebook->api('/' . $appId . '/accounts/test-users', 'POST', array(
        'installed' => false,
        'name' => $name,
        'locale' => 'en_US',
        'method' => 'post',
        'access_token' => $access_token,
            )
    );
    $user = array(
        "id" => $userResponse["id"],
        "name" => $name,
        "email" => $userResponse["email"],
        "password" => $userResponse["password"],
        "access_token" => $userResponse["access_token"]
    );
    $users[] = $user;
}
if ($friend) {
    $i = 1;
    $f = 1;
    foreach ($users as $userFriending) {
        foreach ($users as $userBeingFriended) {
            if ($userFriending["id"] != $userBeingFriended["id"]) {
                if ($f > $i) {
                    $friendRequest = $facebook->api('/' . $userFriending["id"] . '/friends/' . $userBeingFriended["id"], 'POST', array(
                        'access_token' => $userFriending["access_token"],
                            )
                    );
                    $friendAccept = $facebook->api('/' . $userBeingFriended["id"] . '/friends/' . $userFriending["id"], 'POST', array(
                        'access_token' => $userBeingFriended["access_token"],
                            )
                    );
                }
            }
            $f++;
        }
        $f = 1;
        $i++;
    }
}
echo json_encode($users);
?>