<?php

namespace controller;

class Catalog extends AbstractController{

    public function indexAction($params){
        $itemsPerPage = ITEMS_PER_PAGE;
        $structureId = isset($params[0]) ? (int) $params[0] : ROOT_SECTION_ID;
        $page = isset($params[1]) ? (int) str_replace('page', '', $params[1]) : 1;
        
        $structureModel = new \model\Structure();
        $itemModel = new \model\Item();
        $datatypeModel = new \model\Datatype();
        
        $itemsNumber = $structureModel->countByParent($structureId,'item');
        $pagesNumber = ceil($itemsNumber / $itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;
        if(!$structureModel->getById($structureId)){
            throw new \Exception('Section not found');
        }
        
        $structure = $structureModel->fetchAll('section');
        $structureItems = $structureModel->fetchAllByParent($structureId,'item',$itemsPerPage, $offset);
        
        $items = array();
        foreach ($structureItems as $n => $item) {
            $items[] = $itemModel->getByStructureId($item['id_structure']);
        }
        
        $dattatypes = $datatypeModel->fetchAll();
        
        echo $this->render('index',array(
            'activeSection' => $structureId,
            'structure'=>$structure, 
            'items'=>$items,
            'datatypes' => $dattatypes,
            'activePage' => $page,
            'pages' => $pagesNumber,
        ));
    }
    
    public function addelementAction($params){
        $datatypeId = isset($params[0]) ? (int) $params[0] : null;
        if(!$datatypeId){
            throw new \Exception('Datatype not set');
        }
        $parentSection = isset($params[1]) ? (int) $params[1] : ROOT_SECTION_ID;
        
        $datatypeModel = new \model\Datatype();
        $datatype = $datatypeModel->getById($datatypeId);
        if(!$datatype){
            throw new \Exception('Datatype not found');
        }
        $itemModel = new \model\Item();
        $datatypeFields = $itemModel->getFieldsByDatatypeId($datatypeId);
        
        if($_POST){
            $data = array();
            $data['id_datatype'] = $_POST['id_datatype'];
            $data['name'] = $_POST['name'];
            $data['id_structure_parent'] = $_POST['id_structure_parent'];
            foreach ($_POST['fields'] as $id_item_attribute => $field){
                $data['properties'][] = array(
                    'id_item_attribute' => $id_item_attribute,
                    'value' => $field
                );
            }
            $itemModel->create($data);
            header('Location: /catalog/index/'.$parentSection);
        }
        $templateParams = array(
            'datatype' => $datatype, 
            'fields' => $datatypeFields,
            'parentSection' => $parentSection
        );
        echo $this->render('addelement',$templateParams);
    }
    
    public function editelementAction($params){
        $itemId = isset($params[0]) ? (int) $params[0] : null;
        if(!$itemId){
            throw new \Exception('Item id not set');
        }
        $itemModel = new \model\Item();
        $item = $itemModel->getById($itemId);
        $itemFields = $itemModel->getFieldsByDatatypeId($item['item']['id_datatype']);
        
        if($_POST){
//            print_r($_POST);
            $data = array(
                'id_item' => $itemId,
                'name' => $_POST['name'],
                'id_structure_parent' => $item['item']['id_structure_parent']
            );
            foreach ($_POST['fields'] as $id_item_attribute => $value){
                $data['properties'][] = array(
                    'id_item_attribute' => $id_item_attribute,
                    'value' => $value,
                );
            }
            $itemModel->update($data);
            header('Location: /catalog/index/'.$item['item']['id_structure_parent']);
        }
        $templateParams = array(
            'item' => $item,
            'itemFields' => $itemFields
        );
        echo $this->render('editelement',$templateParams);
    }
    
    public function deleteelementAction($params){
        $itemId = isset($params[0]) ? (int) $params[0] : null;
        if(!$itemId){
            throw new \Exception('Item id not set');
        }
        $itemModel = new \model\Item();
        $item = $itemModel->getById($itemId);
        if(isset($_POST['yep'])){
            $itemModel->delete($itemId);
            header('Location: /catalog/index/'.$item['item']['id_structure_parent']);
        }
        if(isset($_POST['nope'])){
            header('Location: /catalog/index/'.$item['item']['id_structure_parent']);
        }
        echo $this->render('deleteelement');
    }
    
    public function addsectionAction($params){
        $itemId = isset($params[0]) ? (int) $params[0] : null;
        if(!$itemId){
            throw new \Exception('Item id not set');
        }
        $structureModel = new \model\Structure();
        if($_POST){
            $data = array(
                'id_structure_parent' => $_POST['id_structure_parent'],
                'name' => $_POST['name'],
                'type' => 'section'
            );
            $id = $structureModel->create($data);
            header('Location: /catalog/index/'.$id);
        }
        echo $this->render('addsection',array('parentSection'=>$itemId));
    }
    
    public function editsectionAction($params){
        $itemId = isset($params[0]) ? (int) $params[0] : null;
        if(!$itemId){
            throw new \Exception('Item id not set');
        }
        $structureModel = new \model\Structure();
        $section = $structureModel->getById($itemId);
        if($_POST){
            $data = array(
                'id_structure' => $itemId,
                'id_structure_parent' => $_POST['id_structure_parent'],
                'name' => $_POST['name'],
                'type' => 'section',
            );
            $structureModel->update($data);
            header('Location: /catalog/index/'.$data['id_structure_parent']);
        }
        echo $this->render('editsection',array('section'=>$section));
    }
    
    public function deletesectionAction($params){
        $itemId = isset($params[0]) ? (int) $params[0] : null;
        if(!$itemId){
            throw new \Exception('Item id not set');
        }
        if($itemId === ROOT_SECTION_ID){
            throw new \Exception('Can not delete root section');
        }
        $structureModel = new \model\Structure();
        $section = $structureModel->getById($itemId);
        if(isset($_POST['yep'])){
            $structureModel->delete($itemId);
            header('Location: /catalog/index/'.$section['id_structure_parent']);
        }
        if(isset($_POST['nope'])){
            header('Location: /catalog/index/'.$section['id_structure']);
        }
        echo $this->render('deletesection');
    }
    

}

?>
