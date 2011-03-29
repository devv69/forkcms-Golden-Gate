{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblUserTracker|ucfirst}: {$lblVisitorData}</h2>

	{*<div class="buttonHolderRight">
		<a href="{$var|geturl:'data'}&amp;id={$formId}&amp;start_date={$filter.start_date}&amp;end_date={$filter.end_date}&amp;ip={$filter.ip}" class="button icon iconBack"><span>{$lblBackToData|ucfirst}</span></a>
	</div>*}
</div>

<div class="box">
	<div class="heading">
		<h3>{$lblSummary|ucfirst}</h3>
	</div>
	<div class="options">
		<p><strong>{$lblUniqueId|ucfirst}:</strong> {$visitor.identifier}</p>
		<p><strong>{$lblLastDataUpdate|ucfirst}:</strong> {$visitor.lastUpdate|formatdatetime}</p>
		<p><strong>{$lblLastVisit|ucfirst}:</strong> {$visitor.lastVisit|formatdatetime}</p>
	</div>
</div>

<div class="box">
	<div class="heading">
		<h3>{$lblVisitorDetails|ucfirst}</h3>
	</div>
	<div class="options">
		{option:visitor.data}
			{iteration:visitor.data}
				<p>
					<strong>{$visitor.data.name|tolabel}:</strong> {$visitor.data.list.0.value}
				</p>
			{/iteration:visitor.data}
		{/option:visitor.data}
	</div>
</div>

{option:visitor.sessions}
	<div class="box">
		<div class="heading">
			<h3>{$lblVisits|ucfirst}</h3>
		</div>
		<div class="options">
			{iteration:visitor.sessions}
				<strong>{$visitor.sessions.dateStart|date:'Y-m-d H:i'} =&gt; {$visitor.sessions.dateStop|date:'Y-m-d H:i'}</strong><br />
				<ul>
					{iteration:visitor.sessions.visits}
						<li>{$visitor.sessions.visits.date|formatdatetime} - {$visitor.sessions.visits.url} - {$visitor.sessions.visits.status}</li>
					{/iteration:visitor.sessions.visits}
				</ul><br />
			{/iteration:visitor.sessions}
		</div>
	</div>
{/option:visitor.sessions}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}