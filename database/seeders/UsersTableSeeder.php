<?php
namespace Database\Seeders;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {

        User::create( [
            'company_id'=>1,
            'role_id'=>1,
            'full_name'=>'Admin',
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=>'$2y$10$UDaMnlB/mNjcPFroiu9M9OjI/Urf.XgIEcdlO8QZ.8dQnEySb7NBm',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Shahin',
            'name'=>'Shahin',
            'email'=>'shahin@gmail.com',
            'password'=>'$2y$10$PQV36YsoAKvoGhDRZzFxy.aFErRdn/o.Y2SyjwdJf0vfGaFdvmYjW',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Rony',
            'name'=>'Rony',
            'email'=>'rony@gmail.com',
            'password'=>'$2y$10$AG9RAGHfEZNprgDy6PGqT.w//UxVz5YV4Lg.oaI8IQGpGsI1oQlAS',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Taj',
            'name'=>'Taj',
            'email'=>'taj@gmail.com',
            'password'=>'$2y$10$.nlCyNLJcU057qop0S6TPecC1TmuEWBUdA/xeLC.IALMuVZFLl1Ka',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'musa',
            'name'=>'musa',
            'email'=>'akmmusa@gmail.com',
            'password'=>'$2y$10$0slK3u4I5H4shTQg.iAzqO2RZMIuUJYySuHlwm6CskktIklbOaBoG',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Jahid',
            'name'=>'Jahid',
            'email'=>'jahid@gmail.com',
            'password'=>'$2y$10$KvHU3TcUHcrwsoHmdHtgpuLSGp8oG46H/.XyPvMVSYPrpGmq4Vqfu',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Shofi',
            'name'=>'Shofi',
            'email'=>'Shofi@gmail.com',
            'password'=>'$2y$10$vlFGYYyVM9t8z19YPIMlWOZpVS414TDlYbBiAIGe6jxmI0Z9lUFne',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Feroz ',
            'name'=>'Feroz ',
            'email'=>'feroz@gmail.com',
            'password'=>'$2y$10$fZgUyPcBi6OreEwHxT1ZBuI4V.k10.wfrL7bpkCAqBJpt3Wf8C..q',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Razzaque',
            'name'=>'Razzaque',
            'email'=>'razzaque@gmail.com',
            'password'=>'$2y$10$c6nBi/6qg7nQZJDiZgAGfO9MqODeGRDAnciWLcv29PH4On7UpAkWS',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Nurul',
            'name'=>'Nurul',
            'email'=>'gm@gmail.com',
            'password'=>'$2y$10$JpLdQsD8tiAZG6QW5ZmwmOV0YEYOM0is5X.BFilpz0TVsdgNtSDgm',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Kayes',
            'name'=>'Kayes',
            'email'=>'agmkayes@gmail.com',
            'password'=>'$2y$10$iuXLsCIYvpU3LtogU93XZe0QXeYzcToT2S3UXmjMMRMPa3hcd8kIe',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Masud',
            'name'=>'Masud',
            'email'=>'masud@gmail.com',
            'password'=>'$2y$10$aJT6vxCoXb/Ogufi3MmuJO8qeEYwRZ6ZCWqFkekSLiQVHWcUEjr3u',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Production',
            'name'=>'Production',
            'email'=>'pm@gmail.com',
            'password'=>'$2y$10$n0o8D4iG2Hx2/8aQDr1Y5eoG5i/qq1gE8lYmww6Ad6DpVVN.y0fDi',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Hasib',
            'name'=>'Hasib',
            'email'=>'hasib@gmail.com',
            'password'=>'$2y$10$ZuB2MGA176bElYpRJ0XUcOah85tPW.liYx5F35ZUGQj2mD/XPUY0y',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Mizan',
            'name'=>'Mizan',
            'email'=>'mizan.eleeng@gmail.com',
            'password'=>'$2y$10$SRXFM0PewtCSHHzxnHvUiucaBR6/3ZlBCkQWcaYEGEbFvdjYjEdle',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Atiqur Rahman',
            'name'=>'Atiqur Rahman',
            'email'=>'atiq@gmail.com',
            'password'=>'$2y$10$2CrlfWBbbypmCDFFE0l8POTS3r8j26VxnOj/GXxV.nxNtWvaQQ6AK',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Saidur Rahman',
            'name'=>'Saidur Rahman',
            'email'=>'saidur@gmail.com',
            'password'=>'$2y$10$grVlEB5Dmh5L4k6Ay9SXwu8GvmA5QRI.XPi/m0ahVfdyOhIHHP7Ii',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );



        User::create( [
            'company_id'=>1,
            'role_id'=>3,
            'full_name'=>'Awal ',
            'name'=>'Awal ',
            'email'=>'awal@gmail.com',
            'password'=>'$2y$10$N9J7evvKC5BO2ce0a5uWUuMm75/hucaKuYM0VvehoDTL80SV/vh/u',
            'password_expiry_days'=>30,
            'password_updated_at'=>'2020-11-01'
        ] );









//        DB::table('users')->insert([
//            'company_id'=>1,
//            'role_id'=>1,
//            'full_name' => 'SuperAdmin',
//            'name' => 'SuperAdmin',
//            'email' => 'superadmin@abcmail.com',
//            'password' => bcrypt('pass123'),
//            'pass_exp_date'=>'2020-11-24'
//        ]);
//
//        DB::table('users')->insert([
//            'company_id'=>1,
//            'role_id'=>2,
//            'full_name' => 'Matin Admin',
//            'name' => 'Matin',
//            'email' => 'matin@matincottonmills.com',
//            'password' => bcrypt('pass123'),
//            'pass_exp_date'=>'2020-11-24'
//        ]);
//
//        DB::table('users')->insert([
//            'company_id'=>2,
//            'role_id'=>3,
//            'full_name' => 'Atiqur Rahman Khadem',
//            'name' => 'Khadem',
//            'email' => 'khadem@matincottonmills.com',
//            'password' => bcrypt('pass123'),
//            'pass_exp_date'=>'2020-11-24'
//        ]);


//        DB::table('users')->insert([
//            'company_id'=>1,
//            'role_id'=>2,
//            'full_name' => 'Mumanu Admin',
//            'name' => 'Mumanu',
//            'email' => 'mumanu@mumanupolyester.com',
//            'password' => bcrypt('pass123'),
//            'pass_exp_date'=>'2020-11-24'
//        ]);

//        DB::table('users')->insert([
//            'company_id'=>1,
//            'role_id'=>3,
//            'full_name' => 'Sharif Muhammed Shahin',
//            'name' => 'Shahin',
//            'email' => 'shahin@mumanupolyester.com',
//            'password' => bcrypt('pass123'),
//            'pass_exp_date'=>'2020-11-24'
//        ]);
//        factory('App\User', 10)->create();
    }
}
