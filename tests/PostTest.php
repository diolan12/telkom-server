<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Rest\Account;

class PostTest extends TestCase
{
    // use DatabaseMigrations;
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function testNewServiceType() {
        
        $account = Account::factory(Account::class)->make();

        $name = $this->generateRandomString();
        $this->actingAs($account)->post('/api/service-type', [
            'name' => $name
        ])->seeJson([
            'name' => $name
        ]);
    }
    public function testUpdateServiceType() {
        $account = Account::factory(Account::class)->make();
        $name = $this->generateRandomString();
        $this->actingAs($account)->put('/api/service-type/3', [
            'name' => $name
        ])->seeJson([
            'name' => $name
        ]);
    }
    public function testDeleteServiceType() {
        $account = Account::factory(Account::class)->make();
        $response = $this->actingAs($account)->call('DELETE', '/api/service-type/3');
        $this->assertEquals(200, $response->status());
    }
}