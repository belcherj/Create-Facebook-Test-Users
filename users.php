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
$name = array("Stephanie MacDonald",
"Sophie Forsyth",
"Sonia Greene",
"Zoe Paige",
"Hannah Slater",
"John Howard",
"Vanessa Butler",
"Adrian Ball",
"Olivia Young",
"Gabrielle Mathis",
"Fiona Langdon",
"Vanessa Churchill",
"Alexander Buckland",
"Elizabeth Ogden",
"Thomas Lambert",
"Jane Peake",
"Matt Ogden",
"Deirdre Smith",
"Nicholas Scott",
"Bella Lawrence",);


for ($i = 0; $i < $numberUsers; $i++) {
    $userResponse = $facebook->api('/' . $appId . '/accounts/test-users', 'POST', array(
        'installed' => true,
        'name' => $name[$i],
        'locale' => 'en_US',
        'method' => 'post',
        'access_token' => $access_token,
            )
    );
    $user = array(
        "id" => $userResponse["id"],
        "name" => $name[$i],
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