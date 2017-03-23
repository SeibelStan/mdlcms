<?php

class A_BaseModel {

    public function checkNoEmptyFill($fieldName, $value) {
        if(!isset($this->noEmpty)) {
            return true;
        }
        if(!(in_array($fieldName, $this->noEmpty))) {
            return true;
        }
        return $value !== ''
            && !preg_match('/0000-00-00/', $value)
            && !preg_match('/1970-01-01/', $value);
    }

    public function isRemovable() {
        return isset($this->removable) && $this->removable;
    }

    public function isAddable() {
        return isset($this->addable) && $this->addable;
    }

    public function getName() {
        return get_called_class();
    }

    public function getTitle() {
        return isset($this->title) && $this->title ? $this->title : $this->table;
    }

    public function getFillable() {
        return isset($this->fillable) && $this->fillable ? $this->fillable : [];
    }

    public function isFillable($fieldName) {
        return in_array($fieldName, $this->getFillable());
    }

    public function getRequired() {
        return isset($this->required) && $this->required ? $this->required : [];
    }

    public function isRequired($fieldName) {
        return in_array($fieldName, $this->getRequired());
    }

    public function getPattern($fieldName) {
        return isset($this->pattern) && isset($this->pattern[$fieldName]) ? $this->pattern[$fieldName] : false;
    }

    public function getExtraView($fieldName) {
        return isset($this->extraView) && isset($this->extraView[$fieldName]) ? $this->extraView[$fieldName] : false;
    }

    public function getTable() {
        return isset($this->table) && $this->table ? $this->table : false;
    }

    public function getSearchable() {
        return isset($this->searchable) && $this->searchable ? $this->searchable : false;
    }

    public function getFieldTitle($fieldName) {
        foreach($this->fields as $field) {
            if(isset($this->titles) && in_array($fieldName, array_keys($this->titles))) {
                return $this->titles[$fieldName];
            }
            else {
                return $fieldName;
            }
        }        
    }

    public function getFields($id = 0, $fillable = false) {
        $exemp = $this->getByField('id', $id);
        $fields = $this->fields;
        $prepfields = [];
        foreach($fields as $name => $value) {
            if($fillable && !$this->isFillable($name)) {
                continue;
            }

            $pvalue = explode(':', $value);
            $type = $pvalue[0];

            if(preg_match('/varchar\(([2-9]\d{2}|\d{4})\)/', $type) || $type == 'text') {
                $control = 'textarea';
            }
            elseif(preg_match('/int\(1\)/', $type)) {
                $control = 'checkbox';
            }
            elseif(in_array($type, ['timestamp', 'datetime'])) {
                $control = 'datetime-local';
            }
            elseif(in_array($type, ['date'])) {
                $control = 'date';
            }
            else {
                $control = 'text';
            }

            $field = (object)[
                'name' => $name,
                'title' => $this->getFieldTitle($name),
                'type' => $type,
                'control' => $control,
                'required' => $this->isRequired($name),
                'value' => $exemp ? $exemp->$name : ''
            ];

            array_push($prepfields, $field);
        }
        return $prepfields;
    }

    public function getByField($fieldName, $value, $condition = false) {
        if(!$this->getTable()) {
            return false;
        }
        $sql = "select * from $this->table where $fieldName = '$value'";
        if($condition) {
            $sql .= " " . $condition;
        }
        $result =  dbs($sql);
        return $result ? $result[0] : false;
    }

    public function getUnits($condition = false, $sort = false, $limit = false) {
        global $db;
        $sql = "select * from $this->table";
        
        if($condition) {
            $sql .= " where " . $condition;
        }
        if($sort) {
            $sql .= " order by " . $sort;
        }
        if($limit) {
            $sql .= " limit " . $limit;
        }
        $units = dbs($sql);
        foreach($units as $unit) {
            foreach(['id', 'login', 'full_name', 'name', 'title'] as $tryName) {
                if(isset($unit->$tryName) && $unit->$tryName) {
                    $unit->display_name = $unit->$tryName;
                }
            }
        }
        return $units;
    }

    public function saveUnit($id, $data, $fillable = false) {
        global $db;
        $fields = $this->getFields($id, $fillable);

        foreach($fields as $field) {
            if($field->control == 'checkbox' && (isset($data[$field->name]) || isset($data['id']))) {
                $data[$field->name] = $data[$field->name] ? 1 : 0;
            }
        }

        if($id) {
            $sql = "update " . $this->getTable() . " set ";
            foreach($fields as $field) {
                if($field->name == 'dateup' || !isset($data[$field->name]) || !$this->checkNoEmptyFill($field->name, $data[$field->name])) {
                    continue;
                }
                $sql .= $field->name . " = '" . $data[$field->name] . "', ";
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= " where id = '$id'";
        }
        else {
            $sql = "insert into " . $this->getTable() . " (";
            foreach($fields as $field) {
                if(!isset($data[$field->name]) || !$this->checkNoEmptyFill($field->name, $data[$field->name])) {
                    continue;
                }
                $sql .= $field->name . ", ";
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= ") VALUES (";
            foreach($fields as $field) {
                if(!isset($data[$field->name]) || !$this->checkNoEmptyFill($field->name, $data[$field->name])) {
                    continue;
                }
                $sql .= "'" . $data[$field->name] . "', ";
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= ")";
        }

        //echo $sql;
        $db->query($sql);
        echo $db->error;
    }

    public function deleteUnit($field, $value = 0, $condition = false) {
        $where = "where $field = '$value'";
        $where .= $condition ? " and " . $condition : '';
        $sql = "delete from " . $this->getTable() . " $where";
        return dbu($sql);
    }

    public static function search($query, $limit = 12) {
        global $models;
        $results = [];
        foreach($models as $modelName) {
            $model = new $modelName();
            if($searchable = $model->getSearchable()) {
                $sql = "select * from " . $model->getTable() . " where";
                foreach($searchable as $field) {
                    $sql .= " $field like '%$query%' or";
                }
                $sql = preg_replace('/or$/', '', $sql);
                $sql .= " order by id desc limit $limit";
                $result = dbs($sql);
                foreach($result as $unit) {
                    $unit->url = isset($unit->url) && $unit->url ? $unit->url : $unit->id;
                    $unit->link = ROOT . strtolower($model->getName()) . '/' . $unit->url;
                    $unit->content = isset($unit->content) ? $unit->content : false;
                    $unit->date = isset($unit->date) ? $unit->date : false;
                    if(isset($unit->image)) {
                    }
                    elseif(isset($unit->images)) {
                        $unit->image = getTextRows($unit->images)[0];
                    }
                    else {
                        $unit->image = '';
                    }
                }
                $results = array_merge($results, $result);
            }
        }
        return $results;
    }

}