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



// display address information
$sql = "select * from address";
$stmt = $conn->prepare($sql);
$stmt->execute();
$addresses = $stmt->fetchAll(PDO::FETCH_OBJ);


?>
<?php foreach ($addresses as $address):?>
    <tr>
        <td><?= $address->street_name?></td>
        <td><?= $address->zipcode?></td>
        <td> <?= $address->house_number?></td>
        <td> <?= $address->city?></td>
        <td> <?= $address->country?></td>
        <br>

    </tr>
<?php endforeach; ?>
<?php

// update
$id    = 1;
$sql        = 'select address_id from user_has_address where auth_id = :id';
$stmt       =   $conn->prepare($sql);

$stmt->execute(
    [
        'id'=>$id
    ]
);
$address       =   $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['street_name'])) {
    $id             = $address['address_id'];
    $street_name    = $_POST['street_name'];
    $zipcode        = $_POST['zipcode'];
    $house_number   = $_POST['house_number'];
    $city           = $_POST['city'];
    $country        = $_POST['country'];
    $password       = $_POST['password'];
    $email          = $_POST['email'];

    $address_table = 'UPDATE `address` set street_name = :street_name, zipcode = :zipcode, house_number = :house_number, city = :city, country = :country  where id = :id';
    $statement_table = $conn->prepare($address_table);
    if
    (
        $statement_table->execute
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
        {
            header("Location: edit-address");
        }
}
?>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-5 border-right">
            <div class="p-1 py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">adres gegevens</h4>
                </div>
                <form action="./edit-address" method="POST">
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Straat</label><input type="text" class="form-control" name="street_name" placeholder="Straat naam" value=""></div>
                        <div class="col-md-6"><label class="labels">Huisnummer</label><input type="text" class="form-control" name="house_number" placeholder="Huisnummer" value=""></div>
                        <div class="col-md-6"><label class="labels">Postcode</label><input type="text" class="form-control" name="zipcode" placeholder="Postcode" value=""></div>
                        <div class="col-md-6"><label class="labels">Stad</label><input type="text" class="form-control" name="city" placeholder="Stad" value=""></div>
                        <div class="col-md-6"><label class="labels">Land</label><input type="text" class="form-control" name="country" placeholder="Uw land" value=""></div>

                    </div>
                    <div class="mt-5 text-center">
                        <input type="submit" class="btn btn-primary profile-button" value="save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



