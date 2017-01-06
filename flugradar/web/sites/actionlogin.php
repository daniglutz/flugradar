<?php
    
    /**
     * can vallidate the login data and give access to the application
     * 
     * @name    actionlogin.php
     * @author  Andreas Trachsel
     * @version 28.12.2016
     *  
     */
    
    
    // take session
    session_start();
    
    // check user data
    userValidate($_POST['user'], $_POST['pwd']);    

    // login ok
    header("Location: ./?site=departures&airport=LSZH");
    
    
	/**
	* check whether user and password are valid
	* 
	* @author  Andreas Trachsel
	* @version 28.12.2016
	* 
	* @param   string user
	* @param   string password
	* @return  void
	*/
    function userValidate($user, $pw) {
        // variables for access validate
        $userValid = false;
        $pwValid = false;

        // create database object
        $db = new Mysql();

        //define SQL query
        $sql = "
        SELECT 
            `id`,
            `username`,
            `password`,
            `admin`
        FROM `users`            
        WHERE `username` = '".$user."'";

        // *** run query ***
        $result = $db->query($sql);

        // *** results? ***
        if($result->num_rows) {
            // ** save result **
            $row = $result->fetch_assoc();

            // check username
            if($row['username'] == $user) {
                $userValid = true;
            }
            // check password
            if(password_verify($pw, $row['password'])) {
                $pwValid = true;
            }

            if($userValid AND $pwValid) {
                setSession($row);
            }
            else {
                setError($userValid, $pwValid);
            }
        }
        else {
            setError($userValid, $pwValid);
        }
    }
    
	/**
	* set session
	* 
	* @author  Andreas Trachsel
	* @version 28.12.2016
	* 
	* @param   array query result
	* @return  void
	*/
    function setSession($row) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['user'] = $row['username'];
        $_SESSION['admin'] = $row['admin'];
    }
    
	/**
	* set error
	* 
	* @author  Andreas Trachsel
	* @version 28.12.2016
	* 
	* @param   boolean user valid (true or false)
	* @param   boolean password valid (true or false)
	* @return  void
	*/
    function setError($userValid, $pwValid) {
        // set error
        if($userValid == false) {
            $_SESSION['error'] = getMessage("Hinweis:", "Benutzer nicht vorhanden", "Bitte geben Sie einen gültigen Benutzernamen ein");
        }
        elseif($pwValid == false) {
            $_SESSION['error'] = getMessage("Hinweis:", "Passwort ungültig", "Bitte geben Sie ein gültiges Passwort ein");
        }
    }
    
?>