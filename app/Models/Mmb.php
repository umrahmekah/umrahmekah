<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Mmb extends Model
{
    public static function getRows($args)
    {
        $table = with(new static())->table;
        $key   = with(new static())->primaryKey;

        extract(array_merge([
            'page'   => '0',
            'limit'  => '0',
            'sort'   => '',
            'order'  => '',
            'params' => '',
            'flimit' => '',
            'fstart' => '',
            'global' => 1,
        ], $args));

        $offset           = ($page - 1) * $limit;
        $limitConditional = (0 != $page && 0 != $limit) ? "LIMIT  $offset , $limit" : '';
        /* Added since version 5.1.7 */
        if ('' != $fstart && '' != $flimit) {
            $limitConditional = "LIMIT  $fstart , $flimit";
        }
        /* End Added since version 5.1.7 */

        $orderConditional = ('' != $sort && '' != $order) ? " ORDER BY {$sort} {$order} " : '';

        // Update permission global / own access new ver 1.1
        $table = with(new static())->table;
        if (0 == $global) {
            $params .= " AND {$table}.entry_by ='" . \Session::get('uid') . "'";
        }

        // add traveller's data traveler is accessing
        

        if (Auth::user() && Auth::user()->group_id  == 6) {
            $travellers = Travellers::where('email', Auth::user()->email)->where('owner_id', CNF_OWNER)->get(['travellerID'])->toArray();

            $travellerID = [];

            foreach ($travellers as $key => $traveller) {
                array_push($travellerID, $traveller['travellerID']);
            }

            if (count($travellerID) && $table != 'tours') {
                $params .= " OR {$table}.travellerID IN (" . implode(',', $travellerID) . ")";
            }
        }
        // End Update permission global / own access new ver 1.1

        $rows   = [];
        $result = \DB::select(self::querySelect() . self::queryWhere() . " 
				{$params} " . self::queryGroup() . " {$orderConditional}  {$limitConditional} ");

        $total = \DB::select('SELECT COUNT(*) AS total FROM ' . $table . ' ' . self::queryWhere() . " 
				{$params} " . self::queryGroup());
        $total = (0 != count($total) ? $total[0]->total : 0);

        return $results = ['rows' => $result, 'total' => $total];
    }

    public static function getRow($id)
    {
        $table = with(new static())->table;
        $key   = with(new static())->primaryKey;

        $result = \DB::select(
                self::querySelect() .
                self::queryWhere() .
                ' AND ' . $table . '.' . $key . " = '{$id}' " .
                self::queryGroup()
            );
        if (count($result) <= 0) {
            $result = [];
        } else {
            $result = $result[0];
        }

        return $result;
    }

    public static function retrive($id)
    {
        $key = with(new static())->primaryKey;

        return self::where($key, '=', $id)->where('owner_id', '=', CNF_OWNER)->first();
    }

    public static function prevNext($id)
    {
        $table = with(new static())->table;
        $key   = with(new static())->primaryKey;

        $prev = '';
        $next = '';

        $Qnext = \DB::select(
            self::querySelect() .
            self::queryWhere() .
            ' AND ' . $table . '.' . $key . " > '{$id}'  " .
            self::queryGroup() . ' LIMIT 1'
        );

        if (count($Qnext) >= 1) {
            $next = $Qnext[0]->{$key};
        }

        $Qprev = \DB::select(
            self::querySelect() .
            self::queryWhere() .
            ' AND ' . $table . '.' . $key . " < '{$id}'" .
            self::queryGroup() . ' ORDER BY ' . $table . '.' . $key . ' DESC LIMIT 1'
        );
        if (count($Qprev) >= 1) {
            $prev = $Qprev[0]->{$key};
        }

        return ['prev' => $prev, 'next' => $next];
    }

    public function insertRow($data, $id)
    {
        $table = with(new static())->table;
        $key   = with(new static())->primaryKey;

        if (null == $id) {
            // Insert Here
            $data['owner_id'] = CNF_OWNER;
            if (isset($data['createdOn'])) {
                $data['createdOn'] = date('Y-m-d H:i:s');
            }
            if (isset($data['updatedOn'])) {
                $data['updatedOn'] = date('Y-m-d H:i:s');
            }
            $id = \DB::table($table)->insertGetId($data);

            //update credit total when owner success buy credit
            if ('credit_transactions' == $table) {
                //dd($table);die();
                $table2 = 'credittotals';
                if (\DB::table($table2)->where('owner_id', $data['owner_id'])->count() > 0) {
                    $credit = \DB::table($table2)->select('total_credit')->where('owner_id', $data['agency'])->first();
                    //dd($credit);
                    $total = $credit->total_credit + $data['credit_request'];
                    //dd($total);
                    \DB::table($table2)->where('owner_id', $data['agency'])->update(['total_credit' => $total]);
                } else {
                    \DB::table($table2)->insertGetId(
                        ['owner_id'        => $data['owner_id'],
                            'total_credit' => $data['credit_request'],
                            'entry_by'     => $data['entry_by'], ]
                    );
                }
            }
        } else {
            // Update here
            // update created field if any
            if (isset($data['createdOn'])) {
                unset($data['createdOn']);
            }
            if (isset($data['updatedOn'])) {
                $data['updatedOn'] = date('Y-m-d H:i:s');
            }
            \DB::table($table)->where($key, $id)->update($data);
        }

        return $id;
    }

    public function insertNewRow($data)
    {
        $table = with(new static())->table;
        $key   = with(new static())->primaryKey;

        if (isset($data['createdOn'])) {
            $data['createdOn'] = date('Y-m-d H:i:s');
        }
        if (isset($data['updatedOn'])) {
            $data['updatedOn'] = date('Y-m-d H:i:s');
        }
        $id = \DB::table($table)->insertGetId($data);

        return $id;
    }

    public static function makeInfo($id)
    {
        $row  = \DB::table('tb_module')->where('module_name', $id)->get();
        $data = [];
        foreach ($row as $r) {
            $langs         = (json_decode($r->module_lang, true));
            $data['id']    = $r->module_id;
            $data['title'] = \SiteHelpers::infoLang($r->module_title, $langs, 'title');
            $data['note']  = \SiteHelpers::infoLang($r->module_note, $langs, 'note');
            $data['table'] = $r->module_db;
            $data['key']   = $r->module_db_key;

            //dd($r->module_config);
            //$arrConfig = \SiteHelpers::CF_decode_json($r->module_config);
            //$arrConfig['forms'][18]=$arrConfig['forms'][17];
            //$arrConfig['forms'][17]=$arrConfig['forms'][16];
            //$arrConfig['forms'][16]=$arrConfig['forms'][15];
            //$arrConfig['forms'][15]=$arrConfig['forms'][14];
            //$arrConfig['forms'][31]=$arrConfig['forms'][30];
            //$arrConfig['forms'][31]['field']='google_calendar';
            //$arrConfig['forms'][31]['label']='Google Calendar';
            //dd($arrConfig['forms']);
            //dd(\SiteHelpers::CF_encode_json($arrConfig));
            $data['config'] = \SiteHelpers::CF_decode_json($r->module_config);
            //dd($data['config']);

            $field = [];
            foreach ($data['config']['grid'] as $fs) {
                foreach ($fs as $f) {
                    $field[] = $fs['field'];
                }
            }
            $data['field']   = $field;
            $data['setting'] = [
                'gridtype'    => (isset($data['config']['setting']['gridtype']) ? $data['config']['setting']['gridtype'] : 'native'),
                'orderby'     => (isset($data['config']['setting']['orderby']) ? $data['config']['setting']['orderby'] : $r->module_db_key),
                'ordertype'   => (isset($data['config']['setting']['ordertype']) ? $data['config']['setting']['ordertype'] : 'asc'),
                'perpage'     => (isset($data['config']['setting']['perpage']) ? $data['config']['setting']['perpage'] : '10'),
                'frozen'      => (isset($data['config']['setting']['frozen']) ? $data['config']['setting']['frozen'] : 'false'),
                'form-method' => (isset($data['config']['setting']['form-method']) ? $data['config']['setting']['form-method'] : 'native'),
                'view-method' => (isset($data['config']['setting']['view-method']) ? $data['config']['setting']['view-method'] : 'native'),
                'inline'      => (isset($data['config']['setting']['inline']) ? $data['config']['setting']['inline'] : 'false'),
            ];
        }

        return $data;
    }

    public static function getComboselect($params, $limit = null, $parent = null)
    {
        $limit       = explode(':', $limit);
        $parent      = explode(':', $parent);
        $table       = $params[0];
        $owner_only  = false;
        $group_combo = false;
        if (in_array($table, ['bookings', 'travellers', 'travel_agent', 'cars', 'def_extra_services', 'hotels', 'tours', 'tour_date', 'guides', 'tb_pages', 'tb_users', 'def_supplier', 'def_inclusions', 'termsandconditions'])) {
            $owner_only = true;
        }
        if (in_array($table, ['tb_groups'])) {
            $group_combo = true;
        }
        if (count($limit) >= 3) {
            $condition = $limit[0] . ' `' . $limit[1] . '` ' . $limit[2] . ' ' . $limit[3] . ' ';
            if (count($parent) >= 2) {
                $row = \DB::table($table)->where($parent[0], $parent[1])->get();
                if ($owner_only) {
                    $row = \DB::select('SELECT * FROM ' . $table . ' ' . $condition . " AND `owner_id` = '" . CNF_OWNER . "' AND " . $parent[0] . " = '" . $parent[1] . "'");
                } else {
                    $row = \DB::select('SELECT * FROM ' . $table . ' ' . $condition . ' AND ' . $parent[0] . " = '" . $parent[1] . "'");
                }
            } else {
                if ($owner_only) {
                    $row = \DB::select('SELECT * FROM ' . $table . ' ' . $condition . " AND `owner_id` = '" . CNF_OWNER . "'");
                } else {
                    $row = \DB::select('SELECT * FROM ' . $table . ' ' . $condition);
                }
            }
        } else {
            if (count($parent) >= 2) {
                if ($owner_only) {
                    $row = \DB::table($table)->where($parent[0], $parent[1])->where('owner_id', '=', CNF_OWNER)->get();
                } else {
                    $row = \DB::table($table)->where($parent[0], $parent[1])->get();
                }
            } else {
                if ($owner_only) {
                    $row = \DB::table($table)->where('owner_id', '=', CNF_OWNER)->get();
                } elseif ($group_combo) {
                    if (1 == \Session::get('gid')) {
                        $row = \DB::table($table)->get();
                    } else {
                        $row = \DB::table($table)->where('group_id', '!=', 1)->get();
                    }
                } else {
                    $row = \DB::table($table)->get();
                }
            }
        }

        return $row;
    }

    public static function getColoumnInfo($result)
    {
        $pdo  = \DB::getPdo();
        $res  = $pdo->query($result);
        $i    = 0;
        $coll = [];
        while ($i < $res->columnCount()) {
            $info   = $res->getColumnMeta($i);
            $coll[] = $info;
            ++$i;
        }

        return $coll;
    }

    public function validAccess($id)
    {
        $row = \DB::table('tb_groups_access')->where('module_id', '=', $id)
                ->where('group_id', '=', \Session::get('gid'))
                ->get();

        if (count($row) >= 1) {
            $row = $row[0];
            if ('' != $row->access_data) {
                $data = json_decode($row->access_data, true);
            } else {
                $data = [];
            }

            return $data;
        } else {
            return false;
        }
    }

    public static function getColumnTable($table)
    {
        $columns = [];
        foreach (\DB::select("SHOW COLUMNS FROM $table") as $column) {
            $columns[$column->Field] = '';
        }

        return $columns;
    }

    public static function getTableList($db)
    {
        $t      = [];
        $dbname = 'Tables_in_' . $db;
        foreach (\DB::select("SHOW TABLES FROM {$db}") as $table) {
            $t[$table->$dbname] = $table->$dbname;
        }

        return $t;
    }

    public static function getTableField($table)
    {
        $columns = [];
        foreach (\DB::select("SHOW COLUMNS FROM $table") as $column) {
            $columns[$column->Field] = $column->Field;
        }

        return $columns;
    }
}
