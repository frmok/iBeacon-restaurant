<?php namespace App;

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

    public static function enqueue($type, $people){
        $ticket = new Ticket();
        $ticket->queue_type_id = $type;
        $ticket->ticket_number = self::where('queue_type_id', $type)->where('cleared', 0)->max('ticket_number') + 1;
        $ticket->ticket_status = 0;
        $ticket->people = $people;
        $ticket->save();
    }

    public static function dequeue($id){
        $ticket = Ticket::find($id);
        $ticket->ticket_status = 1; //dequeued;
        $ticket->save();
    }

    public static function entered($id){
        $ticket = Ticket::find($id);
        $ticket->ticket_status = 2; //dequeued;
        $ticket->save();
    }

    public function getStatusTextAttribute(){
        return self::$statusText[$this->ticket_status];
    }
}