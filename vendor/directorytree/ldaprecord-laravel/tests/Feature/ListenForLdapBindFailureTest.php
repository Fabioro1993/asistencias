<?php

namespace LdapRecord\Laravel\Tests\Feature;

use LdapRecord\Testing\LdapFake;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Testing\DirectoryFake;
use LdapRecord\Laravel\Tests\TestCase;
use LdapRecord\Models\ActiveDirectory\User;

class ListenForLdapBindFailureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetup($app)
    {
        parent::getEnvironmentSetup($app);

        $app['config']->set('ldap.connections.default', [
            'hosts' => ['one', 'two', 'three'],
            'username' => 'user',
            'password' => 'secret',
            'base_dn' => 'dc=local,dc=com',
        ]);
    }

    public function test_validation_exception_is_not_thrown_until_all_connection_hosts_are_attempted()
    {
        $this->setupPlainUserProvider(['model' => User::class]);

        $fake = DirectoryFake::setup('default')->shouldNotBeConnected();

        $expectedSelects = [
            "objectguid",
            "*",
        ];

        $expectedFilter = $fake->query()
            ->where([
                ['objectclass', '=', 'top'],
                ['objectclass', '=', 'person'],
                ['objectclass', '=', 'organizationalperson'],
                ['objectclass', '=', 'user'],
                ['mail', '=', 'jdoe@local.com'],
                ['objectclass', '!=', 'computer'],
            ])
            ->getQuery();

        $expectedQueryResult = [
            [
                'mail' => ['jdoe@local.com'],
                'dn' => ['cn=jdoe,dc=local,dc=com']
            ]
        ];

        $fake->getLdapConnection()->expect([
            // Two bind attempts fail on hosts "one" and "two" with configured user account.
            LdapFake::operation('bind')
                ->with('user', 'secret')
                ->twice()
                ->andReturn(false),

            // Third bind attempt passes.
            LdapFake::operation('bind')
                ->with('user', 'secret')
                ->once()
                ->andReturn(true),

            // Bind is attempted with the authenticating user and passes.
            LdapFake::operation('bind')
                ->with('cn=jdoe,dc=local,dc=com', 'secret')
                ->once()
                ->andReturn(true),

            // Rebind is attempted with configured user account.
            LdapFake::operation('bind')
                ->with('user', 'secret')
                ->once()
                ->andReturn(true),

            // Search operation is executed for authenticating user.
            LdapFake::operation('search')
                ->with(["dc=local,dc=com", $expectedFilter, $expectedSelects, false, 1])
                ->once()
                ->andReturn($expectedQueryResult)
        ])->shouldReturnError( "Can't contact LDAP server");

        $result = Auth::attempt([
            'mail' => 'jdoe@local.com',
            'password' => 'secret',
        ]);

        $this->assertTrue($result);
        $this->assertInstanceOf(User::class, Auth::user());
        $this->assertCount(2, $fake->attempted());
        $this->assertEquals(['one', 'two'], array_Keys($fake->attempted()));
    }
}