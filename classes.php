<?php
	include "connection.php";

    class Profile{
        var $cardnum;
        var $cardtype;
        var $name;
        var $birthday;
        var $address;
        var $email;
        var $contact;

        function __autoload($className){
            echo $className;
        }

        function __construct($cardnum,$cardtype,$name,$birthday,$address,$email,$contact){
            $this->cardnum = $cardnum;
            $this->cardtype = $cardtype;
            $this->name = $name;
            $this->birthday = $birthday;
            $this->address = $address;
            $this->email = $email;
            $this->contact = $contact;
        }
    }

    class Items{
        var $id;
        var $name;
        var $price;

        function __autoload($className){
            echo $className;
        }

        function __construct($id,$name,$price){
            $this->id = $id;
            $this->name = $name;
            $this->price = $price;
        }
    }

    class Cart{
        var $id;
        var $name;
        var $price;
        var $num;

        function __autoload($className){
            echo $className;
        }

        function __construct($id,$name,$price,$num){
            $this->id = $id;
            $this->name = $name;
            $this->price = $price;
            $this->num = $num;
        }
    }

    class Logs{
        var $id;
        var $cardnum;
        var $itemid;
        var $name;
        var $total;
        var $quantity;
        var $discount;
        var $staff;
        var $date;

        function __autoload($className){
            echo $className;
        }

        function __construct($id,$cardnum,$itemid,$name,$total,$quantity,$discount,$staff,$date){
            $this->id = $id;
            $this->cardnum = $cardnum;
            $this->itemid = $itemid;
            $this->name = $name;
            $this->total = $total;
            $this->quantity = $quantity;
            $this->discount = $discount;
            $this->staff = $staff;
            $this->date = $date;
        }
    }

    class Register{
        var $id;
        var $name;
        var $birthday;
        var $address;
        var $email;
        var $contact;

        function __autoload($className){
            echo $className;
        }

        function __construct($id,$name,$birthday,$address,$email,$contact){
            $this->id = $id;
            $this->name = $name;
            $this->birthday = $birthday;
            $this->address = $address;
            $this->email = $email;
            $this->contact = $contact;
        }
    }

    class DiscountDay{
        var $day;
        var $disc;

        function __autoload($className){
            echo $className;
        }

        function __construct($day,$disc){
            $this->day = $day;
            $this->disc = $disc;
        }
    }

    class ItemDiscount{
        var $id;
        var $name;
        var $disc;

        function __autoload($className){
            echo $className;
        }

        function __construct($id,$name,$disc){
            $this->id = $id;
            $this->name = $name;
            $this->disc = $disc;
        }
    }

    class StaffType{
        var $type;

        function __autoload($className){
            echo $className;
        }

        function __construct($type){
            $this->type = $type;
        }
    }

    class Admin{
        var $username;
        var $type;

        function __autoload($className){
            echo $className;
        }

        function __construct($username,$type){
            $this->username = $username;
            $this->type = $type;
        }
    }
?>
