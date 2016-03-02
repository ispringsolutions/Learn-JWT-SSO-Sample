<?php
   // Include php JWT implementation library
   // https://github.com/firebase/php-jwt/tree/master/src

   require_once ("../lib/sso/jwt/JWT.php");
   require_once ("../lib/sso/jwt/BeforeValidException.php");
   require_once ("../lib/sso/jwt/ExpiredException.php");
   require_once ("../lib/sso/jwt/SignatureInvalidException.php");

   const PROTOCOL_STRING       = 'https://';
   const ISPRING_LEARN_DOMAIN  = '***.ispringlearn.com';
   const ISPRING_JWT_LOGIN_URL = '/sso/login/jwt?jwt=';

   const EXAMPLE_USER_EMAIL                    = 'user@mail.com';
   const EXAMPLE_USER_PASSWORD_ON_YOUR_SERVER  = 'verySecurePassword';

   // Enter the same secret key here: %Your_iSpring_Learn_domain%/settings/sso/jwt
   const EXAMPLE_JWT_SECRET_KEY = '********';
   const EXAMPLE_JWT_ENCODE_ALG = 'HS256';

   function sendVerifiedUserJWTData($userEmail)
   {
       $tokenId    = base64_encode(mcrypt_create_iv(32));
       $issuedAt   = time();
       $expire     = $issuedAt + 60;     // Add 60 seconds

       $data = [
           'iat'   => $issuedAt,         // Time when the token was generated
           'jti'   => $tokenId,          // A unique identifier for the token
           'exp'   => $expire,           // Time to destruct token
           'email' => $userEmail         // iSpring Learn user's email that you verify
       ];

       $jwt = JWT::encode(
           $data,                  // Data to be encoded in the JWT
           EXAMPLE_JWT_SECRET_KEY, // The key for encryption
           EXAMPLE_JWT_ENCODE_ALG  // Algorithm used to encode
       );

       // Redirect to iSpring Learn JWT login page
       $redirectUrl = PROTOCOL_STRING . ISPRING_LEARN_DOMAIN . ISPRING_JWT_LOGIN_URL . $jwt;
       header("Location: " . $redirectUrl);
   }

   // Checks if user is already logged into the system
   // In this example it just returns false
   function isUserAlreadyLogged()
   {
       return false;
   }

   // Get data from your login form.
   // In this example we return example strings
   function getEmailFormField()
   {
       return EXAMPLE_USER_EMAIL;
   }

   function getPasswordFormField()
   {
       return EXAMPLE_USER_PASSWORD_ON_YOUR_SERVER;
   }

   // Check if a user exists in your system. Connect to your database and check if the user exists and the password is valid   
function isUserExistsAndPasswordValid($userEmail, $userPassword)
   {
       return ($userEmail == EXAMPLE_USER_EMAIL) &&
     ($userPassword == EXAMPLE_USER_PASSWORD_ON_YOUR_SERVER);
   }

   function processJWTAuthorization()
   {
       if (isUserAlreadyLogged())
       {
           // If your user is already logged into your system, you can get his email from session or etc.
           sendVerifiedUserJWTData(EXAMPLE_USER_EMAIL);
       }
       else  // User should log on to your system. Prompt user to enter an email and password
       {
           $userEmail = getEmailFormField();
           $userPassword = getPasswordFormField();
           if (isUserExistsAndPasswordValid($userEmail, $userPassword))
           {
               sendVerifiedUserJWTData($userEmail);
               return;
           }
       }
       // If that user doesn't exist, send a 401 Error
       header('HTTP/1.0 401 Unauthorized');
       exit(0);
   }

   // Let's start!
   processJWTAuthorization();
