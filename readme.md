<p align="center">
    <img src="https://samcarre.dev/images/ww-example.png" alt="Wagonwheel" >
</p>

> Help support the maintenance of this package by [buying me a coffee or two](https://ko-fi.com/sammyjo20).

# Wagonwheel

**Offer an online version of your Laravel emails to users.**

[![Latest Stable Version](https://poser.pugx.org/sammyjo20/wagonwheel/v)](//packagist.org/packages/sammyjo20/wagonwheel) [![Total Downloads](https://poser.pugx.org/sammyjo20/wagonwheel/downloads)](//packagist.org/packages/sammyjo20/wagonwheel) [![License](https://poser.pugx.org/sammyjo20/wagonwheel/license)](//packagist.org/packages/sammyjo20/wagonwheel)

- Uses Laravel's built-in temporary signed URLs to create the URL for the online version. This means it's secured by your app's encryption key, as well as making it difficult to guess.

- Highly customisable.

- Easy to install.

- Supports Laravel 8

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
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Sammyjo20\Wagonwheel\Concerns\SaveForOnlineViewing;

class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels, SaveForOnlineViewing;
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Confirmed 🎉')
            ->markdown('emails.bookings.confirmed');
    }
}
```

## Configuration

If you would like to customise how Wagonwheel works. Run the following command to publish Wagonwheel's configuration file. 

```shell
php artisan vendor:publish --tag=wagonwheel-config
```

*component_placement* - This configuration variable defines if the banner should be rendered at the start of the email content or at the end of the email content. The available values are **start** and **end**.

*message_expires_in_days* - This configuration variable defines how long Wagonwheel should keep the online version of an email in days. If you would like the online version of your emails to never expire, set this to 0. **The default is 30 days**.

## Customisation

If you would like to customise how the banner looks inside the email, just publish Wagonwheel's views with the following command.
```shell
php artisan vendor:publish --tag=wagonwheel-views
```

## Testing
Run all tests

```
composer test
```

Run a specific test

```
composer test-f [name of test method]
```

## Thanks
- Ryan Chandler (@ryangjchandler) helped out massively with some great code improvements and overall making Wagonwheel better!
- Gareth Thompson (@cssgareth) helped out with coming up with a cool name!
