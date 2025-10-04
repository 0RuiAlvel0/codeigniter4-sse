# CodeIgniter 4 SSE Module

Almost(!) plug-and-play Server-Sent Events (SSE) for CI4 apps.

Server-Sent Events (SSE) are a lightweight way for servers to push real-time updates to the browser over a single, long-lived HTTP connection. Unlike WebSockets, SSE is unidirectional—from server to client—and built on standard HTTP, making it easy to implement and scale. A common use case is live notifications: for example, a dashboard can use SSE to instantly alert users when a new order is placed, a remote sensor changes state, or a background job completes—without requiring the client to constantly poll the server.

## Installation

```bash
composer require 0ruialvel0/codeigniter4-sse
php spark sse:install
```
Tne install command will:
1. Install this file: public/js/sse-client.js. You need this as the event listener on the client side.
2. It will edit your .env file to add some required settings. These settings will be removed when you run the uninstaller. You can manually edit your .env file to add more events (you need to also add the new event listeners in JS).

## Configuration

Edit your `.env` file (these lines are created automatically when after you install):

```
SSE_STREAM_DURATION=15
SSE_POLL_INTERVAL=2
SSE_EVENTS=event_name1:event_cache_name1,event_name12:event_cache_name2
```

## Usage

Trigger events from anywhere in your code:

```php
cache()->save('event_cache_key1', '1');
```

This will set the corresponding cache file which will trigger the StreamController
into sending the event to the JS client.

## Practical example

1. Assuming a working fresh CI4 install, follow the procedure to install.
Then go to app/Controllers/Home.php controller and add

```php
cache()->save('event_cache_key1', '1');
```
on the index(method).

2. If everything installed correctly, you should have a public/js/sse-client.js file. open it and change the first trigger to open a browser alert when the event is detected

```js
source.addEventListener('event_name1', function(e) {
        const data = JSON.parse(e.data);
        alert('Event 1 triggered at ' + data.timestamp);
        // Add code here, e.g. alert('Event 1 triggered at ');
    });
```

3. For further checks to see if all is good add the following somewhere on the app/Views/welcome_message.php

```html
Class exists?
<?php 
var_dump(class_exists('\SseModule\Controllers\StreamController'));
?>
```
This will be "true" if it all installed well.

4. Observe in wonder: go to https://yoursite.com and after a few seconds (actually about the number of seconds you set on SSE_STREAM_DURATION, which by default is 15s and set during the script installation) you should see a browser alert with the trigger timestamp. Neat.

## Frontend

Include `public/js/`[`sse-client.js`](https://sse-client.js) and customize:

```javascript
source.addEventListener('event_name1', function(e) {
    const data = JSON.parse(e.data);
    // Add code here, e.g. alert('event triggered');
});
```

## Uninstall

```bash
php spark sse:uninstall
composer remove 0ruialvel0/codeigniter4-sse
```

## Extend

Add more events in `.env` with this syntax:

```
SSE_EVENTS=event_name1:event_cache_name1,event_name2:event_cache_name2
```
The event_name part needs to match what you have on your client JS.

## Testing
*** This is not active yet. I know this may very well be overkill, I am only doing this because I want to learn about unit testing. I am so ignorant that I do not even know if this makes any sense...

This module will include PHPUnit tests for stream output, config parsing, and event logic.

To run tests:

```bash
composer install
vendor/bin/phpunit
```

Test coverage includes:
- Stream controller output format
- Event triggering logic
- Config parsing
- Spark installer behavior

## License

MIT