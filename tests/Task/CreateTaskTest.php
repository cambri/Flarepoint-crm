<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\PermissionRole;

class CreateClientTest extends TestCase
{
    use DatabaseTransactions;


    protected $role;

    public function setup()
    {
        parent::setup();
        App::setLocale('en');

        $this->createUser();
        $this->createRole();

    }

    public function testCanNotAccessCreatePageWithOutPermission()
    {
        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Tasks')
            ->dontSee('New Task')
            ->visit('/tasks/create')
            ->see('Not allowed to create task')
            ->seePageIs('/tasks');
    }

    public function testCanCreateClientWithPermission()
    {
        $this->createClient();
        $this->createTaskPermission();

        $this->visit('/')
            ->seePageIs('/login')
            ->type('bottelet@flarepoint.com', 'email')
            ->type('admin', 'password')
            ->press('Login')
            ->see('Tasks')
            ->click('New Task')
            ->seePageIs('/tasks/create')
            ->type($this->faker->title, 'title')
            ->type($this->faker->realText(30, 3), 'description')
            ->type($this->faker->date(), 'deadline')
            ->select(1, 'status')
            ->press('Create New Task')
            ->see('Task successfully added');
    }

}