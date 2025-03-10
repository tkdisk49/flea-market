<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password111'),
        ]);
    }

    public function testEmailIsEmptyOnLogin()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response = $this->post(route('login.store'), [
            'email' => '',
            'password' => 'password111',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function testPasswordIsEmptyOnLogin()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response = $this->post(route('login.store'), [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function testInvalidInputDisplaysValidationErrors()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response = $this->post(route('login.store'), [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません。']);
    }

    public function testLoginWithCorrectCredentials()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        $response = $this->post(route('login.store'), [
            'email' => 'test@example.com',
            'password' => 'password111',
        ]);

        $this->assertAuthenticatedAs($this->user);
    }
}
