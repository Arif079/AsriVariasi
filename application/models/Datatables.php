<?php
class Datatables extends CI_Model {

    private $table = "products";
    private $select = "";
    private $where = array();
    private $where2 = array();
    private $join = array();
    private $column = array();
    private $result = array();
    private $searchField = NULL;
    private $ordering = array();
    private $group = array();
    private $whereIn = array();
    private $unionQueries = array();

    function setTable($name) {
        $this->table = $name;
    }

    function setSelect($select) {
        $this->select = $select;
    }

    function setUnion($query, $type = 'UNION') {
        if (!empty($query) && in_array(strtoupper($type), array('UNION', 'UNION ALL'))) {
            $this->unionQueries[] = array(
                'query' => $query,
                'type' => strtoupper($type)
            );
        }
    }
    function setJoin($table,$on,$type) {
        $this->join[] = [
            "table" => $table,
            "on" => $on,
            "type" => $type,
        ];
    }

    function setGroup($group) {
        $this->group[] = $group;
    }

    function setWhere2($conditions) {
        if (is_array($conditions) && !empty($conditions)) {
            $this->where2 = $conditions;
        }
    }

    function setWhere($key, $value) {
        if ($value instanceof CI_DB_query_builder) {
            // If the value is a subquery, we directly set it as the condition
            $this->where[$key] = $value;
        } else {
            // Otherwise, we assume it's a regular value and set it normally
            $this->where[$key] = $value;
        }
    }
    function setColumnSaldo($saldoIndex) {
        $this->saldoIndex = $saldoIndex;
    }
    
    private function calculateSaldo() {
        $saldo = 0;
        foreach ($this->result as &$row) {
            $debet = isset($row['debet']) ? $row['debet'] : 0;
            $kredit = isset($row['kredit']) ? $row['kredit'] : 0;
            $saldo += $debet - $kredit;
            $row[$this->saldoIndex] = number_format($saldo, 2);
        }
    }
    
    function setColumn($column = array()) {
        $this->column = $column;
    }

    function setSearchField($fields) {
        $this->searchField = $fields;
    }

    function setOrdering($ordering) {
        $this->ordering = $ordering;
    }

    private function process() {
        if($this->select) {
            $this->db->select($this->select);
        }
    
        if($this->join) {
            foreach($this->join as $join) {
                $this->db->join($join["table"],$join["on"],$join["type"]);
            }
        }
    
        if(isset($_GET['search']['value'])) {
            if(!empty($this->searchField)) {
                $this->db->group_start();
                $i = 0;
                foreach ($this->searchField as $field) {
                    if ($i > 0) {
                        $this->db->or_like($field, $_GET['search']['value']);
                    } else {
                        $this->db->like($field, $_GET['search']['value']);
                    }
                    $i++;
                }
                $this->db->group_end();
                
            }
        
            if ($this->where2) {
                foreach ($this->where2 as $condition) {
                    if ($i > 0) {
                        $this->db->or_where($condition[0], $condition[1]);
                    } else {
                        $this->db->where($condition[0], $condition[1]);
                    }
                    $i++;
                }
            }

        if (!empty($this->whereIn)) {
            foreach ($this->whereIn as $key => $values) {
                $this->db->where_in($key, $values);
                }
            }
        }

    
        if(isset($_GET['order'][0]['column'])) {
            $order_by = $_GET['order'][0]['column'];
            $this->db->order_by($this->ordering[$order_by], $_GET['order'][0]['dir']);
        }
    
        if($this->group) {
            foreach($this->group as $group) {
                $this->db->group_by($group);    
            }
        }
    }

    private function processUnion() {
        if (!empty($this->unionQueries)) {
            foreach ($this->unionQueries as $union) {
                $this->db->query($union['query'] . ' ' . $union['type']);
            }
        }
    }
    
    private function columnReplace($index,$string) {

        preg_match_all("/<get-([A-Za-z0-9]+)>/",$string,$get);

        $index2 = $index + $_GET['start'];

        $i = 0;

        $array = ["id" => 1];

        $string = str_replace("<index>",$index2+1,$string);

        foreach($get[1] as $row) {
            $string = str_replace($get[0][$i],$this->result[$index][$row],$string);
            $i++;
        }

        preg_match_all("/\[(.*)\=(.*)\]/",$string,$function);

        $i = 0;

        foreach($function[0] as $row) {
            $callFunc = $function[1][$i];
            $getStr = $function[2][$i];
            $tmp = $callFunc($getStr);

            $string = str_replace($row,$tmp,$string);
            $i++;
        }

        return $string;
    }

    function get_num_rows() {
        $this->process();
        if($this->where) {
            $data = $this->db->get_where($this->table,$this->where);
        } else {
            $data = $this->db->get($this->table);
        }
        return $data->num_rows();
    }
    
    function setWhereIn($key, $values) {
        if (!empty($key) && is_array($values) && !empty($values)) {
            $this->whereIn[$key] = $values;
        }
    }

    function generate() {
        $this->process();
        if($_GET['length'] > 0) {
            $this->db->limit($_GET['length'], $_GET['start']);
        }

        if($this->where) {
            $data = $this->db->get_where($this->table,$this->where);
        } else {
            $data = $this->db->get($this->table);
        }

        $this->calculateSaldo();
        $this->processUnion();
        
        $this->result = $data->result_array();
        
        $response['draw'] = $_GET['draw'];
        $response['recordsTotal'] = $this->get_num_rows();
        $response['recordsFiltered'] = $this->get_num_rows();
        $response['data'] = array();

        $i = 0;
        foreach($this->result as $row) {
            $tmp = array();
            foreach($this->column as $col) {
                $tmp[] = $this->columnReplace($i,$col);
            }

            $response['data'][] = $tmp;
            $i++;
        }

        echo json_encode($response);
    }
}
