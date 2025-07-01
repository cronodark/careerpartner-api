<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Organization;
use App\Models\Talent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = User::where('email', $request->identifier)
            ->orWhere('username', $request->identifier)
            ->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid credentials'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = $user->createToken('user_login')->plainTextToken;
        if ($user->photo != null) {
            $user->photo = url($user->photo);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Success',
            'data' => [
                'token' => $token,
                'user' => $user,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Logged out successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Logout failed: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:users|max:15',
            'username' => 'required|string|unique:users|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'role' => 'required|in:company,organization,talent',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:M,F',


            'company_name' => 'required_if:role,company|string|max:255',
            'company_website' => 'nullable|url',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'industry' => 'required_if:role,company|string|max:255',
            'headquarters_address' => 'nullable|string|max:255',
            'company_contact_email' => 'nullable|email|max:255',
            'company_contact_phone' => 'nullable|string|max:15',

            'organization_name' => 'required_if:role,organization|string|max:255',
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'organization_description' => 'required_if:role,organization|string|max:1000',
            'organization_contact_email' => 'nullable|email|max:255',
            'organization_contact_phone' => 'nullable|string|max:15',
            'status' => 'required_if:role,organization|in:active,inactive,pending',

            'current_education' => 'required_if:role,talent|string|max:255',
            'major' => 'required_if:role,talent|string|max:255',
            'interests' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'role' => $request->role,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
        ]);

        if ($request->file('profile_picture')) {
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->save();
        }

        switch ($user->role) {
            case 'company':
                $company = Company::create([
                    'user_id' => $user->id,
                    'name' => $request->input('company_name'),
                    'industry' => $request->input('industry'),
                    'headquarters_address' => $request->input('headquarters_address'),
                    'contact_email' => $request->input('company_contact_email'),
                    'contact_phone' => $request->input('company_contact_phone'),
                    'website' => $request->input('company_website'),
                ]);

                if ($request->file('company_logo')) {
                    $company->logo = $request->file('company_logo')->store('company_logos', 'public');
                    $company->save();
                }

                break;
            case 'organization':
                $organization = Organization::create([
                    'user_id' => $user->id,
                    'name' => $request->input('organization_name'),
                    'description' => $request->input('organization_description'),
                    'contact_email' => $request->input('organization_contact_email'),
                    'contact_phone' => $request->input('organization_contact_phone'),
                    'status' => $request->input('status'),
                ]);
                if ($request->file('organization_logo')) {
                    $organization->logo = $request->file('organization_logo')->store('organization_logos', 'public');
                    $organization->save();
                }
                break;
            case 'talent':
                $talent = Talent::create([
                    'user_id' => $user->id,
                    'current_education' => $request->input('current_education'),
                    'major' => $request->input('major'),
                    'interests' => $request->input('interests'),
                ]);
                break;
            default:
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Invalid role'
                ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'status' => Response::HTTP_CREATED,
            'message' => 'User registered successfully',
            'data' => $user
        ], Response::HTTP_CREATED);
    }
}
