<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="theme-color" content="#0096bf" />

		<title>FaviconAPI, by Skayo</title>

		<link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">

		<meta name="title" content="FaviconAPI, by Skayo" />
		<meta name="description" content="Simple API for generating favicons ⚡" />

		<meta property="og:type" content="website" />
		<meta property="og:url" content="https://favicon.skayo.dev" />
		<meta property="og:site_name" content="FaviconAPI" />
		<meta property="og:title" content="FaviconAPI, by Skayo" />
		<meta property="og:description" content="Simple API for generating favicons ⚡" />
		<meta property="og:image" content="https://favicon.skayo.dev/img/social-preview.png" />

		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:url" content="https://favicon.skayo.dev" />
		<meta name="twitter:title" content="FaviconAPI, by Skayo" />
		<meta name="twitter:description" content="Simple API for generating favicons ⚡" />
		<meta name="twitter:image" content="https://favicon.skayo.dev/img/social-preview.png" />

		<script async defer data-domain="favicon.skayo.dev" src="https://analytics.skayo.dev/js/plausible.js"></script>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
		<style type="text/css">
			body > header, body > footer {
				text-align: center;
			}

			body > section {
				display:        flex;
				flex-direction: column;
			}

			.subtitle {
				color: var(--text-muted);
			}

			#email-field {
				margin-top: 1.5em;
			}

			.input-error {
				box-shadow: 0 0 0 2px #ef4444 !important;
			}

			.alert {
				display:       block;
				padding:       10px;
				margin:        1em 6px 1em 0;
				border-radius: 5px;
				font-weight:   500;
			}

			.alert.error {
				background-color: #e21313;
				color:            #fff;
			}

			.alert.success {
				background-color: #39a33c;
				color:            #fff;
			}
		</style>
	</head>
	<body>
		<header>
			<h1>&#x26A1; FaviconAPI</h1>
			<h3 class="subtitle">Simple API for generating favicons</h3>
		</header>

		<section>
			<h2>API Documentation</h2>

			<h3>Authorization</h3>
			<p>
				First you will need an API key.
				You can get yours <a href="#generate-your-key">here</a>.<br />
				The API key must be sent in every request using the <code>X-Api-Key</code> header.<br />
			</p>

			<h3>Errors</h3>
			<p>
				If an error occurs, the API will respond with a status code and a JSON document with some information about the error.
				Example response:
			</p>
			<pre><code>{
    "status": "Bad Request",
    "code": 400,
    "text": "You can only upload one image at a time",
    "success": false
}</code></pre>

			<h3>POST <i>/api/generate</i></h3>
			<p>
				This is currently the only API endpoint you can use.<br />
				To generate a favicon send a <code>POST</code> request to <code><?= $URL ?>/api/generate</code>.<br />
				Upload the image by encoding it in the body as <code>multipart/form-data</code>. (Any field name)<br />
				Example request:
			</p>
			<pre><code>POST /api/register HTTP/1.1
Host: <?= $HOST . PHP_EOL ?>
Content-Type: multipart/form-data; boundary=X-EXAMPLE-BOUNDARY
Accept: application/json
X-API-Key: lk4sbPT8B3yxHvJPvWiMVC8cpd6Mu1o1awcwCd33
Content-Length: 2000

--X-EXAMPLE-BOUNDARY
Content-Disposition: form-data; name="image"
(some image data ...)
--X-EXAMPLE-BOUNDARY--</code></pre>
			<p>
				The API responds with a JSON document containing the download URL of the generated ZIP archive.<br />
				Example response:
			</p>
			<pre><code>{
    "success": true,
    "download_url": "<?= $URL ?>/download/1ro9dnzb2crax.zip"
}</code></pre>
		</section>

		<section id="generate-your-key">
			<h2>Generate your API key</h2>
			<label for="email-field">
				To use the API, you need an API key, as usual. Type your email below to receive your key.
				You can also use this form to receive your key again if you forgot it.
				We will use your email address only for sending you the key and contacting you in case you abuse the API.
				We won't spam you, share your email or whatever.
			</label>

			<input id="email-field" type="email" placeholder="user@example.com" />
			<button id="email-submit" disabled>Generate your key</button>

			<div id="result-alert" class="alert" style="display: none"></div>
		</section>

		<footer class="footer">
			Made with &#x2764; by <a href="https://skayo.dev" rel="noopener" target="_blank">Skayo</a>
			&bull;
			<a href="/privacy">Privacy Policy</a>
		</footer>

		<script type="application/javascript">
			const emailField = document.getElementById('email-field');
			const emailSubmit = document.getElementById('email-submit');
			const resultAlert = document.getElementById('result-alert');

			function isValidEmail(email) {
				return (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)
					.test(email.toLowerCase());
			}

			emailField.addEventListener('input', function () {
				resultAlert.style.display = 'none';
				resultAlert.classList.remove('success', 'error');

				if (this.value.trim() === '') {
					emailField.classList.remove('input-error');
					emailSubmit.disabled = true;
					return;
				}

				if (!isValidEmail(this.value)) {
					emailField.classList.add('input-error');
					emailSubmit.disabled = true;
					return;
				}

				emailField.classList.remove('input-error');
				emailSubmit.disabled = false;
			});

			emailSubmit.addEventListener('click', async function () {
				if (!isValidEmail(emailField.value)) return;

				const body = 'email=' + encodeURIComponent(emailField.value);

				const response = await fetch('/api/register', {
					method:  'POST',
					headers: {'Content-Type': 'application/x-www-form-urlencoded'},
					body
				});

				const {success, text: error} = await response.json();

				resultAlert.style.display = null;
				resultAlert.classList.add(success ? 'success' : 'error');
				resultAlert.innerText = success
					? 'We have just sent you your API key, please check your email! (Also look into the spam folder)'
					: error;
			});
		</script>
	</body>
</html>
