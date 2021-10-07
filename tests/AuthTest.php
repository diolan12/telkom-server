<?php

use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase{

    public function testLogin() {
        $this->post('/auth/login', [
            'nik' => '16982818',
            'password' => '12345678'
        ])->seeJson([
            'type' => 'SUCCESS'
        ]);

        // $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xOTIuMTY4LjEuNTo4MDgwIiwic3ViIjoiQXV0aG9yaXphdGlvbiIsImF1ZCI6IjE2OTgyODE4IiwiaWF0IjoiMjAyMS0xMC0wNlQxODoyNjozMy45MTE2ODFaIiwianRpIjoiMTY5ODI4MTgiLCJuYW1lIjoiRGlvIExhbnRpZWYgV2lkb3lva28iLCJnZW5kZXIiOiJtYWxlIiwicGljdHVyZSI6ImRlZmF1bHQuanBnIiwiZW1haWwiOiJkaW9fbGFudGllZjIxQG91dGxvb2suY29tIiwicGhvbmVfbnVtYmVyIjoiMDg1NjQ4NTM1OTI3IiwiYWRtaW4iOjB9.W3tUXEmnK20_HkZh1SV6EVRivd_VlIsWAiV4sBfCi-Aebv75oD0r1ltHU7eKo4blarufJ_NWEil6adDUmv-iA9t2dNwVm6WSK0Z1uxzOzImCL5W6CYoIfxyiczPzGze-mnYdR4nIwlD5Bt1xCxvCp04fvFpqv1Szu_FvGfKggjlX9cmiiUQw-flLfcOjU3Qxc5bPUecaBCRmwDXfbVJuysLuTwKR9xUJ0raGgNLTaTbOpOycs8dkRI2u_5--t_ZrSByUhH0pULtLwLToHOu2bfrzSsV6fc8TRXA5S-ql4wh_Xv9qlHHrkCF2ImTxbuhHnIygIQaDJQI0NDRasrWsuA';
        // $response = $this->withHeaders([
        //     'Authentication' => "Bearer $token"
        // ])->get('/auth/verify');
        // $this->assertEquals(200, $response->status());

        Artisan::call('migrate:fresh --seed');
    }

}
