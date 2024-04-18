<?php

namespace WPRelay\Paypal\App\Services;

use WPRelay\Paypal\App\Exception\ModelNotFoundException;
use WPRelay\Paypal\App\Traits\Conditionable;
use WPRelay\Paypal\App\Traits\useScopes;
use WPRelay\Paypal\Core\Models\Model;

class Database
{
    use Conditionable, useScopes;

    const AND = 'AND';
    const OR = 'AND';

    public $singleKeyword = [
        'select' => false,
        'where' => false,
        'groupBy' => false,
        'orderBy' => false,
        'having' => false,
    ];


    private $db;

    private $table;

    private $query = null;

    public function __construct($table)
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $table;
    }

    public function select($columns = '*')
    {
        $this->query = "SELECT $columns FROM {$this->table}";
        $this->singleKeyword['select'] = true;
        return $this;
    }

    /**
     * Updated the where clause to prepare the query whenever the user given inputs.
     * @param $condition
     * @param $values
     * @return $this
     */
    public function where($condition, $values = [])
    {
        if (!empty($values)) {
            $condition = $this->db->prepare($condition, $values);
        }

        if (!$this->clauseAdded('select')) {
            $this->select();
        }

        if ($this->clauseAdded('where')) {
            $this->query .= " AND $condition ";
        } else {
            $this->query .= " WHERE $condition ";
            $this->singleKeyword['where'] = true;
        }

        return $this;
    }

    public function orWhere($condition, $values = [])
    {
        if (!empty($values)) {
            $condition = $this->db->prepare($condition, $values);
        }

        if (!$this->clauseAdded('select')) {
            $this->select();
        }

        if ($this->clauseAdded('where')) {
            $this->query .= " OR $condition ";
        } else {
            throw new \Exception('Where Clause Not Added');
        }

        return $this;
    }

    public function join($table, $onCondition, $type = 'INNER')
    {
        if (!$this->clauseAdded('select')) {
            $this->select();
        }

        $this->query .= " $type JOIN $table ON $onCondition ";
        return $this;
    }

    public function leftJoin($table, $onCondition)
    {
        return $this->join($table, $onCondition, 'LEFT');
    }

    public function rightJoin($table, $onCondition)
    {
        return $this->join($table, $onCondition, 'RIGHT');
    }

    public function orderBy($column, $direction = 'ASC')
    {
        if (!$this->clauseAdded('orderBy')) {
            $this->query .= " ORDER BY $column $direction ";
            $this->singleKeyword['orderBy'] = true;
        } else {
            $this->query .= " $column $direction ";
        }
        return $this;
    }

    public function having($sql)
    {
        if (!$this->clauseAdded('having')) {
            $this->query .= " having $sql ";
            $this->singleKeyword['having'] = true;
        } else {
            $this->query .= " {$sql} ";
        }
        return $this;
    }

    public function groupBy($column)
    {
        if (!$this->clauseAdded('groupBy')) {
            $this->query .= " GROUP BY $column ";
            $this->singleKeyword['groupBy'] = true;
        } else {
            $this->query .= " , $column ";
        }

        return $this;
    }

    public function limit($limit)
    {
        $this->query .= $this->prepareQuery(" LIMIT %d ", [$limit]);
        return $this;
    }

    public function offset($offset)
    {
        $this->query .= $this->prepareQuery(" OFFSET %d ", [$offset]);
        return $this;
    }

    public function get()
    {
        if (is_null($this->query)) {
            $this->select();
        }

        $results = $this->db->get_results($this->query);


        return $this->processResults($results, 'get_results');
    }

    public function find($id)
    {
        if (is_null($this->query)) {
            $this->select()->where("id = %d", [$id]);
        }

        return $this->getSingleRow();
    }

    public function getSingleRow()
    {
        $this->limit(1);

        $results = $this->db->get_results($this->query);

        if (is_iterable($results) && count($results) > 0) {
            $result = $results[0];
        } else {
            $result = $results;
        }

        return $this->processResults($result, 'get_results');
    }

    public function first()
    {
        if (is_null($this->query)) {
            $this->select();
        }

        return $this->getSingleRow();

    }

    public function firstOrFail()
    {
        if (is_null($this->query)) {
            $this->select();
        }

        $row = $this->db->get_row($this->query);

        return $this->processResults($row, 'get_row');
    }

    public function findOrFail($id)
    {
        if (is_null($this->query)) {
            $this->select()->where("id = $id");;
        }

        $row = $this->db->get_row($this->query);

        return $this->processResults($row, 'get_row');
    }

    public function findBy($key, $value)
    {
        if (is_null($this->query)) {
            $this->select()->where("{$key} = %s", [$value]);
        }

        return $this->getSingleRow();
    }


    public function all()
    {
        return $this->select($this->table)->get();
    }

    public function rawQuery($sql)
    {
        $this->query = $sql;
        return $this;
    }

    public function create($data)
    {
        $result = $this->db->insert($this->table, $data);
        return $this->processResults($result, 'insert');
    }

    public function update($data, $where, $format = null, $whereFormat = null)
    {
        $result = $this->db->update($this->table, $data, $where, $format, $whereFormat);
        return $this->processResults($result, 'update');
    }

    public function delete($conditions)
    {
        $results = $this->db->delete($this->table, $conditions);
        return $this->processResults($results, 'delete');
    }

    public function toSql($dump = true)
    {
        return $this->query;
    }

    public function dd()
    {
        var_dump($this->query);
        die;
    }

    public function dump()
    {
        var_dump($this->query);
    }

    public function lastInsertedId()
    {
        global $wpdb;
        return $wpdb->insert_id;
    }

    public function clauseAdded($keyword)
    {
        return $this->singleKeyword[$keyword];
    }

    /**
     * @throws \Exception
     */
    public function count()
    {
        $row = $this->db->get_row("SELECT COUNT(*) as count from ($this->query) as temp");

        $row = $this->processResults($row, 'get_row');

        return $row->count;
    }

    /**
     * @throws \Exception
     */
    public function processResults($results, $method)
    {
        $errorType = false;
        switch ($method) {
            case 'get_var':
            case 'get_row':
                $errorType = null;
                break;
            case 'get_results':
            case 'update':
            case 'insert':
            case 'delete' :
                $errorType = false;
                break;
        }
        return $this->resultOrException($results, $errorType);
    }

    public function resultOrException($results, $errorType)
    {
        if ($results === $errorType) {
            throw new \Exception($this->db->last_error);
        }

        return $results;
    }

    public static function beginTransaction()
    {
        global $wpdb;

        $wpdb->query("START TRANSACTION;");
    }

    public static function commit()
    {
        global $wpdb;

        $wpdb->query("COMMIT");
    }

    public static function rollBack()
    {
        global $wpdb;

        $wpdb->query("ROLLBACK");
    }

    public function prepareQuery($query, $bindings = [])
    {
        return $this->db->prepare($query, $bindings);
    }

    public function getWpLastError()
    {
        return $this->db->last_error;
    }
}
