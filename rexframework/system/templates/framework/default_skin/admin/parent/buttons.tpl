{if $mod eq 'pCatalog'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 160px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить категорию
        </a>
    </li>
</ul>
{elseif $mod eq 'attribute'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 140px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить атрибут
        </a>
    </li>
</ul>
{elseif $mod eq 'brand'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 132px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить бренд
        </a>
    </li>
</ul>
{elseif $mod eq 'technology'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 162px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить технологию
        </a>
    </li>
</ul>
{elseif $mod eq 'article'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 136px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить статью
        </a>
    </li>
</ul>
{elseif $mod eq 'news'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 142px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить новость
        </a>
    </li>
</ul>
{elseif $mod eq 'staticPage'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 148px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить страницу
        </a>
    </li>
</ul>
{elseif $mod eq 'slider'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 131px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить слайд
        </a>
    </li>
</ul>
{elseif $mod eq 'user'}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix" style="width: 131px;">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add" style="padding-right:10px;">
            <span class="ui-icon ui-icon-plusthick"></span>Добавить пользователя
        </a>
    </li>
</ul>
{else}
<ul id="icons" class="add_btn ui-widget ui-helper-clearfix">
    <li class="ui-state-default ui-corner-all">
        <a class="itemadd" href="javascript: void(0);" title="{$mod|capitalize} add">
            <span class="ui-icon ui-icon-plusthick"></span>
        </a>
    </li>
</ul>
{/if}