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
$sql = "select * from user";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_OBJ);


?>
<?php foreach ($users as $user):?>
<tr>
   <td><?= $user->auth_id?></td>
    <td><?= $user->first_name?></td>
    <td> <?= $user->infix?></td>
    <td> <?= $user->last_name?></td>
    <td> <?= $user->phone_number?></td>
    <br>
</tr>
<?php endforeach; ?>
<?php
// update
$auth_id    = 1;
$sql        = 'select * from `user` where auth_id = :auth_id';
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
        $statement_user = $conn->prepare($user_table);
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
                ]
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
                    <h4 class="text-right">Profile gegevens</h4>
                </div>
                 <form action="edit-info" method="POST">
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Naam</label><input  type="text" class="form-control" name="first_name" id="id" placeholder="<?= $user->first_name ?>" value=""></div>
                        <div class="col-md-6"><label class="labels">Tussenvoegsel</label><input type="text" class="form-control" name="infix" value="" placeholder="tussenvoegsel"></div>
                        <div class="col-md-6"><label class="labels">Achternaam</label><input  type="text" class="form-control" name="last_name" value="" placeholder="Achternaam"></div>
                        <div class="col-md-6"><label class="labels">Telefoonnummer</label><input type="text" class="form-control" name="phone_number" placeholder="Telefoonnummer" value=""></div>

                    </div>
                    <div class="mt-5 text-center">
                        <input type="submit" class="btn btn-primary profile-button" value="save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



