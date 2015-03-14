<?php
use \App\User;
class UserTest extends TestCase {

	public function testCreateUser()
	{
		$user = new User();
		$user->email = 'baalmok@gmail.com';
		$user->password = Hash::make('a');
		$this->assertTrue($user->save());
	}

	public function testUserDetail()
	{
		$user = User::find(1);
		$this->assertEquals('baalmok@gmail.com', $user->email);
		\DB::table('user')->truncate();
	}

}
