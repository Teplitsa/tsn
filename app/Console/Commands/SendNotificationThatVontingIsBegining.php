<?php

namespace App\Console\Commands;

use App\Notifications\BeginExtramuralVoting;
use App\Notifications\BeginIntramuralVoting;
use App\Voting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotificationThatVontingIsBegining extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'begin:voting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {

       Voting::whereBetween('opened_at', array(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()))->each(function ($item){
           $item->house->connectedFlats->each(
               function ($flat) use ($item) {
                   $flat->registered_flats->each(
                       function ($reg_flat) use ($item) {
                           if (!is_null($reg_flat->user)) {
                               \Notification::send($reg_flat->user, new BeginIntramuralVoting($item->house));
                           }
                       }
                   );
               }
           );
       });

        Voting::whereBetween('public_at', array(Carbon::now()->startOfDay(), Carbon::now()->endOfDay()))->each(function ($item){
            $item->house->connectedFlats->each(
                function ($flat) use ($item) {
                    $flat->registered_flats->each(
                        function ($reg_flat) use ($item) {
                            if (!is_null($reg_flat->user)) {
                                \Notification::send($reg_flat->user, new BeginExtramuralVoting($item->house));
                            }
                        }
                    );
                }
            );
        });


    }
}
