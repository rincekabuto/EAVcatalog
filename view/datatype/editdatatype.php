
	<div class='container-fluid'>
		<div class='row-fluid'>
            <div class='span8 offset4 well'>
            <h2>Редактирование типа данных.</h2>
            <form method="post" action="" name='datatype'>
                <label>Имя</label>
                <input type='text' name='name' value='<?php print $datatype['name'];?>'/> 
                <fieldset class="datatype-prop">
                    <legend>Свойства</legend>
                    <?php foreach ($fields as $n => $field) :?>
                        <div class='input-append'>
                            <input type='text' name='old[<?php print $field['id_item_attribute'];?>]' value="<?php print $field['name'];?>"/>
                            <a class="btn"><i class="icon-remove"></i></a>
                        </div><br/>
                    <?php endforeach;?>
                    <a class="btn add-prop"><i class="icon-plus"></i></a>
                </fieldset>
                <input class="btn" type="submit" value="Сохранить" />
            </form>
            </div>
		</div>
	</div>
    <script type="text/javascript">
		$(function(){
            n = 1;
			$('.add-prop').on('click',function(){
                content = "<div class='input-append'>\n\
                        <input type='text' name='new["+n+"]' value=''/>\n\
                        <a class='btn'><i class='icon-remove'></i></a>\n\
                        </div><br/>";
                $(this).before(content);
                n++;
            });
            $('.datatype-prop').delegate('.input-append a','click',function(){
                div = $(this).parent('div.input-append');
                br = $(this).parent('div.input-append').find('+ br');
                $(div).remove();
                $(br).remove();
            });
		})
	</script>
