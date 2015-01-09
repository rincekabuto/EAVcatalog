<?php

namespace model;

class Item {
    protected $dbAdapter;
    
    public function __construct() {
        $this->dbAdapter = \library\DB::getAdapter();
    }
    
    public function getByStructureId($id){
        $dba = $this->dbAdapter;
        $item = $dba->query('
            SELECT item.*, datatype.name as datatype_name, structure.id_structure_parent 
            FROM item 
            JOIN datatype ON datatype.id_datatype = item.id_datatype
            JOIN structure ON structure.id_structure = item.id_structure
            WHERE item.id_structure = '.$dba->quote($id))
            ->fetchAll(\PDO::FETCH_ASSOC);
        $item =  isset($item[0]) ? $item[0] : null ;
        $itemProperties = $dba->query('
            SELECT item_attribute_value.*, item_attribute.name as attribute_name
            FROM item_attribute_value
            JOIN item_attribute 
            ON item_attribute.id_item_attribute = item_attribute_value.id_item_attrubute
            WHERE item_attribute_value.id_item = '.$dba->quote($item['id_item']))
            ->fetchAll(\PDO::FETCH_ASSOC);
        return array('item' => $item, 'properties'=>$itemProperties);
    }


    public function getById($id){
        $dba = $this->dbAdapter;
        $item = $dba->query('
            SELECT item.*, datatype.name as datatype_name, structure.id_structure_parent 
            FROM item 
            JOIN datatype ON datatype.id_datatype = item.id_datatype
            JOIN structure ON structure.id_structure = item.id_structure
            WHERE item.id_item = '.$dba->quote($id))
            ->fetchAll(\PDO::FETCH_ASSOC);
        $item =  isset($item[0]) ? $item[0] : null ;
        $itemProperties = $dba->query('
            SELECT item_attribute_value.*, item_attribute.name as attribute_name
            FROM item_attribute_value
            JOIN item_attribute 
            ON item_attribute.id_item_attribute = item_attribute_value.id_item_attrubute
            WHERE item_attribute_value.id_item = '.$dba->quote($id))
            ->fetchAll(\PDO::FETCH_ASSOC);
        return array('item' => $item, 'properties'=>$itemProperties);
    }
    
    public function getFieldsByDatatypeId($id){
        $dba = $this->dbAdapter;
        return $dba->query('SELECT * FROM item_attribute WHERE id_datatype = '.$dba->quote($id))
                ->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * 
     * @param type $data = array(
     *      'id_datatype',
     *      'name',
     *      'id_structure_parent',
     *      'properties' => array('0' => array('id_item_attribute','value'))
     * )
     */
    public function create($data){
        $dba = $this->dbAdapter;
        $id_datatype = isset($data['id_datatype']) ? $dba->quote($data['id_datatype']) : '\'\'';
        $name = isset($data['name']) ? $dba->quote($data['name']) : '\'\'';
        $id_structure_parent = isset($data['id_structure_parent']) ? $dba->quote($data['id_structure_parent']) : '\'\'';
        $properties = array();
        foreach ($data['properties'] as $n => $element){
            $properties[$element['id_item_attribute']] = $element['value'];
        }
        $structureModel = new \model\Structure();
        $structure = array(
            'id_structure_parent' => $data['id_structure_parent'],
            'type'=>'item'
        );
        $id_structure = $structureModel->create($structure);
        
        $query = 'INSERT INTO `item` (`id_datatype`, `name`, `id_structure`) 
            VALUES ('.$id_datatype.', '.$name.', '.$id_structure.');';
        $dba->exec($query);
        $id_item = $dba->lastInsertId();
        foreach ($properties as $id_item_attribute => $value) {
            $query = 'INSERT INTO `item_attribute_value` (`id_item_attrubute`, `value`, `id_item`) 
                VALUES ('.$id_item_attribute.', '.$dba->quote($value).', '.$id_item.');';
            $dba->exec($query);
        }
    }
    
    /**
     * 
     * @param type $data = array(
     *      'id_item',
     *      'name',
     *      'id_structure_parent',
     *      'properties' => array('0' => array('id_item_attribute','value'))
     * )
     */
    public function update($data){
        $dba = $this->dbAdapter;
        $id_item = isset($data['id_item']) ? $dba->quote($data['id_item']) : '\'\'';
        $name = isset($data['name']) ? $dba->quote($data['name']) : '\'\'';
        $id_structure_parent = isset($data['id_structure_parent']) ? $dba->quote($data['id_structure_parent']) : '\'\'';
        
        $properties = array();
        foreach ($data['properties'] as $n => $element){
            $properties[$element['id_item_attribute']] = $element['value'];
        }
        
        $item = $this->getById($data['id_item']);
        $id_structure = $item['item']['id_structure'];
        $existProperties = $item['properties'];
        $structureModel = new \model\Structure();
        $structure = array(
            'id_structure'=>$id_structure,
            'id_structure_parent'=>$data['id_structure_parent'],
            'type'=>'item'
        );
        $id_structure = $structureModel->update($structure);
        
        $query = 'UPDATE `item` SET `name`='.$name.' WHERE `id_item`='.$id_item.';';
        $dba->exec($query);
        
        foreach ($properties as $n => $prop) {
            $exist = false;
            foreach ($existProperties as $j => $element) {
                if($element['id_item_attrubute'] == $n) $exist = $element['id_item_attribute_value'];
            }
            if($exist){
                
                $query = 'UPDATE `item_attribute_value` 
                    SET `id_item_attrubute` = '.$dba->quote($n).', 
                    `value` = '.$dba->quote($prop).', `id_item` = '.$id_item.' 
                    WHERE id_item_attribute_value = '.$dba->quote($exist);
                $dba->exec($query);
            }else{
                $query = 'INSERT INTO `item_attribute_value` (`id_item_attrubute`, `value`, `id_item`) 
                    VALUES ('.$dba->quote($n).', '.$dba->quote($prop).', '.$id_item.');';
                $dba->exec($query);
            }
        }
        return true;
    }
    
    public function delete($id){
        $dba = $this->dbAdapter;
        $id = (int) $id;
        
        $itemModel = new \model\Item();
        $item = $itemModel->getById($id);
        $id_structure = $item['item']['id_structure'];
        
        $query = 'DELETE FROM `structure` WHERE `id_structure`='.$dba->quote($id_structure).';';
        $dba->exec($query);
        
        $query = 'DELETE FROM `item` WHERE `id_item`='.$dba->quote($id).';';
        $dba->exec($query);
        
        return true;
    }
}

?>
