<?php

namespace App\Models;

class support extends Mmb
{
    protected $table      = 'tbl_tickets';
    protected $primaryKey = 'TicketID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return "  SELECT 
	tbl_tickets.* ,
	CONCAT(first_name,' ',last_name) AS fullname ,
	email , avatar 
 
 FROM tbl_tickets
 LEFT JOIN tb_users ON tb_users.id = tbl_tickets.UserID ";
    }

    public static function queryWhere()
    {
        return '  WHERE tbl_tickets.owner_id = ' . CNF_OWNER . ' AND tbl_tickets.TicketID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public static function replies($TicketID)
    {
        return  \DB::select("
		 SELECT 
			tbl_tickets_reply.* ,
			CONCAT(first_name,' ',last_name) AS fullname ,
			email , avatar 
		 
		 FROM tbl_tickets_reply
		 LEFT JOIN tb_users ON tb_users.id = tbl_tickets_reply.UserID
		 WHERE tbl_tickets_reply.owner_id = " . CNF_OWNER . " AND tbl_tickets_reply.TicketID ='" . $TicketID . "'
		 ORDER BY tbl_tickets_reply.createdOn ASC
 
		");
    }

    public function deleteReply($id)
    {
        $operation = \DB::table('tbl_tickets_reply')->where('owner_id', '=', CNF_OWNER)->where('ReplyID', $id)->delete();
        if (! $operation) {
            return false;
        } else {
            return true;
        }
    }

    public function status($id, $status)
    {
        $operation = \DB::table('tbl_tickets')->where('owner_id', '=', CNF_OWNER)->where('TicketID', $id)->update(['Status' => $status]);
        if (! $operation) {
            return false;
        } else {
            return true;
        }
    }
}
