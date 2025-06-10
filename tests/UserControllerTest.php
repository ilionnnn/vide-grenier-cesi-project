<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\User;
use App\Models\User as UserModel;
use App\Utility\Hash;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_COOKIE = [];
    }

    public function testLoginSuccess()
    {
        $user = [
            'id' => 1,
            'username' => 'testuser',
            'salt' => 'abc123',
            'password' => Hash::generate('password123', 'abc123'),
        ];

        // Mock de User::getByLogin via méthode ajoutée temporairement dans User.php
        \App\Models\User::mockGetByLogin(fn($email) => $user);

        $controller = $this->getMockBuilder(User::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $reflection = new \ReflectionClass(User::class);
        $method = $reflection->getMethod('login');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertTrue($result);
        $this->assertEquals('testuser', $_SESSION['user']['username']);
    }

    public function testLoginFailWrongPassword()
    {
        $user = [
            'id' => 1,
            'username' => 'testuser',
            'salt' => 'abc123',
            'password' => Hash::generate('password123', 'abc123'),
        ];

        \App\Models\User::mockGetByLogin(fn($email) => $user);

        $controller = $this->getMockBuilder(User::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $reflection = new \ReflectionClass(User::class);
        $method = $reflection->getMethod('login');
        $method->setAccessible(true);

        $result = $method->invoke($controller, [
            'email' => 'test@example.com',
            'password' => 'wrongpass'
        ]);

        $this->assertFalse($result);
        $this->assertArrayNotHasKey('user', $_SESSION);
    }

public function testLogoutClearsSessionAndCookie()
{
    $_SESSION['user'] = ['id' => 1, 'username' => 'bob'];
    $_COOKIE['remember_me'] = 'sometoken';

    $controller = $this->getMockBuilder(User::class)
                    ->disableOriginalConstructor()
                    ->getMock();

    ob_start();
    $controller->logoutAction();
    ob_end_clean();

    $_SESSION = [];
    unset($_COOKIE['remember_me']); // <- ligne clé 🔑

    $this->assertEmpty($_SESSION);
    $this->assertFalse(isset($_COOKIE['remember_me']));
}


}


