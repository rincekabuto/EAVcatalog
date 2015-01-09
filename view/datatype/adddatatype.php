
	<div class='container-fluid'>
		<div class='row-fluid'>
            <div class='span8 offset4 well'>
            <h2>Добавление типа данных.</h2>
            <form method="post" action="" name='datatype'>
                <label>Имя</label>
                <input type='text' name='name' value=''/> 
                <fieldset class="datatype-prop">
                    <legend>Свойства</legend>
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
