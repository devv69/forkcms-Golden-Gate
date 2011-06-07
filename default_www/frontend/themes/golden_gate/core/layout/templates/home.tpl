{include:core/layout/templates/head.tpl}

<body id="home" class="{$LANGUAGE}">
	<div id="topWrapper">
		<header id="header">
			<div class="container">

				{* Logo *}
				<div id="logo">
					<h1><a href="/">{$siteTitle}</a></h1>
				</div>

				{* Skip link *}
				<div id="skip">
					<p><a href="#main">{$lblSkipToContent|ucfirst}</a></p>
				</div>

				{* Navigation *}
				<nav id="headerNavigation">
					<h4>{$lblMainNavigation|ucfirst}</h4>
					{$var|getnavigation:'page':0:1}
				</nav>

				{* Language *}
				<nav id="headerLanguage">
					<h4>{$lblLanguage|ucfirst}</h4>
					{include:core/layout/templates/languages.tpl}
				</nav>

				{* Block 12 (default: Search widget) *}
				{option:!block12IsHTML}
					<div id="headerSearch">
						<h4>{$lblSearch|ucfirst}</h4>
						{include:{$block12}}
					</div>
				{/option:!block12IsHTML}

				{* Block 1 (default: Header editor) *}
				{option:block1IsHTML}
					{option:block1}
						<section id="headerFocus" class="content">
							{$block1}
							<div class="guillotineBugFix"></div>
						</section>
					{/option:block1}
				{/option:block1IsHTML}

			</div>
		</header>
		<div id="main">
			<div class="container">

				{* Page title *}
				{option:!hideContentTitle}
					<header class="mainTitle">
						<h2>{$page.title}</h2>
					</header>
				{/option:!hideContentTitle}

				{* Block 2 (default: Editor) *}
				{option:block2IsHTML}
					{option:block2}
						<section class="mod">
							<div class="inner">
								<div class="bd content">
									{$block2}
								</div>
							</div>
						</section>
					{/option:block2}
				{/option:block2IsHTML}
				{option:!block2IsHTML}
					{include:{$block2}}
				{/option:!block2IsHTML}

				{option:block3}{option:block4}{option:block5}
					<section class="mod"><div class="inner">
				{/option:block5}{/option:block4}{/option:block3}
				{option:!block3}{option:block4}{option:block5}
					<section class="mod"><div class="inner">
				{/option:block5}{/option:block4}{/option:!block3}

					{* Block 3 (Editor) *}
					{option:block3IsHTML}
						{option:block3}
							<header class="mainTitle">
								<h3>{$block3|striptags}</h3>
							</header>
						{/option:block3}
					{/option:block3IsHTML}

					<div class="bd">

						{* Block 4 (Editor) *}
						{option:block4IsHTML}
							{option:block4}
								<section class="mod col col-4">
									<div class="inner">
										<div class="bd content">
											{$block4}
										</div>
									</div>
								</section>
							{/option:block4}
						{/option:block4IsHTML}
						{option:!block4IsHTML}
							<div class="col col-4">
								{include:{$block4}}
								&nbsp;
							</div>
						{/option:!block4IsHTML}

						{* Block 5 (Editor) *}
						{option:block5IsHTML}
							{option:block5}
								<section class="mod col col-4 lastCol">
									<div class="inner">
										<div class="bd content">
											{$block5}
										</div>
									</div>
								</section>
							{/option:block5}
						{/option:block5IsHTML}
						{option:!block5IsHTML}
							<div class="col col-4 lastCol">
								{include:{$block4}}
								&nbsp;
							</div>
						{/option:!block5IsHTML}

					</div>

				{option:block3}{option:block4}{option:block5}
					</div></section>
				{/option:block5}{/option:block4}{/option:block3}
				{option:!block3}{option:block4}{option:block5}
					</div></section>
				{/option:block5}{/option:block4}{/option:!block3}

				{option:block6}{option:block7}{option:block8}
					<section class="mod"><div class="inner">
				{/option:block8}{/option:block7}{/option:block6}
				{option:!block6}{option:block7}{option:block8}
					<section class="mod"><div class="inner">
				{/option:block8}{/option:block7}{/option:!block6}

					{* Block 6 (Editor) *}
					{option:block6IsHTML}
						{option:block6}
							<header class="mainTitle">
								<h3>{$block6|striptags}</h3>
							</header>
						{/option:block6}
					{/option:block6IsHTML}

					<div class="bd">

						{* Block 7 (Editor) *}
						{option:block7IsHTML}
							{option:block7}
								<section class="mod col col-4">
									<div class="inner">
										<div class="bd content">
											{$block7}
										</div>
									</div>
								</section>
							{/option:block7}
						{/option:block7IsHTML}
						{option:!block7IsHTML}
							<div class="col col-4">
								{include:{$block7}}
								&nbsp;
							</div>
						{/option:!block7IsHTML}

						{* Block 8 (Editor) *}
						{option:block8IsHTML}
							{option:block8}
								<section class="mod col col-4 lastCol">
									<div class="inner">
										<div class="bd content">
											{$block8}
										</div>
									</div>
								</section>
							{/option:block8}
						{/option:block8IsHTML}
						{option:!block8IsHTML}
							<div class="col col-4 lastCol">
								{include:{$block8}}
								&nbsp;
							</div>
						{/option:!block8IsHTML}

					</div>

				{option:block6}{option:block7}{option:block8}
					</div></section>
				{/option:block8}{/option:block7}{/option:block6}
				{option:!block6}{option:block7}{option:block8}
					</div></section>
				{/option:block8}{/option:block7}{/option:!block6}

				{* Block 9 (default: Blog) *}
				{option:block9IsHTML}
					{option:block9}
						<section class="mod">
							<div class="inner">
								<div class="bd content">
									{$block9}
								</div>
							</div>
						</section>
					{/option:block9}
				{/option:block9IsHTML}
				{option:!block9IsHTML}
					{include:{$block9}}
				{/option:!block9IsHTML}

			</div>
		</div>
		<noscript>
			<div class="message notice">
				<h4>{$lblEnableJavascript|ucfirst}</h4>
				<p>{$msgEnableJavascript}</p>
			</div>
		</noscript>
	</div>
	<div id="bottomWrapper">
		<footer id="footer">
			<div class="container">

				{* Footer logo *}
				<div id="footerLogo">
					<p><a href="#">{$siteTitle}</a></p>
				</div>

				{* Footer navigation *}
				<nav id="footerNavigation">
					<h4>{$lblFooterNavigation}</h4>
					{$var|getnavigation:'page':0:1}
				</nav>

				{* Footer meta navigation *}
				<nav id="metaNavigation">
					<h4>Footer meta navigation</h4>
					<ul>
						{iteration:footerLinks}
							<li{option:footerLinks.selected} class="selected"{/option:footerLinks.selected}>
								<a href="{$footerLinks.url}" title="{$footerLinks.title}"{option:footerLinks.rel} rel="{$footerLinks.rel}"{/option:footerLinks.rel}>
									{$footerLinks.navigation_title}
								</a>
							</li>
						{/iteration:footerLinks}
						<li><a href="http://www.fork-cms.be" title="Fork CMS">Fork CMS</a></li>
					</ul>
				</nav>

				{* Block 11 (default: Search widget) *}
				{option:!block11IsHTML}
					<aside id="footerSearch">
						<h4>{$lblSearch|ucfirst}</h4>
						{include:{$block11}}
					</aside>
				{/option:!block11IsHTML}

				{* Block 10 (default: Social Media Content Block) *}
				{option:block10IsHTML}
					{option:block10}
					<aside id="footerSocial">
						<h4>{$lblSocialMedia|ucfirst}</h4>
						{$block10}
					</aside>
					{/option:block10}
				{/option:block10IsHTML}
				{option:!block10IsHTML}
					<aside id="footerSocial">
						<h4>{$lblSocialMedia|ucfirst}</h4>
						{include:{$block10}}
					</aside>
				{/option:!block10IsHTML}

			</div>
		</footer>
	</div>

	{* Site wide HTML *}
	{$siteHTMLFooter}

	{* General Javascript *}
	{iteration:javascriptFiles}
		<script src="{$javascriptFiles.file}"></script>
	{/iteration:javascriptFiles}

	{* Theme specific Javascript *}
	<script src="{$THEME_URL}/core/js/golden_gate.js"></script>

</body>
</html>