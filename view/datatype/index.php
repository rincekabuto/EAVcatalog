
	<div class='container-fluid'>
        <div class='row-fluid'>
            <div class="navbar span8 offset4">
                <div class="navbar-inner">
                    <ul class="nav">
                        <li><a href="/catalog/">Каталог</a></li>
                        <li class="active"><a href="/datatype/">Типы данных</a></li>
                    </ul>
                </div>
            </div>
        </div>
		<div class='row-fluid'>
			<div class='span8 well offset4'>
                <h2>Типы данных каталога</h2>
                <?php foreach ($datatypes as $n => $datatype) :?>
                    <div class='datatype'>
                        <span class='lead'><?php print $datatype['name']; ?></span>&nbsp;
                        <a href='/datatype/editdatatype/<?php print $datatype['id_datatype'];?>' class="btn btn-mini"><i class='icon-edit'></i></a>
                        <a href='/datatype/deletedatatype/<?php print $datatype['id_datatype'];?>' class="btn btn-mini"><i class='icon-remove'></i></a>
                        <hr/>
                    </div>
                <?php endforeach;?>
                <a href='/datatype/adddatatype/' class='btn'>Добавить тип данных</a>
			</div>
		</div>
	</div>
