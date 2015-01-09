
	<div class='container-fluid'>
		<div class='row-fluid'>
            <div class='span8 offset4 well'>
            <h2>Редактирование раздела</h2>
            <form method="post" action="" name='datatype'>
                <label>Имя</label>
                <input type='hidden' name='id_structure_parent' value="<?php print $section['id_structure_parent'];?>"/>
                <input type='hidden' name='id_structure' value="<?php print $section['id_structure'];?>"/>
                <input type='text' name='name' value='<?php print $section['name'];?>'/><br/>
                <input class="btn" type="submit" value="Сохранить" />
            </form>
            </div>
		</div>
	</div>

