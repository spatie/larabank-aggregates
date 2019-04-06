@extends('layouts.app')

@section('content')
    @if($accounts->count())
        <ul>
            @foreach($accounts as $account)
                <li class="mb-2 leading-none flex items-stretch">
                    <div class="flex-1 flex flex-col justify-center p-4 rounded-sm bg-grey-darkest border-grey-darker mr-2">
                        <h2 class="text-grey-light text-sm uppercase font-bold mb-1">
                            {{ strtoupper($account->name) }}
                        </h2>
                        <strong class="text-3xl {{ $account->balance >= 0 ? 'text-green' : 'text-red' }}">
                            € {{ $account->balance }}
                        </strong>
                    </div>
                    <div class="w-1/3">
                        <form action="{{ route('accounts.update', $account->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="flex flex-wrap">
                                <input
                                    type="number"
                                    value=""
                                    name="amount"
                                    placeholder="Amount"
                                    class="rounded-sm px-2 h-10 w-full mb-2"
                                    autocomplete="off"
                                >
                                <button
                                    type="submit"
                                    name="addMoney"
                                    class="px-3 h-10 rounded-sm bg-green-gradient text-white font-medium flex-1 mr-1"
                                >
                                    Deposit
                                </button>
                                <button
                                    type="submit"
                                    name="subtractMoney"
                                    class="px-3 h-10 rounded-sm bg-red-gradient text-white font-medium flex-1 ml-1"
                                >
                                    Withdraw
                                </button>
                            </div>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="py-8 mb-2 flex items-center justify-center rounded-sm bg-grey-darkest border-grey-darker text-grey-light">
            Nothing here yet!
        </div>
    @endif
    @include('accounts.partials.create-form')
@endsection
