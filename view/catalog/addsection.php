
	<div class='container-fluid'>
		<div class='row-fluid'>
            <div class='span8 offset4 well'>
            <h2>Добавление раздела</h2>
            <form method="post" action="" name='datatype'>
                <label>Имя</label>
                <input type='hidden' name='id_structure_parent' value="<?php print $parentSection;?>"/>
                <input type='text' name='name' value=''/><br/>
                <input class="btn" type="submit" value="Сохранить" />
            </form>
            </div>
		</div>
	</div>
