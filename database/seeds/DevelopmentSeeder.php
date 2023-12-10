<?php

use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->resetSubDomains();
        $this->resetPasswords();
    }

    private function resetSubDomains()
    {
        $owners = \App\Models\Owners::all();
        $count  = 1;
        $domain = preg_replace('(^https?://)', '', config('app.url'));
        foreach ($owners as $owner) {
            $new_domain       = 'app-' . $count . '.' . $domain;
            $owner->subdomain = $new_domain;
            $owner->save();
            ++$count;
        }
    }

    private function resetPasswords()
    {
        $users = \App\User::all();
        foreach ($users as $user) {
            $user->password = bcrypt('secret');
            $user->save();
        }
    }
}
