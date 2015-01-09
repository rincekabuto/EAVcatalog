<?php

namespace model;

class Datatype {
    protected $dbAdapter;
    
    public function __construct() {
        $this->dbAdapter = \library\DB::getAdapter();
    }
    
    public function fetchAll(){
        $dba = $this->dbAdapter;
        $result = $dba->query('SELECT * FROM datatype')
                ->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function getById($id){
        $dba = $this->dbAdapter;
        $result = $dba->query('SELECT * FROM datatype WHERE id_datatype ='.$dba->quote($id))
                ->fetchAll(\PDO::FETCH_ASSOC);
        if(isset($result[0])) return $result[0];
    }
    
    public function getByName($name){
        $dba = $this->dbAdapter;
        $result = $dba->query('SELECT * FROM datatype WHERE name ='.$dba->quote($name))
                ->fetchAll(\PDO::FETCH_ASSOC);
        if(isset($result[0])) return $result[0];
    }
    
    /**
     * 
     * @param type $data = array(
     *      'name'
     *      'fields' = array(
     *          0 = 'name'
     *      )
     * )
     */
    public function create($data){
       $dba = $this->dbAdapter;
       $dba->exec('INSERT INTO `datatype` (`name`) VALUES ('.$dba->quote($data['name']).');');
       $datatypeId = $dba->lastInsertId();
       foreach ($data['fields'] as $n => $field){
           $query = 'INSERT INTO `item_attribute` (`name`, `id_datatype`) 
               VALUES ('.$dba->quote($field).', '.$dba->quote($datatypeId).');';
           $dba->exec($query);
       }
       return $datatypeId;
    }
    
    public function update($data){
        $dba = $this->dbAdapter;
        $query = 'UPDATE `datatype` 
            SET `name`='.$dba->quote($data['name']).' WHERE `id_datatype`='.$dba->quote($data['id_datatype']).';';
        $dba->exec($query);
        
        $itemModel = new \model\Item();
        $existAttr = $itemModel->getFieldsByDatatypeId($data['id_datatype']);
        foreach ($existAttr as $n => $attr){
            $persist = false;
            foreach ($data['old'] as $id_item_attribute => $name){
                if($id_item_attribute == $attr['id_item_attribute']) $persist = true;
            }
            if(!$persist){
                $query = 'DELETE FROM `projet2`.`item_attribute` 
                    WHERE `id_item_attribute`='.$attr['id_item_attribute'].';';
                $dba->exec($query);
            }
        }
        foreach ($data['old'] as $id_item_attribute => $name) {
            $query = 'UPDATE `item_attribute` 
                SET `name`='.$dba->quote($name).' WHERE `id_item_attribute`='.$dba->quote($id_item_attribute).';';
            $dba->exec($query);
        }
        if(isset($data['new'])){
            foreach ($data['new'] as $n => $name){
                $query = 'INSERT INTO `item_attribute` (`name`, `id_datatype`) 
                    VALUES ('.$dba->quote($name).', '.$dba->quote($data['id_datatype']).');';
                $dba->exec($query);
            }
        }
        
    }
    
    public function delete($id){
        $id = (int) $id;
        //WILD HACK
        $dba = $this->dbAdapter;
        $query = 'SELECT id_structure FROM item WHERE id_datatype = '.$dba->quote($id);
        $structureItems = $dba->query($query)->fetchAll();
        $structureModel = new \model\Structure();
        foreach ($structureItems as $item){
            $structureModel->delete($item['id_structure']);
        }
        $query = 'DELETE FROM `datatype` WHERE `id_datatype`='.$id.';';
        $dba->exec($query);
    }
}

?>
