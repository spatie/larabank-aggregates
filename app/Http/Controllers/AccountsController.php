<?php

namespace App\Http\Controllers;

use App\Account;
use App\Domain\Account\AccountAggregateRoot;
use App\Http\Requests\UpdateAccountRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccountsController extends Controller
{
    public function index()
    {
        $accounts = Account::where('user_id', Auth::user()->id)->get();

        return view('accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $newUuid = Str::uuid();

        $this
            ->getAccountAggregateRoot($newUuid)
            ->createAccount($request->name, auth()->user()->id)
            ->persist();

        return back();
    }

    public function update(Account $account, UpdateAccountRequest $request)
    {
        $aggregateRoot = $this->getAccountAggregateRoot($account->uuid);

        $request->adding()
            ? $aggregateRoot->addMoney($request->amount)
            : $aggregateRoot->subtractMoney($request->amount);

        $aggregateRoot->persist();

        return back();
    }

    public function destroy(Account $account)
    {
        $this
            ->getAccountAggregateRoot($account->uuid)
            ->deleteAccount()
            ->persist();

        return back();
    }

    protected function getAccountAggregateRoot(string $uuid): AccountAggregateRoot
    {
        return AccountAggregateRoot::retrieve($uuid);
    }
}
