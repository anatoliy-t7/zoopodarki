<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{

    public function profile()
    {
        /**
         * @get('/account/profile')
         * @name('account.profile')
         * @middlewares(web, auth)
         */
        $userId = auth()->user()->id;
        $user = User::where('id', $userId)->firstOrFail();

        return view('site.account.profile', compact('user'));
    }

    public function profileUpdate(Request $request, $id)
    {
        /**
         * @patch('/account/profile/{id}')
         * @name('account.user.update')
         * @middlewares(web, auth)
         */
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'unique:users,email,'.auth()->user()->id],
            'phone' => ['nullable', 'digits:10', 'unique:users,phone'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user = auth()->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        if ($request->has('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('account.profile', compact('user'))
            ->with('message', 'Данные обновлены.');
    }

}
