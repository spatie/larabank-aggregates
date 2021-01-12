@component('mail::message')
# Loan proposal

It seems like you are having financial troubles. Contact us for a loan.

@component('mail::button', ['url' => 'https://spatie.be'])
    View proposal
@endcomponent

Thanks,<br>
Your bank
@endcomponent

