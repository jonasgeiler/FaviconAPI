<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="theme-color" content="#0096bf" />

		<title><?= $status ?> - FaviconAPI, by Skayo</title>

		<link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">

		<script async defer data-domain="favicon.skayo.dev" src="https://analytics.skayo.dev/js/plausible.js"></script>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css" />
		<style type="text/css">
			body {
				height:          100vh;
				display:         flex;
				flex-direction:  column;
				justify-content: center;
				align-items:     center;
				margin-top:      0;
				margin-bottom:   0;
			}

			.code {
				margin-bottom: 0;
				font-size:     6em;
			}

			.message {
				margin-top: 0;
				text-align: center;
				color:      #a9b1ba;
			}

			.error {
				max-width: 100%;
			}
		</style>
	</head>
	<body>
		<h1 class="code"><?= $code ?></h1>
		<h2 class="message"><?= strtoupper($status) ?></h2>

		<pre class="error"><code><b><?= $text ?></b><?= $trace ? "\n\n$trace" : '' ?></code></pre>
	</body>
</html>
