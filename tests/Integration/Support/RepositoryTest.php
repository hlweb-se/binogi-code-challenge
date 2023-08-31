<?php

namespace Tests\Integration\Support;

use App\Models\User\User;
use App\Repositories\UserRepository;
use Tests\FrameworkTest;

class RepositoryTest extends FrameworkTest
{
    /** @var UserRepository */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepository::class);
    }

    public function testAll()
    {
        $this->assertEquals(User::get(), $this->repository->all());
    }

    public function testCreate()
    {
        $user = $this->userFactory->create();
        $this->assertInstanceOf(User::class, $user);

        // Make sure the new user has a nickname (not empty)
        $this->assertNotEquals('', $user->refresh()->nickname);
        $this->assertNotNull($user->refresh()->nickname);

        // Make sure we can't create a user with a nickname that is too short
        $response = $this->json('POST', '/api/users', [
            "nickname" => "",
            "name" => "Darth Maul",
            "email" => "maul@redface.com",
            "password" => "May The Force Be With You"
        ]);
        $response->assertStatus(422);

        // Make sure we can't create a new user with a nickname that is too long
        $response = $this->json('POST', '/api/users', [
            "nickname" => "long-live-the-empire-and-all-of-our-stormtroopers-despite-their-poor-aim",
            "name" => "Darth Maul",
            "email" => "maul@redface.com",
            "password" => "May The Force Be With You"
        ]);
        $response->assertStatus(422);
    }

    public function testUpdate()
    {
        $user = $this->userFactory->create();
        $this->assertInstanceOf(User::class, $user);

        $this->repository->update(['name' => 'Luke Skywalker'], $user->id);
        $this->assertEquals('Luke Skywalker', $user->refresh()->name);

        // Make sure that we can update a valid nickname
        $this->repository->update(['nickname' => 'jedi-master'], $user->id);
        $this->assertNotEquals('sith', $user->refresh()->nickname);
        $this->assertEquals('jedi-master', $user->refresh()->nickname);

        // Make sure we can't update a nickname that is too short
        $response = $this->json('PUT', '/api/users/' .$user->id, [
            'nickname' => ''
        ]);
        $response->assertStatus(422);
        $this->assertNotEquals('', $user->refresh()->nickname);

        // Make sure we can't update a nickname that is too long
        $response = $this->json('PUT', '/api/users/' .$user->id, [
            'nickname' => 'drunk-jedi-with-a-name-that-is-too-long-to-even-remember-or-care-about'
        ]);
        $response->assertStatus(422);
        $this->assertNotEquals('drunk-jedi-with-a-name-that-is-too-long-to-even-remember-or-care-about', $user->refresh()->nickname);
    }

    public function testDelete()
    {
        $user = $this->userFactory->create();
        $this->repository->delete($user->id);
        $this->assertFalse(User::where('id', $user->id)->exists());
    }

    public function testFind()
    {
        $user  = $this->userFactory->create();
        $found = $this->repository->find($user->id);
        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($user->id, $found->id);
        $this->assertNotEquals($user->nickname, '');
        $this->assertNotNull($user->nickname);
    }
}
