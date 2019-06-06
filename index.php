<?php
/*
*Introduction to Object Oriented Programming
*At the end of this lecture we will be able to use oop to build an application that solves a particular problem
*like this below!
*/
session_start();
class BankingSystem{
    public $user='';
    public $balance=0;
    public $process;

    public function create_user($user){
        $process = "User ".$user." created successfully";
        
        if(isset($_SESSION['all_users'])){
            $all_users_array = $_SESSION['all_users'];
        }else{
            $all_users_array = array();
        }
        
        if(!in_array($user, $all_users_array)){
            array_push($all_users_array, $user);
        }

        $_SESSION['all_users'] = $all_users_array;
        
        return self::user_balance($user, $this->balance, $process);
    }

    public function user_balance($user='', $balance=0, $process){
        $_SESSION['balance'] = $balance;
        return $process."<br>Dear ".$user.", your new Balance is #".$balance;
    }

    public function credit_user_account($user, $balance){
        $_SESSION['user'] = $user;
        if(isset($_SESSION['balance'])){
            $balance = $balance + $_SESSION['balance'];
        }
        $_SESSION['balance'] = $balance;
        return "Dear ".$user.", your account has been credited successfully, your new Balance is #".$balance;
    }


    public function delete_user($user){
        session_destroy();
        return "Dear ".$user.", its painfull to see you go. Thank you for banking with us";
    }

    public function last_transaction(){
        if(isset($_SESSION['user']) && isset($_SESSION['balance'])){
            return "last User: ".$_SESSION['user']."<br>Balance: ".$_SESSION['balance'];
        }else{
            return "No Last Trasaction found!";
        }
        
    }
    
    public function all_users(){
        if(isset($_SESSION['all_users'])){
            $users=$_SESSION['all_users'];
            return $users;
        }else{
            return array("No Users Info!");
        }
        
    }
}
$bank = new BankingSystem();

//SET A USER NAME
$new_user="Username";

//CALLS THE FUNCTION THAT CREATES A USER
//echo $bank->create_user($new_user);

//CREDITS USER ACCOUNT
//echo BankingSystem::credit_user_account($new_user, 300);

//SHOW INFO ABOUT LAST TRANSACTION
//echo $bank->last_transaction();

//DELETS USER INFO
//echo $bank->delete_user($new_user);

//echo "<br><br>";

//SHOWS ALL USERS
// $all_users=$bank->all_users();
// foreach($all_users as $users){
//     echo $users."<br>";
// }
