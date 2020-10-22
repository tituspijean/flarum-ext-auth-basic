<?php

namespace TitusPiJean\Flarum\BasicAuth\Middleware;

use Flarum\User\Guest;
use Flarum\User\User;
use Flarum\User\UserRepository;
use Flarum\Http\AccessToken;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class BasicAuthMiddleware implements Middleware
{
    public function process(Request $request, Handler $handler): Response
    {
        // Checking and retrieving credentials
        if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
            $request = $request->withAttribute('actor', new Guest);
        } else {
            // Retrieve user information
            $email = $_SERVER['HTTP_EMAIL'];
            $uid = $_SERVER['PHP_AUTH_USER'];
            $pwd = $_SERVER['PHP_AUTH_PW'];
            // Find the user
            $user = UserRepository::findByIdentification(['username' => $uid,'email' => $email]);
            // If the user does not exist, create it
            if (is_null($user)) {
                $user = User::register($uid, $email, $pwd);
                $user->activate();
                $user->save();
            }
            // Generate a authentication token and assign it to the actor
            $token = AccessToken::generate($user->id);
            $token->touch();
            $actor = $token->user;
            $request = $request->withAttribute('actor', $actor);
        }
        // Proceed
        return $handler->handle($request);
    }

}
