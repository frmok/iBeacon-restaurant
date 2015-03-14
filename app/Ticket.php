<?php namespace App;
use \DB;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model{
    protected $table = 'ticket';
    protected $fillable = ['people', 'number', 'ticket_status', 'queue_type_id']; //to be determined
    public static $statusText = [0 => 'Waiting', 1 => 'Dequeued', 2 => 'Entered'];

    public static function boot()
    {
        parent::boot();
        static::created(function($ticket)
        {
            \Event::fire('ticket.created', array($ticket));
        });
        static::updated(function($ticket)
        {
            \Event::fire('ticket.updated', array($ticket));
        });
    }

    public function queueType(){
        return $this->belongsTo('App\QueueType');
    }
    
    /**
    * Create a new ticket and return the data.
    *
    * @param int $type
    * @param int $people
    * @return Ticket
    */
    public static function enqueue($type, $people){
        $ticket = new Ticket();
        $ticket->queue_type_id = $type;
        $ticket->ticket_number = self::where('queue_type_id', $type)->where('cleared', 0)->max('ticket_number') + 1;
        $ticket->ticket_status = 0;
        $ticket->people = $people;
        $ticket->save();
        return $ticket;
    }
    
    /**
    * Dequeue a ticket
    *
    * @param int $id
    * @return void
    */
    public static function dequeue($id){
        $ticket = Ticket::find($id);
        $ticket->ticket_status = 1; //dequeued;
        $ticket->save();
    }
    
    /**
    * Mark a ticket entered.
    *
    * @param int $id
    * @return void
    */
    public static function entered($id){
        $ticket = Ticket::find($id);
        $ticket->ticket_status = 2; //dequeued;
        $ticket->save();
    }

    public function getStatusTextAttribute(){
        return self::$statusText[$this->ticket_status];
    }
    
    /**
    * Get the current ticket number for a specific queue type.
    *
    * @param int $id
    * @return int
    */
    public static function currentTicket($id){
        $data = self::where('cleared', 0)
        ->where('queue_type_id', $id)
        ->where(
            function ($query) {
                $query->where('ticket_status', 1)->orWhere('ticket_status', 2);
            })
        ->max('ticket_number');
        if($data == NULL){
            $data = 0;
        }
        return intVal($data);
    }

    /**
    * Get the number of waiting people for a specific queue type.
    *
    * @param int $id
    * @return int
    */
    public static function waitingPeople($id){
        return intVal(count(self::where('cleared', 0)
        ->where('queue_type_id', $id)
        ->where('ticket_status', 0)
        ->get()));
    }

    /**
    * Get the average waiting time (in second) for a specific queue type.
    *
    * @param int $id
    * @return array
    */
    public static function avgWaitingTime($id){
        $data = \DB::table('ticket')->select(DB::raw('ROUND(AVG(TIME_TO_SEC(TIMEDIFF(updated_at, created_at)))) as value'))
        ->where('queue_type_id', $id)
        ->where('ticket_status', 2)
        ->get();
        if($data[0]->value == 0){
            $data[0]->value = 0;
        }
        return $data;
    }
}