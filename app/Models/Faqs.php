<?php

namespace App\Models;

class faqs extends Mmb
{
    protected $table      = 'faq';
    protected $primaryKey = 'faqID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT faq.* FROM faq  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  faq.owner_id = ' . CNF_OWNER . ' AND faq.faqID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }
}
