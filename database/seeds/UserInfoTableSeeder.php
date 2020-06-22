<?php

use App\Models\UserInfo;
use Illuminate\Database\Seeder;

class UserInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 8;
        factory(UserInfo::class, $count)->create();
    }
}
