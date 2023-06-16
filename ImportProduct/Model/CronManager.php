<?php

namespace Dev\ImportProduct\Model;

use Magento\Cron\Model\Config\Converter\Db;
use Magento\Cron\Model\ScheduleFactory;

class CronManager
{
    protected $converter;
    protected $scheduleFactory;

    public function __construct(
        Db $converter,
        ScheduleFactory $scheduleFactory
    ) {
        $this->converter = $converter;
        $this->scheduleFactory = $scheduleFactory;
    }

    public function setCron()
    {
        $instance = 'Dev\ImportProduct\Cron\CsvImport'; // Replace with the appropriate instance
        $method = 'execute'; // Replace with the appropriate method

        $configData = [

                'crontab' => 'default',
                'jobs' => [
                'your_custom_job_code' => [
                    'instance' => $instance,
                    'method' => $method,
                    'schedule' => '* * * * *' // Replace with the appropriate cron schedule
                    ]
            ]
        ];

        $convertedConfig = $this->converter->convert($configData);


        echo '<pre>'; 
        print_r($convertedConfig);
        exit;
        $cronJobs = $convertedConfig['default']['jobs'];

        foreach ($cronJobs as $jobCode => $jobData) {
            $schedule = $this->scheduleFactory->create();
            $schedule->setJobCode($jobCode);
            $schedule->setStatus(\Magento\Cron\Model\Schedule::STATUS_PENDING);
            $schedule->setCreatedAt(strftime('%Y-%m-%d %H:%M:%S', time()));
            $schedule->setScheduledAt(strftime('%Y-%m-%d %H:%M:%S', strtotime('2023-05-25 18:55:00')));
            $schedule->setData('instance', $jobData['instance']);
            $schedule->setData('method', $jobData['method']);
            // Set other required attributes

            // Save the cron job
            $schedule->save();
        }

        // Perform any additional logic or response handling
    }
}
