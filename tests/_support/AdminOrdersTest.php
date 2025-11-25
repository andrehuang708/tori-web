<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class AdminOrdersTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $migrate = true;
    protected $seed = 'TestSeeder';

    public function testAdminOrdersRedirectsWhenNotLoggedIn()
    {
        $result = $this->get('/admin/orders');

        $result->assertStatus(302);
        $result->assertRedirectTo('/admin/login');
    }

    public function testAdminOrdersAccessibleWhenLoggedIn()
    {
        $session = session();
        $session->set('isAdminLoggedIn', true);

        $result = $this->withSession()->get('/admin/orders');

        $result->assertStatus(200);
        $result->assertSee('Orders');
    }
}
