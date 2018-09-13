<?php

class A_BaseModel {

    public static function getFieldTitle($fieldName) {
        foreach (static::$fields as $field) {
            if (isset(static::$titles) && in_array($fieldName, array_keys(static::$titles))) {
                return static::$titles[$fieldName];
            }
            else {
                return $fieldName;
            }
        }
    }

    public static function getFields($id = 0, $fillable = false) {
        $exemp = false;
        if ($id) {
            if (is_array($id)) {
                $key = array_keys($id)[0];
                $exemp = static::getByField($key, $id[$key]);
            }
            else {
                $exemp = static::getByField('id', $id);
            }
        }

        $fields = static::$fields;
        $prepfields = (object) [];
        foreach ($fields as $name => $value) {
            if ($fillable && !static::isFillable($name)) {
                continue;
            }

            $pvalue = explode(':', $value);
            $type = $pvalue[0];

            if (isset(static::$inputTypes) && isset(static::$inputTypes[$name])) {
                $tareaClass = $exemp ? preg_match('/class=/', $exemp->$name) : false;
                if ($tareaClass) {
                    $control = 'textarea';
                }
                else {
                    $control = static::$inputTypes[$name];
                }
            }
            elseif (preg_match('/varchar\(([2-9]\d{2}|\d{4})\)/', $type) || preg_match('/text/', $type)) {
                $control = 'textarea';
            }
            elseif (preg_match('/int\(1\)/', $type)) {
                $control = 'checkbox';
            }
            elseif (preg_match('/int/', $type)) {
                $control = 'number';
            }
            elseif (preg_match('/(email)/', $name)) {
                $control = 'email';
            }
            elseif (in_array($type, ['timestamp', 'datetime'])) {
                $control = 'datetime-local';
            }
            elseif (in_array($type, ['date'])) {
                $control = 'date';
            }
            else {
                $control = 'text';
            }

            $field = (object) [
                'name' => $name,
                'title' => static::getFieldTitle($name),
                'type' => $type,
                'control' => $control,
                'required' => static::isRequired($name),
                'value' => $exemp ? $exemp->$name : ''
            ];

            $prepfields->$name = $field;
        }
        return $prepfields;
    }

    public static function checkPattern($pattern, $data) {
        return preg_match('/^' . static::getPattern($pattern)[0] . '$/', $data);
    }

    public static function getByField($fieldName, $value, $condition = false) {
        $parseName = explode('|', $fieldName);
        $fieldName = $parseName[0];
        $arg = isset($parseName[1]) ? $parseName[1] : '';

        switch ($arg) {
            case 'like': {
                $sql = "* from " . static::$table . " where $fieldName like '%$value%'";
                break;
            }
            default: {
                $sql = "* from " . static::$table . " where $fieldName = '$value'";
            }
        }

        if ($condition) {
            $sql .= " " . $condition;
        }
        $result = dbs($sql);
        if ($result) {
            $result = $result[0];
            if (isset($result->url) && !$result->url) {
                $result->url = $result->id;
            }
        }
        return $result;
    }

    public static function paginate($condition = false, $sort = false, $limit = 1, $page = 1) {
        global $db;
        $sql = "count(id) as count from " . static::getTable();
        if ($condition) {
            $sql .= " where " . $condition;
        }
        if ($sort) {
            $sql .= " order by " . $sort;
        }
        $units = dbs($sql);

        $count = $units[0]->count;
        if ($count <= $limit) {
            return [];
        }

        $result = [];
        $iPage = 1;
        for ($i = 1; $i <= $count; $i += $limit) {
            array_push($result, (object) [
                'link' => ROOT . static::getName() . '?page=' . $iPage,
                'title' => $iPage,
                'active' => $iPage == $page,
            ]);
            $iPage++;
        }

        return $result;
    }

    public static function getUnits($condition = false, $sort = false, $limit = false, $page = false) {
        global $db;
        $sql = "* from " . static::getTable();

        if ($condition) {
            $sql .= " where " . $condition;
        }
        if ($sort) {
            $sql .= " order by " . $sort;
        }
        if ($limit) {
            $sql .= " limit " . $limit;
        }
        if ($page) {
            $sql .= " offset " . ($page - 1) * $limit;
        }
        $units = dbs($sql);
        foreach ($units as $unit) {
            foreach (['id', 'login', 'full_name', 'name', 'title'] as $tryName) {
                if (isset($unit->$tryName) && $unit->$tryName) {
                    $unit->display_name = $unit->$tryName;
                }
            }
            if (isset($unit->url) && !$unit->url) {
                $unit->url = $unit->id;
            }
        }
        return $units;
    }

    public static function save($data, $id = 0, $fillable = false) {
        global $db;

        if (isset(static::$fields->dateup)) {
            $data['dateup'] = date('Y-m-d H:i:s');
        }

        $fields = static::getFields();

        foreach ($fields as $field) {
            if ($field->control == 'checkbox' && isset($data[$field->name])) {
                $data[$field->name] = $data[$field->name] ? 1 : 0;
            } 
        }

        $key = 'id';
        $keyVal = $id;
        if (is_array($id)) {
            $key = array_keys($id)[0];
            $keyVal = $id[$key];
        }

        if (static::getByField($key, $keyVal)) {
            $sql = "update " . static::getTable() . " set ";
            foreach ($fields as $field) {
                if (!isset($data[$field->name]) || !static::checkNoEmptyFill($field->name, $data[$field->name])) {
                    continue;
                }
                if (preg_match('/(int)/', $field->type)) {
                    $sql .= $field->name . " = " . ($db->real_escape_string($data[$field->name]) ?: 0) . ", ";
                }
                else {
                    $sql .= $field->name . " = '" . $db->real_escape_string($data[$field->name]) . "', ";
                }
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= " where $key = '$keyVal'";
            $db->query($sql);

            return $db->affected_rows;
        }
        else {
            $sql = "insert into " . static::getTable() . " (";
            foreach ($fields as $field) {
                if (!isset($data[$field->name]) || !static::checkNoEmptyFill($field->name, $data[$field->name])) {
                    continue;
                }
                $sql .= $field->name . ", ";
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= ") VALUES (";
            foreach ($fields as $field) {
                if (!isset($data[$field->name]) || !static::checkNoEmptyFill($field->name, $data[$field->name])) {
                    continue;
                }
                if (preg_match('/(int|float)/', $field->type)) {
                    $sql .= ($db->real_escape_string($data[$field->name]) ?: 0) . ", ";
                }
                else {
                    $sql .= "'" . $db->real_escape_string($data[$field->name]) . "', ";
                }
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= ")";
            $db->query($sql);

            return $db->insert_id;
        }
    }

    public static function delete($field, $value = 0, $condition = false) {
        $where = "where $field = '$value'";
        $where .= $condition ? " and " . $condition : '';
        $sql = static::getTable() . " $where";
        return dbd($sql);
    }

    public static function clear($condition = false) {
        $where = $condition ? "where $condition" : "";
        $sql = static::getTable() . " $where";
        return dbd($sql);
    }

    public static function search($query, $sort = false, $limit = false) {
        $query = clear($query);
        $sort = $sort ?: 'id desc';
        $limit = $limit ?: 12;

        $modelsList = static::getName() == 'a_basemodel' ? Admin::getModelsList() : [static::getName()];
        $results = [];
        foreach ($modelsList as $model) {
            if ($searchable = $model::getSearchable()) {
                $sql = "* from " . $model::getTable() . " where";
                foreach ($searchable as $field) {
                    $sql .= " $field like '%$query%' or";
                }
                $sql = preg_replace('/or$/', '', $sql);
                $sql .= " order by $sort limit $limit";
                $result = dbs($sql);
                foreach ($result as $unit) {
                    $unit->url = isset($unit->url) && $unit->url ? $unit->url : $unit->id;
                    $unit->link = ROOT . strtolower($model::getName()) . '/' . $unit->url;
                    $unit->content = isset($unit->content) ? $unit->content : false;
                    $unit->date = isset($unit->date) ? $unit->date : false;
                    if (isset($unit->image)) {
                    }
                    elseif (isset($unit->images)) {
                        $unit->image = textRows($unit->images)[0];
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

    public static function checkNoEmptyFill($fieldName, $value) {
        $noEmpty = isset(static::$noEmpty) ? static::$noEmpty : [];
        $noEmptyMerged = array_merge($noEmpty, ['id']);
        if (!(in_array($fieldName, $noEmptyMerged))) {
            return true;
        }
        return $value !== ''
            && !preg_match('/0000-00-00/', $value)
            && !preg_match('/1970-01-01/', $value);
    }

    public static function isRemovable() {
        return isset(static::$removable) && static::$removable;
    }

    public static function isAddable() {
        return isset(static::$addable) && static::$addable;
    }

    public static function getName($lowercase = true) {
        $name = get_called_class();
        return $lowercase ? strtolower($name) : $name;
    }

    public static function getTitle() {
        return isset(static::$title) && static::$title ? static::$title : (isset(static::$table) ? static::$table : '');
    }

    public static function getFillable() {
        return isset(static::$fillable) && static::$fillable ? static::$fillable : [];
    }

    public static function isFillable($fieldName) {
        return in_array($fieldName, static::getFillable());
    }

    public static function getRequired() {
        return isset(static::$required) && static::$required ? static::$required : [];
    }

    public static function isRequired($fieldName) {
        return in_array($fieldName, static::getRequired());
    }

    public static function getPattern($fieldName) {
        return isset(static::$pattern) && isset(static::$pattern[$fieldName]) ? static::$pattern[$fieldName] : false;
    }

    public static function getTable() {
        return isset(static::$table) && static::$table ? static::$table : false;
    }

    public static function getSearchable() {
        return isset(static::$searchable) && static::$searchable ? static::$searchable : false;
    }

}