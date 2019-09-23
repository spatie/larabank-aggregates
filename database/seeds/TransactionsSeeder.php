<?php

use App\Account;
use App\Domain\Account\AccountAggregateRoot;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Telescope\Telescope;

class TransactionsSeeder extends Seeder
{
    use WithFaker;

    public function run()
    {
        $this->setUpFaker();

        Telescope::stopRecording();

        $user = User::first();
        /** @var Account $account */
        $account = Account::whereName('My account')->first();

        if (!$account) {
            $account = Account::create([
                'uuid' => $this->faker->uuid,
                'name' => 'My account',
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        }

        $aggregateRoot = AccountAggregateRoot::retrieve($account->uuid);

        $this->command->getOutput()->progressStart(10000);

        foreach (range(1, 10000) as $i) {
            $this->command->getOutput()->progressAdvance();
            $aggregateRoot->addMoney($this->faker->numberBetween(1000, 2000));
            $aggregateRoot->subtractMoney($this->faker->numberBetween(1, 1000));
        }

        $aggregateRoot->persist();

        $this->command->getOutput()->progressFinish();
    }
}
