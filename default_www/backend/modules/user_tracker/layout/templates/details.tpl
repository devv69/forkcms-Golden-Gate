{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblUserTracker|ucfirst}: {$lblVisitorData}</h2>
</div>

<div class="box">
	<div class="heading">
		<h3>{$lblSummary|ucfirst}</h3>
	</div>
	<div class="options">
		<p><strong>{$lblDateLastVisit|ucfirst}:</strong> {$visitor.lastVisit|formatdatetime}</p>
		<p><strong>{$lblNumberOfVisits|ucfirst}:</strong> {$visitor.numVisits}</p>
		<p><strong>{$lblNumberOfPageviews|ucfirst}:</strong> {$visitor.numPageviews}</p>
		<p><strong>{$lblNumberOfActions|ucfirst}:</strong> {$visitor.numActions}</p>
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
				<div class="pageTitle">
					<h2>{$visitor.sessions.dateStart|date:'Y-m-d H:i'} =&gt; {$visitor.sessions.dateStop|date:'Y-m-d H:i'}</h2>
					<div class="buttonHolderRight">
						<a href="#" class="button icon iconEdit" id="replayBrowserLink{$visitor.sessions.counter}"><span>Replay</span></a>
					</div>
				</div>

				<div>
					{option:!visitor.sessions.referrerHost}<p><strong>{$lblReferrer|ucfirst}</strong>: {$lblUnknown}</p>{/option:!visitor.sessions.referrerHost}
					{option:visitor.sessions.referrerHost}
						<p><strong>{$lblReferrer|ucfirst}</strong>: <a href="http://{$visitor.sessions.referrerHost}{option:visitor.sessions.referrerPath}{$visitor.sessions.referrerPath}{/option:visitor.sessions.referrerPath}{option:visitor.sessions.referrerQuery}?{$visitor.sessions.referrerQuery}{/option:visitor.sessions.referrerQuery}">http://{$visitor.sessions.referrerHost}{option:visitor.sessions.referrerPath}{$visitor.sessions.referrerPath}{/option:visitor.sessions.referrerPath}{option:visitor.sessions.referrerQuery}{$visitor.sessions.referrerQuery}{/option:visitor.sessions.referrerQuery}</a></p>
					{/option:visitor.sessions.referrerHost}
				</div>

				<div class="datagridHolder">
					<table class="datagrid" cellspacing="0" cellpadding="0" border="0">
						<thead>
							<tr>
								<th class="date">
									<span>{$lblDate|ucfirst}</span>
								</th>
								<th>
									<span>{$lblURL|ucfirst}</span>
								</th>
								<th class="action">
									<span>{$lblStatus|ucfirst}</span>
								</th>
							</tr>
						</thead>
						<tbody>
							{iteration:visitor.sessions.visits}
								<tr class="{cycle:'odd':'even'}">
									<td class="date">
										{$visitor.sessions.visits.date|formatdatetime}
									</td>
									<td class="url">
										<a href="{$visitor.sessions.visits.url}">{$visitor.sessions.visits.url}</a>
									</td>
									<td class="status">
										{$visitor.sessions.visits.status}
									</td>
								</tr>
							{/iteration:visitor.sessions.visits}
						</tbody>
					</table>
				</div>
			{/iteration:visitor.sessions}
		</div>
	</div>
{/option:visitor.sessions}

<div id="replayWindow"></div>

{option:visitor.sessions}
	{iteration:visitor.sessions}
		<script type="text/javascript">
		jQuery(function($){
			$('#replayBrowserLink{$visitor.sessions.counter}').click(function() {
				replayBrowser.init(
					[{iteration:visitor.sessions.visits}{$visitor.sessions.visits.url|jsonencode}{option:!visitor.sessions.visits.last},{/option:!visitor.sessions.visits.last}{/iteration:visitor.sessions.visits}],
					[{iteration:visitor.sessions.visits}{$visitor.sessions.visits.date|jsonencode}{option:!visitor.sessions.visits.last},{/option:!visitor.sessions.visits.last}{/iteration:visitor.sessions.visits}]
				);
				return false;
			});
		});
		</script>
	{/iteration:visitor.sessions}
{/option:visitor.sessions}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}