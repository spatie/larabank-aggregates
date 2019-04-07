# Larabank built with an aggregate and projectors 📽

![Larabank accounts page](https://spatie.github.io/larabank-traditional/screenshot.png)

This is an example app used in the documentation of [laravel-event-projector](https://docs.spatie.be/laravel-event-projector).

These rules are implemented:
- a user cannot go below -5000 on an account
- when hitting the limit three times in a row a loan proposal mail must be sent

## Getting started

- Clone the repo
- copy `env.example` to `.env`
- set the `DB_` environment variables in `.env` to your liking
- create a database with the name specified in `DB_DATABASE`
- `composer install`
- `yarn`, `yarn run dev` (or the npm equivalents)
- migrate and seed the database with `php artisan migrate:fresh --seed`
- you can now loging in with user "user@larabank.com", password "secret"

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
