<?php
   // Include php JWT implementation library
   // https://github.com/firebase/php-jwt/tree/master/src

   require_once ("../lib/sso/jwt/JWT.php");
   require_once ("../lib/sso/jwt/BeforeValidException.php");
   require_once ("../lib/sso/jwt/ExpiredException.php");
   require_once ("../lib/sso/jwt/SignatureInvalidException.php");

   // Enter the same secret key here: %Your_iSpring_Learn_domain%/settings/sso/jwt
   const EXAMPLE_JWT_SECRET_KEY                = '********';
   const EXAMPLE_JWT_ENCODE_ALG                = 'HS256';

   $jwtString = $_GET['jwt'];
   try
   {
       $token = JWT::decode($jwtString, EXAMPLE_JWT_SECRET_KEY, EXAMPLE_JWT_ENCODE_ALG);
       $email = isset($token->email) ? $token->email : '';
       print("User with email {$email} is trying to logout!");
   }
   catch (Exception $e)
   {
       // If that user doesn't exist, send a 401 Error
       header('HTTP/1.0 401 Unauthorized');
       exit(0);
   }
