@extends('layouts.app')

@section('content')
    <h1 class="text-xl leading-none tracking-wide text-grey-darker mb-2">
        Transaction counts
    </h1>
    <div class="bg-grey-darkest rounded-sm p-4 text-grey-light">
    <table class="w-full">
        <thead>
        <tr>
            <th class="text-left text-grey-light text-sm uppercase font-bold p-2 bg-grey-darker rounded-l-sm">E-mail</td>
            <th class="text-right text-grey-light text-sm uppercase font-bold p-2 bg-grey-darker rounded-r-sm">Count</td>
        </tr>
        </thead>
        <tbody>
        @foreach($transactionCounts as $transactionCount)
            <tr>
                <td class="p-2 border-b border-grey-darker">{{ $transactionCount->user->email }}</td>
                <td class="p-2 border-b border-grey-darker text-right">{{ $transactionCount->count }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
@endsection
