<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testNameIsEmptyOnRegistration()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);

        $response = $this->post(route('register.store'), [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password111',
            'password_confirmation' => 'password111',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    public function testEmailIsEmptyOnRegistration()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);

        $response = $this->post(route('register.store'), [
            'name' => 'test',
            'email' => '',
            'password' => 'password111',
            'password_confirmation' => 'password111',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function testPasswordIsEmptyOnRegistration()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);

        $response = $this->post(route('register.store'), [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password111',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function testPasswordIsTooShort()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);

        $response = $this->post(route('register.store'), [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function testPasswordConfirmationFailsWhenNotMatching()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);

        $response = $this->post(route('register.store'), [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password112',
            'password_confirmation' => 'password111',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません']);
    }

    public function testSuccessfulRegistrationRedirectsToEmailVerification()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);

        $response = $this->post(route('register.store'), [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'password111',
            'password_confirmation' => 'password111',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'name' => 'test',
            'email' => 'test@example.com',
        ]);
    }
}
