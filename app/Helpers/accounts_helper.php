<?php
/**
 * Check if the user is logged in
 * 
 * @return bool
 */
function user_loggedin() {
    $session = session();

    if(empty($session->get('user_id')) && empty($session->get('user_loggedin'))) {
        return false;
    }

    return true;
}
?>