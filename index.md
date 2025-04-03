# Todo

## Worker

En production dans le frankenph/Caddyfile

Ajouter

```php
{
	{$CADDY_GLOBAL_OPTIONS}

	frankenphp {
		{$FRANKENPHP_CONFIG}

		worker {
			file ./public/index.php
			env APP_RUNTIME Runtime\FrankenPhpSymfony\Runtime
			{$FRANKENPHP_WORKER_CONFIG}
		}
	}
}
```
