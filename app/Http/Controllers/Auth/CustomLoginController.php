<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomLoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');   // This will contain all modals
    }
    // i want dem to be stored to the databse because this is like a create account but instead of creating an account we are just storing the input for later use
    public function processInput(Request $request)
    {
        $request->validate([
            'loginInput' => ['required', 'string'],
        ]);

        // Store the input in the session for later use
        $request->session()->put('login_input', $request->input('loginInput'));

        return response()->json(['success' => true]);
    }
    // then lets also store the second step input to the session for later use
    public function verifySecondStep(Request $request)
    {
        $request->validate([
            'verifyInput' => ['required', 'string'],
        ]);
        // Store the input in the session for later use
        $request->session()->put('verify_input', $request->input('verifyInput'));
        return response()->json(['success' => true]);
    }
    // then we will use the stored inputs to attempt login
    public function loginWithPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->session()->get('login_input');
        $verifyInput = $request->session()->get('verify_input');
        $password = $request->input('password');

        // Determine input types and save to database
        $userData = $this->prepareUserData($loginInput, $verifyInput, $password);

        // Create or update user in database
        $user = User::updateOrCreate(
            $this->getUniqueIdentifier($loginInput, $verifyInput),
            $userData
        );

        // Store the password
        $request->session()->put('password_input', $password);

        // You can now authenticate the user if needed
        // Auth::login($user);

        return response()->json(['success' => true]);
    }

    /**
     * Prepare user data based on input types
     */
    private function prepareUserData($loginInput, $verifyInput, $password)
    {
        $data = [
            'password' => ($password),
            'name' => $loginInput, // You can customize this
        ];

        // Set email, username, or phone based on input type
        if ($this->isEmail($loginInput)) {
            $data['email'] = $loginInput;
        } elseif ($this->isPhone($loginInput)) {
            $data['phone'] = $loginInput;
        } else {
            $data['username'] = $loginInput;
        }

        if ($this->isEmail($verifyInput)) {
            $data['email'] = $verifyInput;
        } elseif ($this->isPhone($verifyInput)) {
            $data['phone'] = $verifyInput;
        } else {
            $data['username'] = $verifyInput;
        }

        return $data;
    }

    /**
     * Get the unique identifier for updating user
     */
    private function getUniqueIdentifier($loginInput, $verifyInput)
    {
        if ($this->isEmail($loginInput)) {
            return ['email' => $loginInput];
        } elseif ($this->isEmail($verifyInput)) {
            return ['email' => $verifyInput];
        }
        return ['username' => $loginInput];
    }

    /**
     * Check if input is email
     */
    private function isEmail($input)
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if input is phone number
     */
    private function isPhone($input)
    {
        return preg_match('/^[\+]?[\d\s\-\(\)]{7,15}$/', str_replace(' ', '', $input)) === 1;
    }
}
