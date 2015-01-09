<?php 
$printSections = function($sections, $parent = null) use(&$printSections, &$activeSection){?>
    <ul>
    <?php foreach ($sections as $n => $section) : 
            if($section['id_structure_parent'] === $parent) : ?>
            <li>
                <span class='menu'>
                    <a <?php if($section['id_structure'] == $activeSection) print "class='active'"?> href='/catalog/index/<?php print $section['id_structure'];?>'><?php print $section['name'];?></a>
                    <span class='controls'>
                        <a href='/catalog/editsection/<?php print $section['id_structure'];?>' title='Редактировать'><i class='icon-edit'></i></a>
                        <?php if($parent): ?>
                            <a href='/catalog/deletesection/<?php print $section['id_structure'];?>' title='Удалить'><i class='icon-remove'></i></a>
                        <?php endif?>
                        <a href='/catalog/addsection/<?php print $section['id_structure'];?>' title='Добавить подраздел'><i class='icon-plus'></i></a>
                    </span>
                </span>
                <?php $printSections($sections, $section['id_structure']); ?>
            </li>
    <?php endif; 
    endforeach;?>
    </ul>
<?php }
?>
	<div class='container-fluid'>
        <div class='row-fluid'>
            <div class="navbar span8 offset4">
                <div class="navbar-inner">
                    <ul class="nav">
                        <li class="active"><a href="/catalog/">Каталог</a></li>
                        <li><a href="/datatype/">Типы данных</a></li>
                    </ul>
                </div>
            </div>
        </div>
		<div class='row-fluid'>
			<div class='span4 well sections'>
				<h4>Каталог</h4>
                <?php $printSections($structure); ?>
			</div>
			<div class='span8 well items'>
                <?php foreach ($items as $n => $item) : ?>
                    <div class='well'>
                        <span class='lead'><?php print $item['item']['name'];?></span>&nbsp;
                        <span class='lead'>:<?php print $item['item']['datatype_name'];?></span>
                        <div class="btn-group">
                            <a href='/catalog/editelement/<?php print $item['item']['id_item']?>' class="btn btn-mini"><i class='icon-edit'></i></a>
                            <a href='/catalog/deleteelement/<?php print $item['item']['id_item']?>' class="btn btn-mini"><i class='icon-remove'></i></a>
                        </div>
                        <?php foreach ($item['properties'] as $j => $prop) : ?>
                            <div>
                                <b><?php print $prop['attribute_name']?>:</b>&nbsp;
                                <span><?php print $prop['value']?></span>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php endforeach;?>
                <div class='input-append'>
                    <select class="select-datatype-item">
                        <?php foreach ($datatypes as $n => $datatype) : ?>
                        <option value='<?php print $datatype['id_datatype']; ?>'><?php print $datatype['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <a href ="/catalog/addelement/<?php print $datatypes[0]['id_datatype'];?>/<?php print $activeSection;?>" class='btn add-datatype-item'>Добавить</a>
                </div></br>
                <?php if($pages > 1) :?>
                    <div class="pagination">
                    <ul>
                        <?php for ($i = 1; $i <= $pages; $i++) : ?>
                            <li <?php if($i === $activePage) print "class='active'" ;?>>
                                <a href="/catalog/index/<?php print $activeSection;?>/page<?php print $i; ?>"><?php print $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                    </div>
                <?php endif;?>
			</div>
		</div>
	</div>
    <script type="text/javascript">
		$(function(){
			$('.sections .menu').hover(
                function(){
                   fade = $('> .controls',this);
                   if($(fade).is(':animated')){
                       $(fade).stop().fadeTo(300,1);
                   }else{
                       $(fade).fadeIn(300);
                   }
                },
                function(){
                   fade = $('> .controls',this);
                   if($(fade).is(':animated')){
                       $(fade).stop().fadeTo(300,0);
                   }else{
                       $(fade).fadeOut(300);
                   }
                }
            );
            activeSection = <?php print $activeSection; ?>;
            $('.select-datatype-item').on('change',function(){
                href = '/catalog/addelement/'+$(this).val()+'/'+activeSection;
                $('.add-datatype-item').attr('href',href);
            });
            $('.controls a').tooltip();
		});
	</script>
