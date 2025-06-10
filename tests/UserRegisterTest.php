<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\User;
use App\Models\User as UserModel;
use App\Utility\Hash;

class UserRegisterTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_COOKIE = [];
        UserModel::mockCreateUser(function() {}); // Réinitialise le mock
    }

    public function testRegisterSuccess()
    {
        $formData = [
            'username' => 'newuser',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password-check' => 'password123'
        ];

        // Mock de createUser()
        UserModel::mockCreateUser(function($data) use ($formData) {
            $this->assertEquals($formData['username'], $data['username']);
            $this->assertEquals($formData['email'], $data['email']);
            $this->assertNotEquals($formData['password'], $data['password']); // Vérifie le hash
            $this->assertArrayHasKey('salt', $data);
            $this->assertTrue(Hash::check($formData['password'], $data['salt'], $data['password']));
            return 1; // Simule un ID d'utilisateur créé
        });

        $controller = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflection = new \ReflectionClass(User::class);
        $method = $reflection->getMethod('register');
        $method->setAccessible(true);

        $result = $method->invoke($controller, $formData);

        $this->assertTrue($result);
        $this->assertEquals('newuser', $_SESSION['user']['username']); // Si connexion automatique
    }

    public function testRegisterFailPasswordMismatch()
    {
        $formData = [
            'username' => 'newuser',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password-check' => 'differentpassword'
        ];

        $controller = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflection = new \ReflectionClass(User::class);
        $method = $reflection->getMethod('register');
        $method->setAccessible(true);

        $result = $method->invoke($controller, $formData);

        $this->assertFalse($result);
        $this->assertStringContainsString('Les mots de passe ne correspondent pas', $_SESSION['flash']['danger']);
    }
}
