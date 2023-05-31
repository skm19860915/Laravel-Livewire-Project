<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    User::truncate();
    $now = Carbon::now();
    DB::table('users')
      ->insert([
        [
          "name" => "Developer Testing",
          "username" => "dev",
          "email" => "dev@dev.com",
          "password" => Hash::make('@1Pryapus7'),
          "email_verified_at" => null,
          "created_at" => $now,
          "updated_at" => $now,
          "last_ip" => null,
          "first_name" => "Developer",
          "last_name" => "Testing",
          "role_id" => 1,
        ],
        [
          "name" => null,
          "username" => "jdoe",
          "email" => "jdoe@gmail.com",
          "password" => '$2y$12$i..o7Efh0W5NXjQsTYg5J.Q5dHguVKZrsx.TcGyDlxqi8JI.JtBoe',
          "email_verified_at" => null,
          "created_at" => $now,
          "updated_at" => $now,
          "last_ip" => null,
          "first_name" => "John",
          "last_name" => "Doe",
          "role_id" => 2,
        ],
        [
          "name" => null,
          "username" => "ckent",
          "email" => "ckent@gmail.com",
          "password" => '$2y$12$i..o7Efh0W5NXjQsTYg5J.Q5dHguVKZrsx.TcGyDlxqi8JI.JtBoe',
          "email_verified_at" => null,
          "created_at" => $now,
          "updated_at" => $now,
          "last_ip" => null,
          "first_name" => "Clark",
          "last_name" => "Kent",
          "role_id" => 4,
        ],
        [
          "name" => null,
          "username" => "bwayne",
          "email" => "bwayneg@gmail.com",
          "password" => '$2y$12$i..o7Efh0W5NXjQsTYg5J.Q5dHguVKZrsx.TcGyDlxqi8JI.JtBoe',
          "email_verified_at" => null,
          "created_at" => $now,
          "updated_at" => $now,
          "last_ip" => null,
          "first_name" => "Bruce",
          "last_name" => "Wayne",
          "role_id" => 4,
        ],
      ]);
  }
}
