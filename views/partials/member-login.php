<?php require 'member-nav.php' ?>
<?php
$conn = getDbConnection();

// code that actually work...in a way.
//update
//     $sql = "update `user`
// set first_name = 'neville',
//     last_name = 'testing'
//where auth_id = 3;";
//
//     $stmt = $conn->query($sql);
//     $stmt->execute();



// display user information
$sql = "select * from auth";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_OBJ);


?>
<?php foreach ($users as $user):?>
    <tr>
        <td><?= $user->id?></td>
        <td><?= $user->email?></td>
        <td> <?= $user->password?></td>
        <br>

    </tr>
<?php endforeach; ?>
<?php
// update 2
$auth_id    = 1;
$sql        = 'select * from `auth` where id = :auth_id';
//$sql        = 'select * from address where id = :id';
//$sql        = 'select * from auth where id = :id';
$stmt       =   $conn->prepare($sql);

$stmt->execute(
    [
        'auth_id'=>$auth_id
    ]
);
$user       =   $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['first_name']) && isset($_POST['last_name'])) {
    $auth_id        = $user['auth_id'];
    $first_name     = $_POST['first_name'];
    $infix          = $_POST['infix'];
    $last_name      = $_POST['last_name'];
    $phone_number      = $_POST['phone_number'];
    $street_name    = $_POST['street_name'];
    $zipcode        = $_POST['zipcode'];
    $house_number   = $_POST['house_number'];
    $city           = $_POST['city'];
    $country        = $_POST['country'];
    $password       = $_POST['password'];
    $email          = $_POST['email'];


    $user_table = 'UPDATE `user` set first_name = :first_name, last_name = :last_name, infix = :infix, phone_number = :phone_number where auth_id = :auth_id';
    $address_table = 'UPDATE `address` set street_name = :street_name, zipcode = :zipcode, house_number = :house_number, city = :city, country = :country  where id = :id';
    $auth_table = 'UPDATE `auth` set password = :password, email = :email where id = :id';

    $statement_user = $conn->prepare($user_table);
    $statement_table = $conn->prepare($address_table);
    $statement_auth = $conn->prepare($auth_table);
    if
    (
        $statement_user->execute
        (
            [
                'auth_id'       => $auth_id,
                'first_name'    => $first_name,
                'infix'         => $infix,
                'last_name'     => $last_name,
                'phone_number'     => $phone_number
//                    'street_name'   => $street_name,
//                    'zipcode'       => $zipcode,
//                    'house_number'  =>$house_number,
//                    'city'          => $city,
//                    'country'       => $country,
//                    'password'      => $password,
//                    'email'         => $email

            ]
        )
    )if
    (
        $statement_table->execute(
            (
            [
                'street_name'   => $street_name,
                'zipcode'       => $zipcode,
                'house_number'  =>$house_number,
                'city'          => $city,
                'country'       => $country
            ]
            )
        )
    )if (
            $statement_auth->execute(
                (
                [
                    'password'      => $password,
                    'email'         => $email
                ]
                )
            )
        )
        {
            header("Location: edit-info");
        }
}
?>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-5 border-right">
            <div class="p-1 py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">inlog gegevens</h4>
                </div>
                <form action="edit-info" method="POST">
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Email adres</label><input type="email" class="form-control" name="email" placeholder="Email adres" value=""></div>
                        <div class="col-md-6"><label class="labels">Password</label><input type="password" class="form-control" name="password" placeholder="password" value=""></div>

                    </div>
                    <div class="mt-5 text-center">
                        <input type="submit" class="btn btn-primary profile-button" value="save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



