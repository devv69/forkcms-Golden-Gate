{option:languages}
	<ul>
		{iteration:languages}
			<li{option:languages.current}{option:!languages.first} class="selected"{/option:!languages.first}{/option:languages.current}{option:!languages.current}{option:languages.first} class="firstChild"{/option:languages.first}{/option:!languages.current}{option:languages.current}{option:languages.first} class="selected firstChild"{/option:languages.first}{/option:languages.current}>
				<a href="{$languages.url}"><span class="icon worldIcon"></span><span class="iconWrapper">{$languages.label}</span><span class="icon dropdownIcon"></span></a>
			</li>
		{/iteration:languages}
	</ul>
{/option:languages}