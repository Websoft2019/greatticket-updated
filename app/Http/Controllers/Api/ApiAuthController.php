<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\Religion;
use App\Notifications\UserActionNotification;
use App\Notifications\ForgotPassword;
use Exception;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class ApiAuthController extends BaseApiController
{
    use Notifiable;
    // Register, Login, profile, logout API
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:100",
            "email" => "required|email|unique:users,email|max:100",
            "password" => "required",
            "number" => "sometimes|digits_between:10,10",
            // "gender" => "required|in:male,female,others",
        ]);
        DB::beginTransaction();
        try {
            $otp  = rand(100_000, 999_999);
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password,
                // "gender" => $request->gender,
                "contact" => $request->number,
                "address" => $request->address,
                // "otp" => $otp,
                // "otp_expires_at" => now()->addMinute(2),
            ]);

            // Send the notification
            $message = "Your have successfully registered";
            // $user->notify(new UserActionNotification($message));


            // Send the OTP via email
            // Mail::to($user->email)->send(new OtpMail($otp));
            DB::commit();
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
            $data = [
                'access_token' => $token,
            ];

            return $this->sendResponse($data, "User Register Successfully", 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("User registration error.\n Error => " . $th->getMessage());
            return $this->sendError($th->getMessage(), "Registration Failed", 500);
        }
    }

    public function guestlogin()
    {
        DB::beginTransaction();
        try {
            $session_id = Session::get('id') ?? Str::uuid();
            $email = Str::uuid() . ".greatticket@gmail.com";
            $password = Str::random(16);
            $religion = Religion::firstOrFail();
            $user = User::create([
                'session_id' => $session_id,
                'name' => 'Guest User',
                'email' => $email,
                'password' => $password,
                'religion_id' => $religion->id,
            ]);

            DB::commit();
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
            $data = [
                'access_token' => $token,
            ];

            return $this->sendResponse($data, 'Guest user register successfully', 201);
        } catch (Throwable $th) { {
                DB::rollBack();
                Log::error("Guest User registration error.\n Error => " . $th->getMessage());
                return $this->sendError($th->getMessage(), "Registration Failed", 500);
            }
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email|max:100",
            "password" => "required|max:128",
        ]);
        try {
            $user = User::where("email", $request->email)->first();
            if (!is_null($user) && Hash::check($request->password, $user->password)) {
                $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
                $data = [
                    'access_token' => $token,
                ];

                // Send the notification
                $message = "Your have successfully Login";
                $user->notify(new UserActionNotification($message));

                return $this->sendResponse($data, "Login Successfully", 200);
            } else {
                return $this->sendError("Credentials doesn't match", "Login Failed", 401);
            }
        } catch (\Throwable $th) {
            Log::error("User login.\n Error => " . $th->getMessage());
            return $this->sendError($th->getMessage(), "Login Failed", 500);
        }
    }

    // only after auth token
    public function profile()
    {
        $data = auth()->user();
        // return $this->sendResponse($data, "User Profile Information", 200);
        $result = new UserResource($data);
        return $this->sendResponse($result, "User Profile");
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:100",
            "dob" => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'states' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|string|max:100',
            'icnumber' => 'nullable|string|max:100',
            // "email" => "required|email|max:100|unique:users,email," . $user->id,
            "number" => "required|digits:10",
            // "gender" => "required|in:male,female,others",
        ]);
        
        // return $this->sendResponse($request->all(), 'sldfjsd', 200);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation failed',
            ], 422);
        }
        try {
            $user->update([
                'name' => $request->name,
                'dob' => $request->dob,
                'address' => $request->address,
                'country' => $request->country,                
                // 'email' => $request->email,
                'state'=> $request->states,
                'city'=> $request->city,
                'postcode' => $request->postcode,
                'icnumber' => $request->icnumber,
                'contact' => $request->number,
                // 'gender' => $request->gender,
            ]);
            return $this->sendResponse($user, "User profile update Successfully", 201);
        } catch (Exception $e) {
            // Log the exception
            Log::error('Error updating user profile', [
                'user_id' => $user->id,
                'exception' => $e->getMessage(),
            ]);
            return $this->sendError(
                'An error occurred while updating the profile.',
                $e->getMessage(),
                500
            );
        }
    }

    public function logout()
    {
        try {
            $user = auth()->user();
            auth()->user()->tokens()->delete();

            // Send the notification
            $message = "Your have successfully Logout";
            $user->notify(new UserActionNotification($message));

            return $this->sendResponse([], "Logout Successfully", 200);
        } catch (\Throwable $th) {
            Log::error("User logout.\n Error => " . $th->getMessage());
            return $this->sendError($th->getMessage(), "Logout failed", 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "otp" => "required|numeric|min:000000|max:999999",
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        if (!$user) {
            return $this->sendError('Invalid email', 'User nor found', 404);
        }
        if ($user->otp !== (int)$request->otp) {
            return $this->sendError('InCorrect OTP', 'Invalid OTP.', 401);
        }
        if (now()->greaterThan($user->otp_expires_at)) {
            return $this->sendError('OTP Expired', 'OTP has expired', 401);
        }

        // Mark the email as verified
        $user->email_verified_at = now();
        $user->otp = null; // Clear the OTP
        $user->otp_expires_at = null;
        $user->save();

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        $data = [
            'access_token' => $token,
        ];

        return $this->sendResponse($data, 'Email successfully verified', 200);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => "required|email",
        ]);

        $user = User::where("email", $request->email)->firstOrFail();
        if (!$user) {
            return $this->sendError("Invalid email", "User not found", 404);
        }

        // Generate and send a new OTP
        $user->otp = $otp = rand(100_000, 999_999);
        $user->otp_expires_at = now()->addMinute(2);
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));
        return $this->sendResponse([], "A new OTP has been sent to your email.", 200);
    }

    public function changePassword(Request $request)
    {
        // Validate the inputs
        $validator = Validator::make($request->all(), [
            'oldpassword' => 'required',
            'newpassword' => 'required|min:4',
            'confirmpassword' => 'required|same:newpassword',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => 'Validation failed',
            ], 422);
        }

        // Check if the old password matches the current password
        if (!Hash::check($request->oldpassword, auth()->user()->password)) {
            return response()->json([
                'error' => 'Old password is incorrect',
            ], 401);
        }

        // Update the password
        $user = auth()->user();
        $user->password = $request->newpassword;
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully',
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors(),
                'message' => 'Validation failed',
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        try {

            if ($user) {
                $user->notify(new ForgotPassword($user->id));
                return response()->json([
                    'message' => 'An email was sent to your email address.'
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Email not found.'
                ], 404);
            }
        } catch (\Exception $e) {
            // Handle exception and return an appropriate response
            return response()->json([
                'error' => 'Something went wrong.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteAccount(Request $request)
{
    try {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Your account has been deleted successfully.'
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Something went wrong.',
            'message' => $e->getMessage(),
        ], 500);
    }
}
}
