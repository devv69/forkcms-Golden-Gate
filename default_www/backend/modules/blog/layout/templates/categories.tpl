{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblBlog|ucfirst}: {$lblCategories}</h2>
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add_category'}" class="button icon iconAdd"><span>{$lblAddCategory|ucfirst}</span></a>
	</div>
</div>

{option:datagrid}
	<div class="datagridHolder">
		{$datagrid}
	</div>
{/option:datagrid}
{option:!datagrid}<p>{$msgNoCategoryItems|sprintf:{$var|geturl:'add_category'}}</p>{/option:!datagrid}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}