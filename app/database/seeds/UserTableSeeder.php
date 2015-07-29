<?php

class UserTableSeeder extends Seeder{

	public function run(){
		
		User::create(array(
            'group_id'=>1,
			'name'=>'Разработчик',
			'surname'=>'',
			'email'=>'developer@zemaktiv.ru',
			'active'=>1,
			'password'=>Hash::make('grapheme1234'),
			'photo'=>'',
			'thumbnail'=>'',
			'temporary_code'=>'',
			'code_life'=>0,
			'remember_token' => 'Ycr4p62EPv3x3UWabeo3NpiSdJmI7hT3E460C5eTuiFKp1Vbjg6WL2M2bmPv',
		));
        DB::table('sessions')->insert(array(
            array(
                'id' => '80b1264be73168dfa026345d79abc86a1d29660c',
                'payload' => 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQXVsQ0M1Y0dhQTJMV2lKWVF4VTVlUkRtZ0s0NE5wdFRHeXh2eEdjMSI7czo2OiJsb2NhbGUiO3M6MjoicnUiO3M6MjI6IlBIUERFQlVHQkFSX1NUQUNLX0RBVEEiO2E6MDp7fXM6NToiZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozODoibG9naW5fODJlNWQyYzU2YmRkMDgxMTMxOGYwY2YwNzhiNzhiZmMiO2k6MTtzOjk6Il9zZjJfbWV0YSI7YTozOntzOjE6InUiO2k6MTQxNjM5OTU3MztzOjE6ImMiO2k6MTQxNjM5Mzg3ODtzOjE6ImwiO3M6MToiMCI7fX0=',
                'last_activity' => time(),
            )
        ));

		User::create(array(
            'group_id'=>2,
			'name'=>'Администратор',
			'surname'=>'',
			'email'=>'admin@zemaktiv.ru',
			'active'=>1,
			'password'=>Hash::make('grapheme1234'),
			'photo'=>'',
			'thumbnail'=>'',
			'temporary_code'=>'',
			'code_life'=>0,
		));
	}
}