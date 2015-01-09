
	<div class='container-fluid'>
		<div class='row-fluid'>
            <div class='span8 offset4 well'>
            <h2>Редактирование элемента<small> : <?php print $item['item']['datatype_name'];?></small></h2>
            <form method="post" action="" name='datatype'>
                <label>Имя</label>
                <input type='text' name='name' value='<?php print $item['item']['name']; ?>'/> 
                <fieldset class="datatype-prop">
                    <legend>Свойства</legend>
                    <?php foreach ($itemFields as $n => $field) :?>
                        <label><?php print $field['name'];?></label>
                        <?php $value = null;
                        foreach ($item['properties'] as $n => $prop) {
                            if($prop['id_item_attrubute'] === $field['id_item_attribute']){
                                $value = $prop['value'];
                            }
                        }?>
                        <input type='text' name='fields[<?php print $field['id_item_attribute'];?>]' value="<?php print $value ?>"/>
                    <?php endforeach;?>
                </fieldset>
                <input class="btn" type="submit" value="Сохранить" />
                
            </form>
            </div>
		</div>
	</div>
