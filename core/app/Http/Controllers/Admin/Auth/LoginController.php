<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laramin\Utility\Onumoti;
use Illuminate\Support\Facades\Log; 

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public $redirectTo = 'admin';

    public function showLoginForm()
    {
        $pageTitle = "Admin Login";
        return view('admin.auth.login', compact('pageTitle'));
    }

    protected function guard()
    {
        return auth()->guard('admin');
    }

    public function username()
    {
        return 'username';
    }

public function login(Request $request)
{
    Log::info('LOGIN START', [
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'ip' => $request->ip(),
        'input' => $request->except(['password'])
    ]);

    try {
        // Step 1: Validation
        Log::info('STEP 1: Before validateLogin');
        $this->validateLogin($request);
        Log::info('STEP 2: After validateLogin');

        // Step 2: Regenerate CSRF token
        $request->session()->regenerateToken();
        Log::info('STEP 3: Session token regenerated');

        // Step 3: Onumoti processing (license / custom check)
        Log::info('STEP 4: Before Onumoti');
        try {
            Onumoti::getData();
            Log::info('STEP 5: After Onumoti');
        } catch (\Exception $e) {
            Log::error('ONUMOTI ERROR', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            // Continue login flow even if Onumoti fails
        }

        // Step 4: Redirect handling
        $redirect = $request->redirect ?? null;
        if ($redirect) {
            try {
                $routes = \Route::getRoutes();

                if (@explode('/', $redirect)[0] != 'admin') {
                    $redirect = 'admin/' . $redirect;
                }

                $headers = get_headers(route('home') . '/' . $redirect);
                $status = substr($headers[0], 9, 3);

                Log::info('Redirect check', ['redirect' => $redirect, 'status' => $status]);

                if ($status != 404) {
                    $requestRedirectUrl = Request::create($redirect ?? 'admin');
                    $routes->match($requestRedirectUrl);

                    $this->redirectTo = $redirect;

                    Log::info('Valid redirect detected', ['redirectTo' => $redirect]);
                }
            } catch (\Exception $e) {
                Log::error('Redirect validation failed', ['error' => $e->getMessage()]);
            }
        }

        // Step 5: Check too many login attempts
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {

            Log::warning('Too many login attempts', [
                'ip' => $request->ip(),
                'username' => $request->username ?? null
            ]);

            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Step 6: Attempt login
        Log::info('Before attemptLogin');
        if ($this->attemptLogin($request)) {
            Log::info('LOGIN SUCCESS', [
                'username' => $request->username ?? null,
                'ip' => $request->ip()
            ]);

            return $this->sendLoginResponse($request);
        }

        // Step 7: Login failed
        Log::warning('LOGIN FAILED', [
            'username' => $request->username ?? null,
            'ip' => $request->ip()
        ]);

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);

    } catch (ValidationException $e) {
        // Validation failed
        Log::error('VALIDATION EXCEPTION', [
            'errors' => $e->errors(),
            'message' => $e->getMessage(),
        ]);
        throw $e;
    } catch (\Exception $e) {
        // Catch-all for other exceptions
        Log::error('LOGIN EXCEPTION', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        throw $e;
    }
}

    public function logout(Request $request)
    {
        $this->guard('admin')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect($this->redirectTo);
    }
}