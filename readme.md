<p align="center">
    <img src="https://samcarre.dev/images/wagonwheel-example.png" alt="Wagonwheel" height="400">
</p>

# Wagonwheel

**Offer an online version of all of your Laravel emails to your users.**

- Uses Laravel's built-in temporary signed URLs to create the URL for the online version. This means it's secured by your app's encryption key, as well as making it difficult to guess.

- Highly customisable.

- Easy to install.

- Supports Laravel 7+

## Installation
1. Install Wagonwheel using composer with the command below:

```shell
composer require sammyjo20/wagonwheel
```



2. Publish the migrations
```shell
php artisan vendor:publish --tag=wagonwheel-migrations
```



3. Run the migrations
```shell
php artisan migrate
```



4. Add the "SaveForOnlineViewing" trait to **any** of your Mailables.

```php
use Sammyjo20\Wagonwheel\Concerns\SaveForOnlineViewing;

class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels, SaveForOnlineViewing;
```

## Thanks
- Ryan Chandler (@ryangjchanlder) helped out massively with some great code improvements and overall making Wagonwheel better!
- Gareth Thompson (@cssgareth) helped out with coming up with a cool name!

## Configuration

If you would like to customise how Wagonwheel works. Run the following command to publish Wagonwheel's configuration file. 

```shell
php artisan vendor:publish --tag=wagonwheel-config
```

*component_placement* - This configuration variable defines if the banner should be rendered at the start of the email content or at the end of the email content. The available values are **start** and **end**.

*message_expires_in_days* - This configuration variable defines how long Wagonwheel should keep the online version of an email in days. **The default is 30 days**.

## Customisation

If you would like to customise how the banner looks inside the email, just publish Wagonwheel's views with the following command.
```shell
php artisan vendor:publish --tag=wagonwheel-views
```
