<?php

namespace Tests\Unit\Services;

use App\Repositories\Contracts\InstructorRepositoryInterface;
use App\Services\InstructorService;
use Mockery;
use Tests\TestCase;

class InstructorServiceTest extends TestCase
{
    private $instructorRepository;
    private InstructorService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instructorRepository = Mockery::mock(InstructorRepositoryInterface::class);
        $this->service = new InstructorService($this->instructorRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_gets_instructor_by_id(): void
    {
        $id = 1;
        $expected = ['id' => 1, 'name' => 'John Doe'];

        $this->instructorRepository
            ->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($expected);

        $result = $this->service->getInstructor($id);

        $this->assertEquals($expected, $result);
    }
}
