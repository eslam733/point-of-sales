<?php

namespace Tests\Unit;

use App\Http\Controllers\UserController;
use App\Models\User;
use Database\Seeders\SeederDefaultRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    /**
     * A basic unit test example.
     */
    public function test_get_users(): void
    {
        $res = $this->post('/api/category/create', [
            'name' => 'test category'
        ]);
        dd($res);
//        $response = $this->get('/api/category/show');
//
//        $response->assertJson([
//            'name' => 'test category'
//        ]);
    }
}
