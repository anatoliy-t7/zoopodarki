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
            'email' => ['required', 'unique:users,email,' . auth()->user()->id],
            'phone' => ['nullable', 'digits:10', 'unique:users,phone,' . auth()->user()->id],
        ]);

        $user = auth()->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        if ($request->has('password') && $request->password !== null) {
            $request->validate([
                'password' => ['required', 'string', 'min:8'],
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        toast()
            ->success('Данные обновлены.')
            ->pushOnNextPage();

        return redirect()->route('account.profile', compact('user'));
    }
}
