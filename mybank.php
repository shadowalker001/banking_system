<?php
/*
*@authour kc@shadowalker
*Introduction to Object Oriented Programming
*At the end of this lecture we will be able to use oop to build an application that solves a particular problem
*like this below!
1. It registers a User!
2. Every User can be able to credit his/her account
3. It keeps record of Users Balance
4. Every User can delete his record whenever he wishes!
5. Its able to show all users and thier record
6. All this is done without communication to a database Oops! :), its amazing right? Enjoy!
**#{DONT FORGET TO LIKE BEKLOW}**
*/
session_start();
class BankingSystem{
    public $balance=0;

    public function create_user($user){
        $process = "User ".$user." created successfully";
        
        if(isset($_SESSION['all_users'])){
            $all_users_array = $_SESSION['all_users'];
            $all_balance_array = $_SESSION['all_balance'];
        }else{
            $all_users_array = array();
            $all_balance_array = array();
        }
        
        if(!in_array($user, $all_users_array)){
            array_push($all_users_array, $user);
            array_push($all_balance_array, $this->balance);
        }

        $_SESSION['all_users'] = $all_users_array;
        $_SESSION['all_balance'] = $all_balance_array;
        
        return self::user_balance($user, $this->balance, $process);
    }

    public function user_balance($user='', $balance=0, $process=''){
        if($process==''){
            $users=$_SESSION['all_users'];
            $balances=$_SESSION['all_balance'];
            for($count=0; $count<count($users); $count++){
                if($users[$count] == $user){
                    return "Dear ".$user.", your Account Balance is #".$balances[$count];
                }
            }
        }else{
            $_SESSION['last_user'] = $user;
            $_SESSION['last_balance'] = $balance;
            return $process."<br>Dear ".$user.", your new Balance is #".$balance;
        }
    }

    public function credit_user_account($user, $balance){
        $all_users_array = $_SESSION['all_users'];
        $all_balance_array = $_SESSION['all_balance'];
        for($count=0; $count<count($all_users_array); $count++){
            $each_user=$all_users_array[$count];
            if($each_user == $user){
                $balance += $all_balance_array[$count];
                $all_balance_array[$count] = $balance;
                $_SESSION['all_balance'] = $all_balance_array;
                $_SESSION['last_user'] = $user;
                $_SESSION['last_balance'] = $balance;
                return "Dear ".$user.", your account has been credited successfully, your new Balance is #".$balance;
            }
        }
    }


    public function delete_user($user){
        if(isset($_SESSION['all_users'])){
            $new_array=array();
            $all_users_array=$_SESSION['all_users'];
            for($count=0; $count<count($all_users_array); $count++){
                $each_user=$all_users_array[$count];
                if($each_user != $user){
                    array_push($new_array, $each_user);
                }
            }
            $_SESSION['all_users'] = $new_array;
            return "Dear ".$user.", its painfull to see you go. Thank you for banking with us";
        }else{
            return "User ".$user." not registered!";
        }
    }

    public function last_transaction(){
        if(isset($_SESSION['last_user']) && isset($_SESSION['last_balance'])){
            return "Last User: ".$_SESSION['last_user']."<br>User Balance: ".$_SESSION['last_balance'];
        }else{
            return "No Last Transaction found!";
        }
        
    }
    
    public function all_users(){
        if(isset($_SESSION['all_users']) && isset($_SESSION['all_balance'])){
            $users=$_SESSION['all_users'];
            $balances=$_SESSION['all_balance'];
                ?><table class="">
                <thead class="">
                    <tr>
                        <th>Users:</th>
                        <th>Balance:</th>
                    </tr>
                </thead>
                    <tbody class="">      
                <?php
            for($count=0; $count<count($users); $count++){
                    echo "<tr>";
                        echo "<td>".$users[$count]."</td>";
                        echo "<td>".$balances[$count]."</td>";
                    echo "</tr>";
            }
                ?>          
                        </tbody>
                    </table>
                <?php
        }else{
            return "No Users Info!";
        }
    }
}
$bank = new BankingSystem();

class SavingsAccout extends BankingSystem{

    public function transferMoney($owner, $beneficiary, $amount){
        $all_users_array = $_SESSION['all_users'];
        $all_balance_array = $_SESSION['all_balance'];
        for($count=0; $count<count($all_users_array); $count++){
            $each_user=$all_users_array[$count];
            if($each_user == $owner){
                $owner_balance = $all_balance_array[$count]-$amount;
                $all_balance_array[$count] = $owner_balance;
                for($x=0; $x<count($all_users_array); $x++){
                    $each_user=$all_users_array[$count];
                    if($each_user==$beneficiary){
                        $beneficiary_balance = $all_balance_array[$x]+$amount;
                        $all_balance_array[$x] = $beneficiary_balance;
                        //return $all_balance_array[$x];
                    }
                }
            }
        }
        $_SESSION['all_balance'] = $all_balance_array;
        $_SESSION['last_user'] = $owner;
        $_SESSION['last_balance'] = $owner_balance;
        return "Dear ".$owner.", your have successfully transered $amount to $beneficiary, your new Balance is #".$owner_balance;
    }
    
}

/* CLEARS ALL THE RECORD AND BANKING CACHE :) */
//session_destroy();

/* SET A USER NAME */
//$new_user="User";

/* CALLS THE FUNCTION THAT CREATES A USER */
//echo $bank->create_user($new_user);

/* CREDITS USER ACCOUNT */
//echo BankingSystem::credit_user_account($new_user, 500);

/* SHOW INFO ABOUT LAST TRANSACTION */
//echo $bank->last_transaction();

/* DELETS USER INFO */
//echo $bank->delete_user($new_user);

//echo "<br><br>";

/* SHOWS ALL USERS AND THIER BALANCE */
//$all_users=$bank->all_users();

if(isset($_POST['process'])){
    $process='';
    $user = $_POST['user'];
    $value = $_POST['value'];
    $transaction = $_POST['transaction'];
    $beneficiary = $_POST['beneficiary'];

    if($transaction=='register'){
        echo $bank->create_user($user);
    }
        elseif($transaction=='credit'){
          echo BankingSystem::credit_user_account($user, $value);
        }
        elseif($transaction=='balance'){
            echo BankingSystem::user_balance($user, $value, $process);
        }
        elseif($transaction=='delete'){
            echo $bank->delete_user($user);
        }
        elseif($transaction=='all_users'){
            echo $bank->all_users();
        }elseif($transaction=='clear'){
            session_destroy();
        }elseif($transaction=='transfer'){
            echo SavingsAccout::transferMoney($user, $beneficiary, $value);
        }
    else{
        echo "No Transaction To perform!";
    }
}
?>
<br><br>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Banking App by shadowalker</title>
</head>
<body>
    <form action="" method="POST">
        <label for="transaction">Transaction Type:</label>
            <select name="transaction" class="form-control" >
                <option value="clear">Clear Record</option>
                <option value="register">Register User</option>
                <option value="credit">Credit Account</option>
                <option value="balance">User Balance</option>
                <option value="delete">Delete Record</option>
                <option value="transfer">Transfer Money</option>
                <option value="all_users">Show All Users</option>
            </select>

        <label for="Username">Account Name:</label>
        <input type="text" name='user'>

        <label for="value">Amount:</label>
        <input type="text" name='value' placeholder="dont fill while registering">

        <label for="value">Beneficiary:</label>
        <input type="text" name='beneficiary' placeholder="fiil only when you want to transfer money!">

        <br>
        <input type="submit" name='process'>
    </form>
</body>
</html>