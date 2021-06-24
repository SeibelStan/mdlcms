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

    public static function getFields($obj = 0, $fillable = false) {
        $exemp = false;
        if ($obj) {
            if (is_object($obj)) {
                $exemp = $obj;
            }
            elseif (is_array($obj)) {
                $key = array_keys($obj)[0];
                $exemp = static::getByField($key, $obj[$key]);
            }
            else {
                $exemp = static::getByField('id', $obj);
            }
        }

        $fields = static::$fields;
        $prepfields = (object) [];
        foreach ($fields as $name => $value) {
            if ($fillable && !static::isFillable($name)) {
                continue;
            }

            $expVal = explode(':', $value);
            $defVal = @$expVal[1] ?: '';
            $type = $expVal[0];

            if ($defVal == 'NOW()') {
                $defVal = now();
            }

            if (isset(static::$inputTypes) && isset(static::$inputTypes[$name])) {
                $tareaClass = $exemp ? preg_match('/class=/', $exemp->$name) : false;
                if ($tareaClass) {
                    $control = 'textarea';
                }
                else {
                    $control = static::$inputTypes[$name];
                }
            }
            elseif (in_array($type, ['datetime', 'timestamp'])) {
                $control = 'datetime-local';
            }
            elseif (in_array($type, ['date'])) {
                $control = 'date';
            }
            elseif (preg_match('/int\(1\)/', $type)) {
                $control = 'checkbox';
            }
            elseif (preg_match('/(int|float)/', $type)) {
                $control = 'number';
            }
            elseif (preg_match('/(email)/', $name)) {
                $control = 'email';
            }
            elseif (preg_match('/varchar\(([2-9]\d{2}|\d{4})\)/', $type) || preg_match('/text/', $type)) {
                $control = 'textarea';
            }
            else {
                $control = 'text';
            }

            preg_match('/(\d+)/', $type, $matches);
            $maxlength = @$matches[1];

            $field = (object) [
                'name'      => $name,
                'title'     => static::getFieldTitle($name),
                'type'      => $type,
                'control'   => $control,
                'required'  => static::isRequired($name),
                'maxlength' => $maxlength,
                'value'     => $exemp ? htmlentities($exemp->$name) : $defVal
            ];

            $prepfields->$name = $field;
        }
        return $prepfields;
    }

    public static function getByField($fieldName, $value, $condition = false) {
        $parseName = explode('|', $fieldName);
        $fieldName = $parseName[0];
        $arg = isset($parseName[1]) ? $parseName[1] : '';

        switch ($arg) {
            case 'like': {
                $sql = static::$table . " where $fieldName like '%$value%'";
                break;
            }
            default: {
                $sql = static::$table . " where $fieldName = '$value'";
            }
        }

        if ($condition) {
            $sql .= " " . $condition;
        }
        $result = dbs($sql);
        if ($result) {
            array_reverse($result);
            $result = $result[0];
            if (isset($result->url) && !$result->url) {
                $result->url = $result->id;
            }
        }
        return $result;
    }

    public static function paginate($condition = false, $sort = false, $limit = 1, $page = 1) {
        global $db;
        $sql = "select count(id) as count from " . static::getTable();
        if ($condition) {
            $sql .= " where " . $condition;
        }
        if ($sort) {
            $sql .= " order by " . $sort;
        }
        $units = dbs($sql, 'raw');

        $count = $units[0]->count;
        if ($count <= $limit) {
            return [];
        }

        $result = [];
        $iPage = 1;
        for ($i = 1; $i <= $count; $i += $limit) {
            $_GET['page'] = $iPage;
            $params = [];
            foreach ($_GET as $k => $v) {
                $params[] = "$k=$v";
            }
            array_push($result, (object) [
                'link' => ROOT . static::getName() . '?' . implode('&', $params),
                'title' => $iPage,
                'active' => $iPage == $page,
            ]);
            $iPage++;
        }

        return $result;
    }

    public static function getUnits($condition = false, $sort = false, $limit = false, $page = false) {
        global $db;
        $sql = static::getTable();

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
            foreach (['id', 'login', 'full_name', 'title', 'name'] as $tryName) {
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

        if (!$data) {
            return false;
        }

        $fields = static::getFields();

        foreach ($fields as $k => $field) {
            if (!isset($data[$field->name]) || $field->type == 'virtual') {
                unset($fields->$k);
            }
            if (isset($data[$field->name])) {
                if (is_array($data[$field->name])) {
                    $data[$field->name] = array_pop($data[$field->name]);
                }
                if ($field->control == 'checkbox') {
                    $data[$field->name] = $data[$field->name] ? 1 : 0;
                }
            }
            if (preg_match('/date/', $field->type) && !@$data[$field->name]) {
                unset($data[$field->name]);
            }
        }

        $keys = is_array($id) ? $id : ['id' => $id];

        $keysWhere = [];
        foreach ($keys as $k => $v) {
            $keysWhere[] = "$k = '$v'";
        }
        $keysWhere = implode(' and ', $keysWhere);

        if (static::getUnits($keysWhere)) {
            $hasUpdate = false;
            $sql = "update " . static::getTable() . " set ";
            foreach ($fields as $field) {
                if (!isset($data[$field->name]) || $field->type == 'virtual') {
                    continue;
                }
                if ($field->type == 'float') {
                    $data[$field->name] = preg_replace('/,/', '.', $data[$field->name]);
                }
                if ($field->type == 'datetime' && preg_match('/1970-01-01/', $data[$field->name])) {
                    $data[$field->name] = 'NULL';
                }
                if ($data[$field->name] === '' || $data[$field->name] === 'NULL') {
                    $data[$field->name] = 'NULL';
                }
                else {
                    $data[$field->name] = "'" . $db->real_escape_string($data[$field->name]) . "'";
                }
                $sql .= "`$field->name` = " . $data[$field->name] . ", ";
                $hasUpdate = true;
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= " where ";
            $sql .= implode(' and ', $impWhere);

            if ($hasUpdate) {
                //echo $sql . "\n";
                $db->query($sql);
                $affected = $db->affected_rows;
            }
            else {
                $affected = 0;
            }

            if ($db->affected_rows) {
                foreach (['updated_at'] as $fieldName) {
                    if (isset(static::$fields[$fieldName]) && (!isset($data[$fieldName]) || $data[$fieldName] == 'NULL')) {
                        dbu(static::getTable() . " set $fieldName = '" . now() . "' where " . implode(' and ', $impWhere));
                    }
                }
            }

            return max(0, $affected);
        }
        else {
            $sql = "insert into " . static::getTable() . " (";
            foreach ($fields as $field) {
                if (!isset($data[$field->name]) || $field->type == 'virtual') {
                    continue;
                }
                $sql .= "`$field->name`, ";
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= ") VALUES (";
            foreach ($fields as $field) {
                if (!isset($data[$field->name]) || $field->type == 'virtual') {
                    continue;
                }
                if ($field->type == 'float') {
                    $data[$field->name] = preg_replace('/,/', '.', $data[$field->name]);
                }
                if ($field->type == 'datetime' && preg_match('/1970-01-01/', $data[$field->name])) {
                    $data[$field->name] = 'NULL';
                }
                if ($data[$field->name] === '' || $data[$field->name] === 'NULL') {
                    $data[$field->name] = 'NULL';
                }
                else {
                    $data[$field->name] = "'" . $db->real_escape_string($data[$field->name]) . "'";
                }
                $sql .= $data[$field->name] . ", ";
            }
            $sql = preg_replace('/,\s+$/', '', $sql);
            $sql .= ")";

            //echo $sql . "\n";
            $result = $db->query($sql);
            $insertId = (int) (string) $db->insert_id;

            if ($insertId) {
                foreach (['created_at', 'updated_at'] as $fieldName) {
                    if (isset(static::$fields[$fieldName]) && (!isset($data[$fieldName]) || $data[$fieldName] == 'NULL')) {
                        $sql = static::getTable() . " set $fieldName = '" . now() . "' where " . implode(' and ', $impWhere);
                        dbu($sql);
                    }
                }
            }

            return $insertId;
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
                $sql = $model::getTable() . " where";
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

    public static function checkPattern($pattern, $data) {
        return preg_match('/^' . static::getPattern($pattern)[0] . '$/', $data);
    }

    public static function getTable() {
        return isset(static::$table) && static::$table ? static::$table : false;
    }

    public static function getSearchable() {
        return isset(static::$searchable) && static::$searchable ? static::$searchable : false;
    }

}