# Jockey

## Installation

1. Copy and paste the following repository in your composer.json file

```json
"repositories": [
    {
        "type": "path",
        "url": "./../jockey"
    }
]
```

``composer require sammyjo20/jockey``

2. Publish and run the migrations

``php artisan vendor:publish``
 
``php artisan migrate``

3. Create a mailable, and replace the Mailable class with OnlineMailable

4. If you are using a constructor in your Mailable, make sure to add:

```php 
parent::__construct();
```

5. And that should be it!

(Very early stages.)

### Credits
https://laravelpackage.com/10-events-and-listeners.html#creating-an-event-service-provider
