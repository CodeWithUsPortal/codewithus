<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TaskClass;
use Carbon\Carbon;
use App\Domain\TokyFunctions;
use App\Domain\MailFunctions;
class TaskClassReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskclass:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sends a reminder to parents ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(TokyFunctions $toky)
    {
        $now = Carbon::now();
        $taskClasses = TaskClass::where('is_deleted', false)->get();
        foreach($taskClasses as $taskClass){
            $dateOfTaskClass = $taskClass->starts_at;
            $diff = $now->diffInDays($dateOfTaskClass);
            if($diff == 1){
                $users = $taskClass->users()->get();
                foreach($users as $user){
                    $role = $user->role->role;
                    if($role == "student"){
                        $phoneNumber = $user->phone_number;
                        $classDate = date('Y-m-d', strtotime($taskClass->starts_at));
                        $classTime = date('h:i A', strtotime($taskClass->starts_at));
                        
                        $sms_message = "Hello! ".$user->full_name."'s coding class is tomorrow, ".$classDate." at ".$classTime." Pacific Time Zone.To start the class, please go to this link at ".$classTime.
                                       ": https://zoom.us/j/896523407. To reschedule to a different time, please text us back or call this number. See you soon!";
                        
                        $toky->sms_send($phoneNumber, $sms_message);
                    }
                }
            }      
        }
        
    }
}
