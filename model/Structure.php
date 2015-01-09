<?php

namespace model;

class Structure{
    protected $dbAdapter;

    public function __construct() {
        $this->dbAdapter = \library\DB::getAdapter();
    }

    public function fetchAll($type = null){
        $dba = $this->dbAdapter;
        $query = 'SELECT * FROM structure';
        if($type){
            $query .= ' WHERE type = '.$dba->quote($type);
        }
        return $dba->query($query)
                ->fetchAll(\PDO::FETCH_ASSOC);
	}
    
	public function getById($id){
        $id = (int) $id;
		$dba = $this->dbAdapter;
        $result = $dba->query('SELECT * FROM structure WHERE id_structure ='.$dba->quote($id))
                ->fetchAll(\PDO::FETCH_ASSOC);
        if(isset($result[0])) return $result[0];
	}
    
    public function countByParent($parentId, $type){
        $parentId = (int) $parentId;
        $dba = $this->dbAdapter;
        $query = 'SELECT COUNT(*) FROM structure WHERE id_structure_parent = '.$dba->quote($parentId);
        if($type){
            $query .= 'AND type = '.$dba->quote($type);
        }
        $result = $dba->query($query)->fetch();
        return $result[0];
    }
    
    public function fetchAllByParent($parentId, $type = 'section',$limit = null, $offset = null){
        $parentId = (int) $parentId;
        $dba = $this->dbAdapter;
        $query = 'SELECT * FROM structure 
            WHERE id_structure_parent ='.$dba->quote($parentId).'
            AND type = '.$dba->quote($type);
        if($limit){
            $query .= ' LIMIT '.$limit;
            if($offset) $query .= ' OFFSET '.$offset;
        }
        $result = $dba->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function create($data){
        $dba = $this->dbAdapter;
        $parent = isset($data['id_structure_parent']) ? $dba->quote($data['id_structure_parent']) : '\'\'';
        $name = isset($data['name']) ? $dba->quote($data['name']) : '\'\'';
        $type = isset($data['type']) ? $dba->quote($data['type']) : 'section';
        $query = "INSERT INTO `structure` 
            (`id_structure_parent`, `name`, `type`) 
            VALUES (".$parent.", ".$name.", ".$type.")";
        if($dba->exec($query) > 0){
            return $dba->lastInsertId();
        }
    }
    public function update($data){
        $dba = $this->dbAdapter;
        $id = isset($data['id_structure']) ? $dba->quote($data['id_structure']) : '\'\'';
        $parent = isset($data['id_structure_parent']) ? $dba->quote($data['id_structure_parent']) : '\'\'';
        $name = isset($data['name']) ? $dba->quote($data['name']) : '\'\'';
        $type = isset($data['type']) ? $dba->quote($data['type']) : 'section';
        $query = "UPDATE `structure` 
            SET `id_structure_parent`=".$parent.", 
            `name`=".$name.", `type`=".$type." 
            WHERE `id_structure`=".$id.";";
        return $dba->exec($query);
    }
    public function delete($id){
        $id = (int) $id;
        $dba = $this->dbAdapter;
        $query = 'DELETE FROM `structure` WHERE `id_structure`='.$dba->quote($id);
        return $dba->exec($query);
    }
}