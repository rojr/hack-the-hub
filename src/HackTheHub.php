<?php

namespace HackTheHub;


use HackTheHub\Custard\HackTheHubCustard;
use HackTheHub\Layouts\DefaultLayout;
use HackTheHub\Leaves\Index;
use HackTheHub\LoginProviders\HackTheHubLoginProvider;
use HackTheHub\Models\HackTheHubSolutionSchema;
use Rhubarb\Crown\Application;
use Rhubarb\Crown\Encryption\HashProvider;
use Rhubarb\Crown\Encryption\Sha512HashProvider;
use Rhubarb\Crown\Layout\LayoutModule;
use Rhubarb\Crown\UrlHandlers\ClassMappedUrlHandler;
use Rhubarb\Leaf\LeafModule;
use Rhubarb\Scaffolds\AuthenticationWithRoles\AuthenticationWithRolesModule;
use Rhubarb\Stem\Custard\SeedDemoDataCommand;
use Rhubarb\Stem\Repositories\MySql\MySql;
use Rhubarb\Stem\Repositories\Repository;
use Rhubarb\Stem\Schema\SolutionSchema;
use Rhubarb\Stem\StemModule;

class HackTheHub extends Application
{
    protected function initialise()
    {
        parent::initialise();

        if (file_exists(APPLICATION_ROOT_DIR . "/settings/site.config.php")) {
            include_once(APPLICATION_ROOT_DIR . "/settings/site.config.php");
        }

        $this->developerMode = true;

        Repository::setDefaultRepositoryClassName(MySql::class);

        SolutionSchema::registerSchema('HackTheHubSchema', HackTheHubSolutionSchema::class);

        HashProvider::setProviderClassName(Sha512HashProvider::class);
    }

    protected function registerUrlHandlers()
    {
        parent::registerUrlHandlers();

        $this->addUrlHandlers(
            [
                "/" => new ClassMappedUrlHandler(Index::class)
            ]
        );
    }

    protected function getModules()
    {
        return [
            new LayoutModule(DefaultLayout::class),
            new StemModule(),
            new LeafModule(),
            new AuthenticationWithRolesModule(HackTheHubLoginProvider::class, '/admin/'),
        ];
    }

    public function getCustardCommands()
    {
        SeedDemoDataCommand::registerDemoDataSeeder(new HackTheHubCustard(''));

        return parent::getCustardCommands();
    }
}