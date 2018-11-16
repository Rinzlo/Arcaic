<?php
declare(strict_types=1);

namespace App\Controllers;


use App\Auth;
use App\Config;
use App\Flash;
use App\Models\User;
use Core\View;

class Accounts extends \Core\Controller
{
    // Login

    /**
     * Show the log in page
     */
    public function loginAction(): void
    {
        View::renderTemplate('Accounts/login.html.twig');
    }

    /**
     * Log in the user
     */
    public function loggedinAction(): void
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);
        
        $remember_me = isset($_POST['remember_me']);

        if($user){
        	
	        Auth::login($user, $remember_me);
	        
	        Flash::addMessage('Login successful');
        	
            $this->redirect(Auth::getReturnToPage());
            
        }else
        	
        	Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);
        	
            View::renderTemplate('Accounts/login.html.twig', [
                'email' => $_POST['email'],
	            'remember_me' => $remember_me
            ]);
    }
	
	/**
	 * Log out a user
	 */
    public function logoutAction(): void
    {
    	Auth::logout();
    	
    	$this->redirect('/'.Config::APP_NAME.'/accounts/show-logout-message');
    }
	
	/**
	 * Show a "logged out" flash message and redirect to the homepage. Necessary to use the flash messages
	 * as they use the session and at the end of the logout method (logoutAction) the session is destroyed
	 * so a new action needs to be called in order to use the session.
	 */
    public function showLogoutMessageAction(): void
    {
	    Flash::addMessage('Logout successful');
	
	    $this->redirect();
    }

    // Registration
    /**
     * Validate if email is available (AJAX) for a new signup
     */
    public function validateEmailAction(): void
    {
        $is_valid = ! User::emailExists($_GET['email']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }

    public function registerAction(): void
    {
        View::renderTemplate('Accounts/register.html.twig');
    }

    public function createAction(): void
    {
        $user = new User($_POST);

        if($user->save()) {

            $this->redirect('/'.Config::APP_NAME.'/accounts/success');

        }else {
            View::renderTemplate('Accounts/register.html.twig', [
                'user' => $user
            ]);
        }
    }

    public function successAction(): void
    {
        View::renderTemplate('Accounts/registered.html.twig');
    }
}