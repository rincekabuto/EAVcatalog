<?php

namespace controller;

class Datatype extends AbstractController{
    public function indexAction(){
        $datatypeModel = new \model\Datatype();
        $datatypes = $datatypeModel->fetchAll();
        
        echo $this->render('index',array('datatypes' => $datatypes));
    }
    public function adddatatypeAction(){
        if($_POST){
            $data = array(
                'name' => $_POST['name']
            );
            foreach ($_POST['new'] as $n => $fieldName){
                $data['fields'][] = $fieldName;
            }
            $datatypeModel = new \model\Datatype();
            $datatypeModel->create($data);
            header('Location: /datatype/');
        }
        echo $this->render('adddatatype');
    }
    public function editdatatypeAction($params){
        $datatypeId = isset($params[0]) ? (int) $params[0] : null;
        if(!$datatypeId){
            throw new \Exception('Datatype not set');
        }
        $datatypeModel = new \model\Datatype();
        $itemModel = new \model\Item();
        
        $datatype = $datatypeModel->getById($datatypeId);
        if(!$datatype){
            throw new \Exception('Datatype not found');
        }
        $fields = $itemModel->getFieldsByDatatypeId($datatypeId);
        
        if($_POST){
            $data = $_POST;
            $data['id_datatype'] = $datatypeId;
            $datatypeModel->update($data);
            header('Location: /datatype/');
        }
        
        echo $this->render('editdatatype', array(
            'datatype' => $datatype,
            'fields' => $fields,
        ));
    }
    public function deletedatatypeAction($params){
        $datatypeId = isset($params[0]) ? (int) $params[0] : null;
        if(!$datatypeId){
            throw new \Exception('Datatype not set');
        }
        if(isset($_POST['yep'])){
            $datatypeModel = new \model\Datatype();
            $datatypeModel->delete($datatypeId);
            header('Location: /datatype/');
        }
        if(isset($_POST['nope'])){
            header('Location: /datatype/');
        }
        echo $this->render('deletedatatype');
    }
}

?>
