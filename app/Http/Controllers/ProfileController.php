<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nickname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'country_id' => ['nullable', 'exists:countries,id'],
        ]);
        // Se password foi enviado, adiciona validação dinâmica
        $validator->sometimes('password', [
            'confirmed',
            Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
        ], function ($input) {
            return !empty($input->password);
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('activeTab', 'profile');
        }

        $user = auth()->user();
        $user->nickname = $request->input('nickname');
        $user->email = $request->input('email');
        $user->country_id = $request->input('country_id');

        if (!empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return back()
            ->withInput()
            ->with('activeTab', 'profile')
            ->with('success', __('Profile updated successfully.'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
