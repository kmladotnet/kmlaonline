#!/usr/bin/php
<?php

/**
 * Prosody XMPP Server External Authentication w/ Panu
 *
 *
 * <http://prosody.im/>
 * <https://code.google.com/p/prosody-modules/wiki/mod_auth_external>
 *
 *
 * @author Ville Korhonen <ville.korhonen@ylioppilastutkinto.fi>
 * @license GPLv3
 * @version 0.0.0
 * @package panu
 */

/*
 Config:
  /etc/prosody/prosody.cfg.lua:
    authentication = "external"
    external_auth_protocol = "generic"
    external_auth_command = "path/to/this/file"


 Commands: (TBD)
  auth:
   $0 auth:username:domain:password
   $0 auth:ville:digabi.fi:mysecretpassword
  isuser:
   $0 isuser:username:domain
   $0 isuser:ville:digabi.fi
  setpass:
   $0 setpass:username:domain:password
   $0 setpass:ville:digabi.fi:mynewsecretpassword

*/

define("XMPP_TOKEN_PREFIX", "xmpp_");
define("SEPARATOR_CHAR", ":");
define("AUTHLOG", "prosody_external.log");

define("ABSPATH", dirname(__FILE__));
require_once(__DIR__."/soreeengine/SoreeTools.php");
$bDoInit = false; //remove and you die
require_once(__DIR__."/db-config.php");

/**
 * Check if user exists in domain
 * @param string $user Username
 * @param string $domain Domain
 * @return boolean 0 if failure (user doesn't exist in domain), 1 if success (user exists in domain)
 */
function isuser($user, $domain) {
    global $member;
    if($domain != 'kmlaonline.net') {
        return 0;
    }

    return $member->getMember($user, 1) === false ? 0 : 1;
}

/**
 * Change user password
 *
 * @todo Should this be able to create new users?
 * @todo Should this do some sort of testing? (User exists in domain etc.?)
 *
 * @param string $user Username
 * @param string $domain Domain
 * @param string $password New password
 * @return boolean 0 if failure (password wasn't changed), 1 if success (password was changed)
 */
function setpass($user, $domain, $password) {
    // TODO
    return 0;
}

/**
 * Authenticate user (check, that user exists in domain w/ specified password)
 *
 * @param string $user Username
 * @param string $domain Domain
 * @param string $password Password
 * @return boolean 0 if failure (user doesn't exist in domain, or password is invalid), 1 if success (user exists in domain, password is valid)
 */
function auth($user, $domain, $password) {
    global $member;
    if($domain != 'kmlaonline.net') {
        return 0;
    }
    return $member->authMember($user, $password) === 0 ? 1 : 0;
}

/**
 * CLI
 */
function cli() {
    $input = fgets(STDIN);
    if($input === false) {
        return;
    }
    // Parse STDIN, remove trailing whitespace, split at first SEPARATOR_CHAR, so we get command & "the rest of input"
    $command = explode(SEPARATOR_CHAR, trim($input), 2);

    // Split username, domain and password (in this order, max. 3 separate pieces, password might contain SEPARATOR_CHARs)
    $params = explode(SEPARATOR_CHAR, $command[1], 3);

    switch ($command[0]) {
        case "auth":
            return auth($params[0], $params[1], $params[2]);
            break;
        case "isuser":
            return isuser($params[0], $params[1]);
            break;
        case "setpass":
            return setpass($params[0], $params[1], $params[2]);
            break;
        default:
            return 0;
    }
    return $res;
}

if (php_sapi_name() == 'cli') {
    while(true) {
        echo cli();
    }
}
?>
