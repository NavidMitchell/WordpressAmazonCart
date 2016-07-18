{foreach from=$items item=item}

{include file='itemBrief.tpl'}

{/foreach}

<div style="width:100%;text-align:center;">
	{paginator currentPage=$currentPage totalPages=$totalPages baseURL=$baseURL}
</div>