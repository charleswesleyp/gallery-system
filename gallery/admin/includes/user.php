

<?php
    class User{

        public $id;
        public $username;
        public $password;
        public $first_name;
        public $last_name;



        public static function find_all_users()
        {
        global $database;
        $user_set = Self::find_this_query("SELECT * FROM users");
        return $user_set;
        }


        public static function find_user_by_id($id){
            global $database;
            $user_by_id_set = Self::find_this_query("SELECT * FROM users WHERE id = $id LIMIT 1");

            return !empty($user_by_id_set) ? array_shift($user_by_id_set) : false;
        }

        public static function find_this_query($sql){
            global $database;
            $result = $database->query($sql);
            $the_object_array = array();

            while($row = mysqli_fetch_array($result)){
                $the_object_array[] = Self::instantiation($row);
            }
            return $the_object_array;
        }


        public static function verify_user($username, $password){
            global $database;

            $username = $database->escape_string($username);
            $password = $database->escape_string($password);

            $sql = "SELECT * FROM `users` WHERE `username` = '{$username}' AND `password` = '{$password}' LIMIT 1";
            $the_user_result = Self::find_this_query($sql);
            return !empty($the_user_result) ? array_shift($the_user_result) : false;

        }


        public static function instantiation($found_user){


            $the_object = new Self;
//            $the_object->id         = $found_user['id'];
//            $the_object->username   = $found_user['username'];
//            $the_object->password   = $found_user['password'];
//            $the_object->first_name = $found_user['firstname'];
//            $the_object->last_name  = $found_user['lastname'];

            foreach ($found_user as $attribute => $value){

                if($the_object->has_attribute($attribute)){
                    $the_object->$attribute = $value;
                }
                }
                return $the_object;
            }


            public function has_attribute($attribute){
             $object_properties = get_object_vars($this);
             return array_key_exists($attribute, $object_properties);
            }



            public function create(){
                global $database;

                $sql = "INSERT INTO users (username, password, firstname, lastname) VALUES ('" . $database->escape_string($this->username) . "' , '". $database->escape_string($this->password)."' , '". $database->escape_string($this->first_name)."' , '".$database->escape_string($this->last_name)."')    ";

                if($database->query($sql)){
                    $this->id = $database->insert_id();
                    return true;
                    echo "bitch";
                }  else{

                    return false;
                }
            }


            public function update(){

                global $database;

                $sql = "UPDATE users SET username = '". $database->escape_string($this->username)."' ,    password = '". $database->escape_string($this->password)."'   , firstname = '". $database->escape_string($this->first_name)."' , lastname = '". $database->escape_string($this->last_name)."'   WHERE id = ".$database->escape_string($this->id)."  ";
                $database->query($sql);

                return mysqli_affected_rows($database->connection ) ? true : false;

            }


            public function delete(){
                global $database;

                $sql = "DELETE FROM users WHERE id = ". $database->escape_string($this->id) ." LIMIT 1";
                $database->query($sql);

                return mysqli_affected_rows($database->connection ) ? true : false;


            }



        }



    ?>