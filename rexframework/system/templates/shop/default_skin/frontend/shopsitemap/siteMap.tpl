<div class="block">
	{if $staticpage}
		<div>
			<h2>{$staticpage->name}</h2>
			<p>{$staticpage->content}</p>
		</div>
	{/if}

	<h3>Страницы</h3>
	<ul>
		{if $staticpage_list}
			{foreach from=$staticpage_list key=key item=item}
			    <li><a href="{url mod=staticPage act=default task=$item.alias}">{$item.name}</a></li>
			{/foreach}
		{/if}
	</ul>
	<h3>Разделы</h3>
	<ul>
        <li><a class="menu" href="{url mod=events act=archive}">Акции</a></li>
        <li><a class="menu" href="{url mod=news act=archive}">Новости</a></li>
        <li><a class="menu" href="{url mod=home act=siteMap}">Карта сайта</a></li>
        <li><a href="{url mod=home act=contact}">Обратный звонок</a></li>
        <li><a href="{url route=shop_complaint}">Пожаловаться директору</a></li>
	</ul>
    
    
{assign var=level value=-1}
{foreach from=$treeList item=catMenuP key=keyP}

    {if $catMenuP->level eq 0} {*top level*}
<div style="text-align: left; border: 4px solid #4DCEC3; margin: 0px 6px 6px;" >
        <div id="m{$catMenuP->id}" class="ma">
            {if $catMenuP->level neq $level and $level > 0} {*close menu*}
                <br/><br/>
            {/if}
            <div class="catalog_name_preview">                        
                <a class="lmenu" href="{url mod=pCatalog act=default task=$catMenuP->alias}" >{$catMenuP->name}</a>
            </div>
        </div>
    
    {foreach from=$treeList item=catMenu key=key}
        {if $catMenuP->id eq $catMenu->pid} {*sub level*}
            <div id="{$catMenu->id}sub-m{$catMenu->pid}" style="text-align: left; margin: 0px 6px 6px;" class="sub-m{$catMenu->pid}">
            {*<br/><a class="lmenu" href="{url mod=pCatalog act=default task=$catMenu->alias}" >{$catMenu->name}</a>*}
            <li style="list-style: disc !important; margin-left: 10px; text-align: left; text-decoration: underline;"><a class="lmenu" href="{url mod=pCatalog act=default task=$catMenu->alias}">{$catMenu->name}</a></li>
                {foreach from=$treeList item=catMenuC key=keyC}
                    {if $catMenu->id eq $catMenuC->pid}
                        <ul class="submenu_3">
                            <div id="{$catMenuC->id}sub-m{$catMenuC->pid}" class="sub-me{$catMenu->pid}">
                            <li style="list-style: disc !important; margin-left: 50px; text-align: left; text-decoration: underline;"><a class="lmenu" href="{url mod=pCatalog act=default task=$catMenuC->alias}">{$catMenuC->name}</a></li>
                            </div>
                        </ui>
                    {/if}
                {/foreach}
            </div>
        {/if}
    {/foreach}
    </div>
    {/if}
    
{/foreach}
    
    
    
</div>