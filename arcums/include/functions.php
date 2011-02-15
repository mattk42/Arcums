<?php

function generateCode($length = 10) {
    $password = "";
    $chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
    srand((double)microtime() * 1000000);
    
    for ($i = 0;$i < $length;$i++) {
        $password = $password . substr($chars, rand() % strlen($chars) , 1);
    }
    
    return $password;
}

function username_exists($username) {
    $query = mysql_query("SELECT username FROM accounts WHERE username = '$username' LIMIT 1");
    
    return (mysql_num_rows($query) > 0) ? TRUE : FALSE;
}

function is_valid_email($email) {

    // First, we check that there's one @ symbol,
    // and that the lengths are right.

    
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {

        // Email invalid because wrong number of characters
        // in one section or wrong number of @ symbols.

        
        return false;
    }

    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    
    for ($i = 0;$i < sizeof($local_array);$i++) {
        
        if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            
            return false;
        }
    }

    // Check if domain is IP. If not,
    // it should be valid domain name

    
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        $domain_array = explode(".", $email_array[1]);
        
        if (sizeof($domain_array) < 2) {
            
            return false; // Not enough parts to domain

            
        }
        
        for ($i = 0;$i < sizeof($domain_array);$i++) {
            
            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$", $domain_array[$i])) {
                
                return false;
            }
        }
    }
    
    return true;
}
?>
