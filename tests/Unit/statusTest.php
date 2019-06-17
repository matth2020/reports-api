<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{
    protected $newData = [
    'name' => 'completeHobo',
    'position' => 12
    ];

    /**
     * Create status.
     * @return treatment_set_status_id
     */
    public function test_treatment_set_status_insert()
    {
        $url = $this->makeUrl('/v1/treatment_set/status');

        $data = [
        'name' => 'TSComplete',
        'position' => 1,
        'type' => 'treatment_set'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'status' => $data
            ]
        ]);

        $arr = $response->json();
        $status = $arr['data']['status'];
        $insertedId = $status['treatment_set_status_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update clinic.
     * @depends test_treatment_set_status_insert
     * @return $insertedId
     */
    public function test_treatment_set_status_update($insertedId)
    {
        $url = $this->makeUrl('/v1/treatment_set/status/{id}', $insertedId);

        $response = $this->putJsonTest($url, $this->newData);

        $response
         ->assertStatus(200)
         ->assertJson([
            'status' => 'success',
             'data' => [
                'status' => array_merge($this->newData, ['treatment_set_status_id' => $insertedId])
              ]
          ]);

        return $insertedId;
    }

    /**
     * Read status.
     * @depends test_treatment_set_status_update
     * @return void
     */
    public function test_treatment_set_status_read($insertedId)
    {
        $url = $this->makeUrl('/v1/treatment_set/status/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'status' => array_merge($this->newData, ['treatment_set_status_id' => $insertedId])
            ]
        ]);
    }

    /**
     * Read all statuses.
     * @depends test_treatment_set_status_insert
     * @return void
     */
    public function test_treatment_set_status_get_all()
    {
        $url = $this->makeUrl('/v1/treatment_set/status');

        $response = $this->getJsonTest($url);

        $response
          ->assertStatus(200)
          ->assertJsonFragment([
              'name' => 'completeHobo'
          ]);
    }

    /**
     * Search status.
     * @depends test_treatment_set_status_insert
     * @return void
     */
    public function test_treatment_set_status_search()
    {
        $url = $this->makeUrl('/v1/treatment_set/status/_search');

        $data = [
          'name' => 'completeHobo'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
          ->assertStatus(200)
          ->assertJson([
              'status' => 'success'
          ]);

        $arr = $response->json();

        $stat = $arr['data']['status'];
        $this->assertTrue(count($stat) > 0);
    }

    /**
     * Delete treatment_set_status.
     * @depends test_treatment_set_status_update
     * @return void
     */
    public function test_treatment_set_status_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/treatment_set/status/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
          ->assertStatus(200)
          ->assertJson([
              'status' => 'success',
              'data' => [
                  'status' => array_merge($this->newData, ['treatment_set_status_id' => $insertedId/*, 'deleted' => 'T'*/])
              ]
          ]);

        // verify that it no longer shows up in searches

        $url = $this->makeUrl('/v1/treatment_set/status');

        $response = $this->getJsonTest($url);

        $response
          ->assertStatus(200)
          ->assertJsonMissing([
              'name' => 'completeHobo'
          ]);

        $url = $this->makeUrl('/v1/treatment_set/status/_search');

        $data = [
          'name' => 'completeHobo'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
          ->assertStatus(200)
          ->assertJsonMissing([
              'name' => 'completeHobo'
          ]);

        // verify that we can still get it by asking directly for it

        $url = $this->makeUrl('/v1/treatment_set/status/{id}', $insertedId);

        $response = $this->getJsonTest($url, 'fail');

        $response
          ->assertStatus(404);
        // ->assertJson([
          //     'status' => 'success',
          //     'data' => [
          //         'status' => [
          //             'name' => 'completeHobo',
          //             'deleted' => 'T'
          //         ]
          //     ]
          // ]);
    }
}

class StatusTest2 extends TestCase
{
    protected $newData = [
    'name' => 'POComplete',
    'position' => 4,
    'type' => 'purchase_order'
    ];

    /**
     * Create status.
     * @return purchase_order_status_id
     */
    public function test_purchase_order_status_insert()
    {
        $url = $this->makeUrl('/v1/purchase_order/status');

        $data = [
        'name' => 'In Queue',
        'position' => 1,
        'type' => 'purchase_order'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'status' => $data
            ]
        ]);

        $arr = $response->json();
        $status = $arr['data']['status'];
        $insertedId = $status['purchase_order_status_id'];

        $this->assertTrue($insertedId !== 0);
        return $insertedId;
    }

    /**
     * Update purchase_order_status.
     * @depends test_purchase_order_status_insert
     * @return $insertedId
     */
    public function test_purchase_order_status_update($insertedId)
    {
        $url = $this->makeUrl('/v1/purchase_order/status/{id}', $insertedId);

        $response = $this->putJsonTest($url, $this->newData);

        $response
         ->assertStatus(200)
         ->assertJson([
            'status' => 'success',
             'data' => [
                'status' => array_merge($this->newData, ['purchase_order_status_id' => $insertedId])
              ]
          ]);

        return $insertedId;
    }

    /**
     * Read status.
     * @depends test_purchase_order_status_update
     * @return void
     */
    public function test_purchase_order_status_read($insertedId)
    {
        $url = $this->makeUrl('/v1/purchase_order/status/{id}', $insertedId);

        $response = $this->getJsonTest($url);

        $response
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'data' => [
                'status' => array_merge($this->newData, ['purchase_order_status_id' => $insertedId])
            ]
        ]);
    }

    /**
     * Read all purchase_order_statuses.
     * @depends test_purchase_order_status_insert
     * @return void
     */
    public function test_purchase_order_status_get_all()
    {
        $url = $this->makeUrl('/v1/purchase_order/status');

        $response = $this->getJsonTest($url);

        $response
          ->assertStatus(200)
          ->assertJsonFragment([
              'name' => 'POComplete'
          ]);
    }

    /**
     * Search status.
     * @depends test_purchase_order_status_insert
     * @return void
     */
    public function test_purchase_order_status_search()
    {
        $url = $this->makeUrl('/v1/purchase_order/status/_search');

        $data = [
          'name' => 'POComplete'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
          ->assertStatus(200)
          ->assertJson([
              'status' => 'success'
          ]);

        $arr = $response->json();

        $stat = $arr['data']['status'];
        $this->assertTrue(count($stat) > 0);
    }

    /**
     * Delete treatment_set_status.
     * @depends test_purchase_order_status_update
     * @return void
     */
    public function test_purchase_order_status_delete($insertedId)
    {
        $url = $this->makeUrl('/v1/purchase_order/status/{id}', $insertedId);

        $response = $this->deleteJsonTest($url, []);

        $response
          ->assertStatus(200)
          ->assertJson([
              'status' => 'success',
              'data' => [
                  'status' => array_merge($this->newData, ['purchase_order_status_id' => $insertedId/*, 'deleted' => 'T'*/])
              ]
          ]);

        // verify that it no longer shows up in searches

        $url = $this->makeUrl('/v1/purchase_order/status');

        $response = $this->getJsonTest($url);

        $response
          ->assertStatus(200)
          ->assertJsonMissing([
              'name' => 'POComplete'
          ]);

        $url = $this->makeUrl('/v1/purchase_order/status/_search');

        $data = [
          'name' => 'POComplete'
        ];

        $response = $this->postJsonTest($url, $data);

        $response
          ->assertStatus(200)
          ->assertJsonMissing([
              'name' => 'POComplete'
          ]);

        // verify that we can still get it by asking directly for it

        $url = $this->makeUrl('/v1/purchase_order/status/{id}', $insertedId);

        $response = $this->getJsonTest($url, 'fail');

        $response
          ->assertStatus(404);
        // ->assertJson([
          //     'status' => 'success',
          //     'data' => [
          //         'status' => [
          //             'name' => 'Complete',
          //             'deleted' => 'T'
          //         ]
          //     ]
          // ]);
    }
}
