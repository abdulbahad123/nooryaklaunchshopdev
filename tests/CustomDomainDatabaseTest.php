<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * Custom Domain Database Switching Test
 * 
 * This test verifies that the CustomDomainDatabaseMiddleware correctly
 * switches between maindb and agencydb based on the accessed domain.
 */
class CustomDomainDatabaseTest extends TestCase
{
    /**
     * Test main infrastructure hosts use maindb
     *
     * @return void
     */
    public function test_main_host_uses_maindb()
    {
        // Simulate request to main host
        $response = $this->get('/', ['HTTP_HOST' => 'cockroachjantaparty.top']);
        
        // Check that we're using the main database
        $currentDb = Config::get('database.connections.mysql.database');
        $mainDb = env('DB_DATABASE', 'nooryak_launchshopp');
        
        $this->assertEquals($mainDb, $currentDb, 'Main host should use maindb');
        $this->assertFalse(session('using_agency_db', false), 'Should not be flagged as using agency db');
    }
    
    /**
     * Test approved custom domain switches to agencydb
     * 
     * Note: This test requires a custom domain record with status=1 in the database
     *
     * @return void
     */
    public function test_approved_custom_domain_uses_agencydb()
    {
        // First, create a test custom domain record
        $customDomain = 'testdomain.com';
        
        // Insert test data (ensure this exists in your test database)
        DB::table('user_custom_domains')->insert([
            'user_id' => 1,
            'current_domain' => 'subdomain.example.com',
            'requested_domain' => $customDomain,
            'status' => 1, // Approved
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Simulate request to custom domain
        $response = $this->get('/', ['HTTP_HOST' => $customDomain]);
        
        // Check that we switched to agency database
        $currentDb = Config::get('database.connections.mysql.database');
        $agencyDb = env('AGENCY_DB_DATABASE', 'agencydb');
        
        $this->assertEquals($agencyDb, $currentDb, 'Custom domain should use agencydb');
        $this->assertTrue(session('using_agency_db', false), 'Should be flagged as using agency db');
        $this->assertTrue(session('custom_domain_active', false), 'Custom domain should be marked as active');
        
        // Clean up
        DB::table('user_custom_domains')->where('requested_domain', $customDomain)->delete();
    }
    
    /**
     * Test pending custom domain (status=0) does not switch database
     *
     * @return void
     */
    public function test_pending_custom_domain_uses_maindb()
    {
        // Create a pending custom domain record
        $customDomain = 'pendingdomain.com';
        
        DB::table('user_custom_domains')->insert([
            'user_id' => 1,
            'current_domain' => 'subdomain.example.com',
            'requested_domain' => $customDomain,
            'status' => 0, // Pending
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Simulate request to pending custom domain
        $response = $this->get('/', ['HTTP_HOST' => $customDomain]);
        
        // Should still use main database since not approved
        $currentDb = Config::get('database.connections.mysql.database');
        $mainDb = env('DB_DATABASE', 'nooryak_launchshopp');
        
        $this->assertEquals($mainDb, $currentDb, 'Pending domain should use maindb');
        $this->assertFalse(session('using_agency_db', false), 'Should not switch to agency db for pending domain');
        
        // Clean up
        DB::table('user_custom_domains')->where('requested_domain', $customDomain)->delete();
    }
    
    /**
     * Test rejected custom domain (status=2) does not switch database
     *
     * @return void
     */
    public function test_rejected_custom_domain_uses_maindb()
    {
        // Create a rejected custom domain record
        $customDomain = 'rejecteddomain.com';
        
        DB::table('user_custom_domains')->insert([
            'user_id' => 1,
            'current_domain' => 'subdomain.example.com',
            'requested_domain' => $customDomain,
            'status' => 2, // Rejected
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Simulate request to rejected custom domain
        $response = $this->get('/', ['HTTP_HOST' => $customDomain]);
        
        // Should use main database
        $currentDb = Config::get('database.connections.mysql.database');
        $mainDb = env('DB_DATABASE', 'nooryak_launchshopp');
        
        $this->assertEquals($mainDb, $currentDb, 'Rejected domain should use maindb');
        $this->assertFalse(session('using_agency_db', false), 'Should not switch to agency db for rejected domain');
        
        // Clean up
        DB::table('user_custom_domains')->where('requested_domain', $customDomain)->delete();
    }
    
    /**
     * Test helper function isUsingAgencyDb()
     *
     * @return void
     */
    public function test_helper_is_using_agency_db()
    {
        // Set session to simulate agency db usage
        session(['using_agency_db' => true]);
        
        $this->assertTrue(isUsingAgencyDb(), 'Helper should return true when using agency db');
        
        // Clear session
        session(['using_agency_db' => false]);
        
        $this->assertFalse(isUsingAgencyDb(), 'Helper should return false when not using agency db');
    }
    
    /**
     * Test helper function getCustomDomainInfo()
     *
     * @return void
     */
    public function test_helper_get_custom_domain_info()
    {
        // Set session to simulate active custom domain
        session([
            'custom_domain_active' => true,
            'custom_domain_user_id' => 123,
            'custom_domain_name' => 'testdomain.com',
            'using_agency_db' => true,
        ]);
        
        $info = getCustomDomainInfo();
        
        $this->assertNotNull($info, 'Should return info when custom domain is active');
        $this->assertEquals(123, $info['user_id'], 'User ID should match');
        $this->assertEquals('testdomain.com', $info['domain'], 'Domain should match');
        $this->assertTrue($info['using_agency_db'], 'Should be using agency db');
        
        // Clear session
        session(['custom_domain_active' => false]);
        
        $this->assertNull(getCustomDomainInfo(), 'Should return null when no custom domain is active');
    }
    
    /**
     * Test helper function getCurrentDatabaseName()
     *
     * @return void
     */
    public function test_helper_get_current_database_name()
    {
        $dbName = getCurrentDatabaseName();
        
        $this->assertNotEmpty($dbName, 'Database name should not be empty');
        $this->assertIsString($dbName, 'Database name should be a string');
    }
}
