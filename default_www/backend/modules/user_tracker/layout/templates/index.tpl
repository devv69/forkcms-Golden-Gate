{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblUserTracker|ucfirst}:</h2>
</div>

<div class="datagridHolder">
	{form:filter}
		{*<div class="dataFilter">
			<table cellspacing="0" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td>
							<div class="options">
								<p>
									<label for="language">{$lblLanguage|ucfirst}</label>
									{$ddmLanguage} {$ddmLanguageError}
								</p>
								<p>
									<label for="application">{$lblApplication|ucfirst}</label>
									{$ddmApplication} {$ddmApplicationError}
								</p>
							</div>
						</td>
						<td>
							<div class="options">
								<p>
									<label for="module">{$lblModule|ucfirst}</label>
									{$ddmModule} {$ddmModuleError}
								</p>
								<p>
									<label for="type">{$lblType|ucfirst}</label>
									{$ddmType} {$ddmTypeError}
								</p>
							</div>
						</td>
						<td>
							<div class="options">
								<div class="oneLiner">
									<p>
										<label for="name">{$lblReferenceCode|ucfirst}</label>
									</p>
									<p>
										<abbr class="help">(?)</abbr>
										<span class="tooltip" style="display: none;">
											{$msgHelpName}
										</span>
									</p>
								</div>
								{$txtName} {$txtNameError}

								<div class="oneLiner">
									<p>
										<label for="value">{$lblValue|ucfirst}</label>
									</p>
									<p>
										<abbr class="help">(?)</abbr>
										<span class="tooltip" style="display: none;">
											{$msgHelpValue}
										</span>
									</p>
								</div>
								{$txtValue} {$txtValueError}

							</div>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="99">
							<div class="options">
								<div class="buttonHolder">
									<input id="search" class="inputButton button mainButton" type="submit" name="search" value="{$lblUpdateFilter|ucfirst}" />
								</div>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>*}
	{/form:filter}

	{option:datagrid}
		{$datagrid}
	{/option:datagrid}
</div>

{option:!datagrid}
	<div class="tableHeading">
		<h3>{$lblUserTracker|ucfirst}</h3>
	</div>
{/option:!datagrid}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}
