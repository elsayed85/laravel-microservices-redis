<?php

namespace Elsayed85\LmsRedis\Console\Commands\Service;

use Elsayed85\LmsRedis\Console\Command;
use Elsayed85\LmsRedis\Utils\Enum;

class CreateServiceCommand extends Command
{
    protected string $command = 'make:service name';

    protected string $description = 'Create exception class';

    protected string $stub = 'exception';

    protected function handle($input, $output): int
    {
        $name = $input->getArgument('name');

        if (preg_match('/^\d/', $name)) {
            $output->writeln('<error>Service name must not start with number!</error>');

            return 1;
        }

        if (preg_match('/[^A-Za-z0-9]/', $name)) {
            $output->writeln('<error>Service name must not contain special characters!</error>');

            return 1;
        }

        if (str_ends_with($name, 'Service')) {
            $name = str_replace('Service', '', $name);
        }

        $name = ucfirst($name);
        $nameLower = strtolower($name);
        $serviceName = $this->getServiceName($name);

        $baseDir = __DIR__.'/../../../Services';
        $dstDir = $baseDir.'/'.$serviceName;

        $this->copyDirectory($baseDir.'/BaseService', $dstDir);

        $this->renameFiles($dstDir.'/DTO', 'ServiceData', $name.'Data'); // Dto
        $this->renameFiles($dstDir.'/Enum', 'ServiceEvent', $name.'Event'); // Enum

        $this->renameFiles($dstDir.'/Event', 'ServiceCreatedEvent', $name.'CreatedEvent'); // Event
        $this->renameFiles($dstDir.'/Event', 'ServiceUpdatedEvent', $name.'UpdatedEvent'); // Event
        $this->renameFiles($dstDir.'/Event', 'ServiceDeletedEvent', $name.'DeletedEvent'); // Event
        $this->renameFiles($dstDir, 'RedisService', $name.'RedisService'); // Event

        $this->replaceInFiles($dstDir, '{ServiceName}', $name);
        $this->replaceInFiles($dstDir, '{ServiceFullName}', $serviceName);
        $this->replaceInFiles($dstDir, '{ServiceNameLower}', $nameLower);

        $output->writeln('<info>Service created successfully!</info>');

        return 0;
    }

    private function copyDirectory($src, $dst)
    {
        // 0777 is the default mode for directories. Leading zero specifies octal in PHP.
        mkdir($dst, 0777, true);

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                mkdir($dst.'/'.$iterator->getSubPathName());
            } else {
                copy($item, $dst.'/'.$iterator->getSubPathName());
            }
        }
    }

    private function replaceInFiles($dir, $search, $replace)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $contents = file_get_contents($file->getRealPath());
                $contents = str_replace($search, $replace, $contents);
                file_put_contents($file->getRealPath(), $contents);
            }
        }
    }

    private function renameFiles($dir, $search, $replace)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && strpos($file->getFilename(), $search) !== false) {
                $newName = str_replace($search, $replace, $file->getFilename());
                $newName = str_replace('.stub', '.php', $newName);
                rename($file->getRealPath(), $file->getPath().'/'.$newName);
            }
        }
    }

    private function getServiceName($name): string
    {
        if (! str_ends_with($name, 'Service')) {
            return $name.'Service';
        }

        return $name;
    }
}
