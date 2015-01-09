
	<div class='container-fluid'>
		<div class='row-fluid'>
            <div class='span8 offset4 well'>
            <h2>Добавление элемента<small> : <?php print $datatype['name'];?></small></h2>
            <form method="post" action="" name='datatype'>
                <label>Имя</label>
                <input type='hidden' name='id_datatype' value="<?php print $datatype['id_datatype'];?>"/>
                <input type='hidden' name='id_structure_parent' value="<?php print $parentSection;?>"/>
                <input type='text' name='name' value=''/> 
                <fieldset class="datatype-prop">
                    <legend>Свойства</legend>
                    <?php foreach ($fields as $n => $element) :?>
                        <label><?php print $element['name'];?></label>
                        <input type='text' name='fields[<?php print $element['id_item_attribute'];?>]' value=""/>
                    <?php endforeach;?>
                </fieldset>
                <input class="btn" type="submit" value="Сохранить" />
                
            </form>
            </div>
		</div>
	</div>
