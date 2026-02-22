<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $user = new User(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_returns_false_for_non_admin(): void
    {
        $user = new User(['role' => 'customer']);
        $this->assertFalse($user->isAdmin());
    }

    public function test_is_admin_returns_false_for_null_role(): void
    {
        $user = new User();
        $this->assertFalse($user->isAdmin());
    }

    public function test_fillable_attributes(): void
    {
        $user = new User();
        $fillable = $user->getFillable();
        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
        $this->assertContains('phone', $fillable);
        $this->assertContains('role', $fillable);
        $this->assertContains('addresses', $fillable);
    }

    public function test_hidden_attributes(): void
    {
        $user = new User();
        $hidden = $user->getHidden();
        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }

    public function test_casts_configuration(): void
    {
        $user = new User();
        $casts = $user->getCasts();
        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('array', $casts['addresses']);
    }
}
