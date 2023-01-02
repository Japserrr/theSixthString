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
$userId    = 1;
$sql        = 'select address_id from user_has_address where auth_id = :userId';
$stmt       =   $conn->prepare($sql);

$stmt->execute(
    [
        'userId' => $userId
    ]
);
$address       =   $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST)) {
    if (!empty($_POST['street_name'])
        && !empty($_POST['zipcode'])
        && !empty($_POST['house_number'])
        && !empty($_POST['city'])
        && !empty($_POST['country'])
    ) {
        $street_name = $_POST['street_name'];
        $zipcode = $_POST['zipcode'];
        $house_number = $_POST['house_number'];
        $city = $_POST['city'];
        $country = $_POST['country'];

        $params = [
            'street_name' => $street_name,
            'zipcode' => $zipcode,
            'house_number' => $house_number,
            'city' => $city,
            'country' => $country
        ];

        $address_table = '';
        if (empty($address)) {
            $address_table = '
            INSERT INTO `address` 
            VALUES (null, :street_name, :zipcode, :house_number, :city, :country)';
        } else {
            $params['addressId'] = $address;
            $address_table = '
            UPDATE `address` 
            SET street_name = :street_name, 
                zipcode = :zipcode, 
                house_number = :house_number, 
                city = :city, 
                country = :country  
            WHERE id = :id';
        }

        $statement_table = $conn->prepare($address_table);
        $statement_table->execute($params);

        header('Location: ./edit-address');
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
                        <div class="col-md-6">
                            <label class="labels">Straat</label>
                            <input type="text" class="form-control" name="street_name" placeholder="Straat naam" value="" required>
                        </div>
                        <div class="col-md-6">
                            <label class="labels">Huisnummer</label>
                            <input type="text" class="form-control" name="house_number" placeholder="Huisnummer" value="" required>
                        </div>
                        <div class="col-md-6"><label class="labels">Postcode</label>
                            <input type="text" class="form-control" name="zipcode" placeholder="Postcode" value=""></div>
                        <div class="col-md-6"><label class="labels">Stad</label>
                            <input type="text" class="form-control" name="city" placeholder="Stad" value=""></div>
                        <div class="col-md-6"><label class="labels">Land</label>
                        <select type="text" class="form-control" name="country">
                            <option disabled selected>Uw land</option>
                            <option value="Nederland">Nederland</option>
                            <option value="België">België</option>
                            <option value="Luxemburg">Luxemburg</option>
                        </select>

                    </div>
                    <div class="mt-5 text-center">
                        <input type="submit" class="btn btn-primary profile-button" value="save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



