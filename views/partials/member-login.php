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
$sql        = 'select * from `auth` where id = : ';
$stmt       =   $conn->prepare($sql);

$stmt->execute(
    [
        'auth_id'=>$auth_id
    ]
);
$user       =   $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST)) {
    if (!empty($_POST['password'])
        && !empty($_POST['email'])
    ) {
        $password = $_POST['password'];
        $email = $_POST['email'];

        $params = [
            'password' => $password,
            'email' => $email
        ];
        $login = '';

        if (empty($user)) {
            $login = '
        INSERT INTO `auth`
        VALUES (null, :password, :email)';
        } else {
            $params['loginid'] = $user;
            $login = '
        UPDATE `auth`
        SET password    =   :password,
            email       =    :email
            WHERE id    = :id';
        }
        $statement_auth = $conn->prepare($login);
        $statement_auth->execute($params);
        header("Location: edit-login");
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
                <form action="edit-login" method="POST">
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



