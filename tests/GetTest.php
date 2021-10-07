<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Rest\Account;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return Authenticatable
     */
    // protected $account;

    // public function __construct() {
    //     $this->account = Account::factory(Account::class)->make();
    // }
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }

    public function testGetModels()
    {
        $account = Account::factory(Account::class)->make();
        $models = [
            'account', 
            'attendance',
            'customer', 
            'order',
            'order-service',
            'role', 
            'service',
            'service-type'
        ];

        foreach ($models as $model) {
            $response = $this->actingAs($account)->call('GET', "/api/$model");

            $this->assertEquals(200, $response->status());
        }
    }

    public function testServiceTypeGetAt()
    {
        $account = Account::factory(Account::class)->make();
        $response = $this->actingAs($account)->call('GET', '/api/service-type/1');

        $this->assertEquals(200, $response->status());
    }

    public function testServiceTypeGetAtColumn()
    {
        $account = Account::factory(Account::class)->make();
        $response = $this->actingAs($account)->call('GET', '/api/service-type/1/name');

        $this->assertEquals(200, $response->status());
    }

    public function testServiceTypeGetWhere()
    {
        $account = Account::factory(Account::class)->make();
        $response = $this->actingAs($account)->call('GET', '/api/service/w/type/1');
        $this->assertEquals(200, $response->status());

        $response = $this->actingAs($account)->call('GET', '/api/service?where=type-is-1');
        $this->assertEquals(200, $response->status());
    }

}
